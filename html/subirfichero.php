<?php
header('Content-Type: text/html; charset=utf-8');

include_once ("../include/inicia_ses.inc.php"); 	# Usamos sesion activa para obtener datos.
include_once ("../include/datos.inc.php");  		# Incluimos datos básicos de la BBDD.

# Validación y comprobaciones de fichero/s elegidos por el usuario.
# Usamos la variable de sesión para visualizar mensajes de error por pruebas.
$_SESSION['PruebasCompeticion'] = $_POST['numpruebas'];
for ($prueba = 0; $prueba < $_POST['numpruebas']; $prueba++){
	# $_FILES['fichero_usuario']['error'][$prueba]) == 0 (Archivo subido OK), == 1-8 (Diversos errores al subir fichero).
	if($_FILES['fichero_usuario']['error'][$prueba] > 0)
	{	$_SESSION['ErrorFichero'][$prueba] = "Error al subir fichero seleccionado";	}
	else{
		# El tipo de fichero insertado NO es nom_fichero.h (x-chdr)
		if($_FILES['fichero_usuario']['type'][$prueba] != "text/x-chdr")
		{	$_SESSION['ErrorFichero'][$prueba] = "El tipo de fichero introducido no es correcto";	}
		# Fichero vacio.
		if($_FILES['fichero_usuario']['size'][$prueba] == 0)
		{	$_SESSION['ErrorFichero'][$prueba] = "Fichero Vacío";	}
	}
}

# Si existen errores terminamos la ejecución del código. (Evitar creación de carpetas y ficheros)
if (isset($_SESSION['ErrorFichero'])) {	header('Location: ../html/competicion.html');	exit();	}

	# Comprobamos que cada fichero tiene un nombre distinto.
	for ($a = 0; $a < ($_POST['numpruebas'] - 1); $a++){	
		for ($b = ($a + 1); $b < $_POST['numpruebas']; $b++){		
			if ($_FILES['fichero_usuario']['name'][$a] == $_FILES['fichero_usuario']['name'][$b])
			{	
				$_SESSION['ErrorFichero'][$a] = "Existen ficheros con el mismo nombre";
				$_SESSION['ErrorFichero'][$b] = "Existen ficheros con el mismo nombre";	
				header('Location: ../html/competicion.html');	
				exit();
			}
		}
	}

# Crear carpeta nueva si NO existe.
$serv = $_SERVER['DOCUMENT_ROOT'] . "/WEBots/competiciones/"; 
$ruta = $serv . $_SESSION['NombreUsuario'];

if(!file_exists($ruta)){	mkdir($ruta);	echo "Se ha creado el directorio: " . $ruta;	} 
$ruta = $ruta . "/" . $_POST['competicion'];
if(!file_exists($ruta)){	
	mkdir($ruta);	

	# Variable que almacena el resultado de la competicion.
	$puntuacioncompeticion = 0;

	# Subida de fichero para cada prueba, almacenamiento de ficheros necesarios, compilación y ejecución.
	for ($prueba = 0; $prueba < $_POST['numpruebas']; $prueba++)
	{
		$rutaprueba = $ruta . "/Prueba" . ($prueba + 1) . "/";
		mkdir($rutaprueba);		

		$dir_subida = '/var/www/WEBots/competiciones/'.$_SESSION['NombreUsuario'].'/'.$_POST['competicion'].'/Prueba' . ($prueba + 1) . '/';
		$fichero_subido = $dir_subida . basename($_FILES['fichero_usuario']['name'][$prueba]);

		# Comprobación de que el fichero se sube correctamente
		if (!move_uploaded_file($_FILES['fichero_usuario']['tmp_name'][$prueba], $fichero_subido)) {
		    $_SESSION['ErrorFichero'][$prueba] = "Error en la subida del fichero";
		    header('Location: ../html/competicion.html');	
			exit();
		}

		$eficiencia = "/var/www/WEBots/uploads/EsqueletoEficiencia/eficiencia.cpp";

		$esqueletoeficiencia = "#include \"".$_FILES['fichero_usuario']['name'][$prueba]."\"" . PHP_EOL . file_get_contents($eficiencia);

		$nuevoeficiencia = $dir_subida . "eficientenuevo.cpp";
		file_put_contents($nuevoeficiencia, $esqueletoeficiencia);

		$contenidomakefile = "# Compilador de C++ y opciones de compilación.

CXX = c++
CXXFLAGS = -ansi -Wall -O$(OPTIMIZACION)

# Nivel de optimización (por omisión, no se optimiza).

OPTIMIZACION = 3

# Módulos objeto y ejecutables.

OBJS = eficientenuevo.o
EXES = eficientenuevo

# Ficheros de tiempo y de gráficas.

TIEMPOS = eficientenuevo.tmp
GRAFICAS = eficientenuevo.eps

# Por omisión, obtiene los ficheros de tiempo.

all: $(TIEMPOS)

# Obtención de los ejecutables.

eficientenuevo: eficientenuevo.o
		$(CXX) $(LDFLAGS) -o $@ $^

# Obtención de los objetos.

$(OBJS): ".$_FILES['fichero_usuario']['name'][$prueba]."

eficientenuevo.o: cronometro.h

# Obtención de los ficheros de tiempo.

eficientenuevo.tmp: eficientenuevo
		./$< | tee $@

# Obtención de las gráficas.

graficas:
	gnuplot graficas.plot

graficas-eps:
	gnuplot graficas-eps.plot

# Limpieza del directorio.

clean:
	$(RM) $(EXES) $(OBJS) *~

clean-all: clean
	$(RM) $(TIEMPOS) $(GRAFICAS)";

		$makefile = $dir_subida . "makefile";
		file_put_contents($makefile, $contenidomakefile);

		$last_line = system('cd '.$dir_subida.' && cp /var/www/WEBots/uploads/EsqueletoEficiencia/cronometro.h ./ && make', $retval);

		# Obtenemos el tiempo de ejecución total y obtenemos puntuación/prueba (sobre 10).
		$resultado = substr($last_line, 6);
		# Cuanto mayor sea $resultado menos eficiente es el algoritmo (menor $puntuación).
		$puntuacion = 10 - $resultado;
		$puntuacioncompeticion = $puntuacioncompeticion + $puntuacion;

		# Insertar la puntuación obtenida en cada prueba.
		// Crear conexión
		$c = new mysqli($_SESSION['servidor'], $_SESSION['login'], $_SESSION['pass'], $_SESSION['BBDD']);
		$c->set_charset('utf8');
		// Comprobar conexión
		if ($c->connect_error){
			$_SESSION['BBDDError'] = "Conexión fallida: " . $c->connect_error;
			die();
		}

		# Si la puntuación es 10, quiere decir que el fichero no se ha ejecutado correctamente. ($resultado == 0)
		if ($puntuacion == 10)
		{
			$c->query("DELETE FROM ".$_SESSION['pruebas']." WHERE ".$_SESSION['pruebas'].".nom_competicion='".$_POST['competicion']."'");
			$c->query("DELETE FROM ".$_SESSION['puntuaciones']." WHERE ".$_SESSION['puntuaciones'].".nom_competicion='".$_POST['competicion']."'");
			# Cerrar conexión
			$c->close();
			
			$_SESSION['ErrorFichero'][$prueba] = "El fichero no cumple el esqueleto aportado";
			passthru('cd /var/www/WEBots/competiciones/'.$_SESSION['NombreUsuario'].'/ && rm -r '.$_POST['competicion'].'');
			header('Location: ../html/competicion.html');
			exit();
		} 

		# Insertar datos en tabla pruebas. $_FILES['fichero_usuario']['name'][$prueba]
		if (!$c->query("INSERT INTO ".$_SESSION['pruebas']." (num_prueba, nom_competicion, nom_usuario, puntuacion_prueba, nom_fichero) 
			VALUES ('".($prueba + 1)."', '".$_POST['competicion']."', '".$_SESSION['NombreUsuario']."', '".$puntuacion."','".$_FILES['fichero_usuario']['name'][$prueba]."')"))
		{	$_SESSION['BBDDError'] = "Error al insertar prueba: (" . $c->errno . ") " . $c->error;	}

		passthru('cd '.$dir_subida.' && make clean');
	} # Fin del FOR que recorre las pruebas.

	# Calculamos la puntuación total de la competición haciendo una media aritmetica de los resultados de las pruebas.
	$puntuaciontotal = ($puntuacioncompeticion / $_POST['numpruebas']);

	# Insertar la puntuación obtenida en la competición.
	if (!$c->query("INSERT INTO ".$_SESSION['puntuaciones']." (nom_usuario, nom_competicion, puntuacion_total) 
		VALUES ('".$_SESSION['NombreUsuario']."', '".$_POST['competicion']."', '".$puntuaciontotal."')"))
	{	$_SESSION['BBDDError'] = "Error al insertar puntuacion: (" . $c->errno . ") " . $c->error;	}

	# Cerrar conexión
	$c->close(); 

	header('Location: ../html/mostrarmiscompeticiones.php');
} # Fin de if(!file_exists($ruta . "/" . $_POST['competicion']))
# Si EXISTE la carpeta competición significa que el usuario ya ha participado.
# Deshabilitar botón Participar de las competiciones en las que el usuario haya participado.
else 	{	header('Location: ../html/mostrarcompeticiones.php');	}

?>