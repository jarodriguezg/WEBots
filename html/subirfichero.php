<?php

include_once ("../include/inicia_ses.inc.php");

# Crear carpeta nueva si NO existe.
$serv = $_SERVER['DOCUMENT_ROOT'] . "/WEBots/competiciones/"; 
$ruta = $serv . $_SESSION['correo'];

if(!file_exists($ruta)){	mkdir($ruta);	echo "Se ha creado el directorio: " . $ruta;	} 
echo $ruta . "EXISTE. - ";
$ruta = $ruta . "/" . $_POST['competicion'];
$_SESSION['rutacompeticion'] = $ruta;
if(!file_exists($ruta)){	
	mkdir($ruta);	
	echo "Se ha creado el directorio: " . $ruta;	
	$ruta = $ruta . "/Prueba";
	for ($cont = 0; $cont < $_POST['numpruebas']; $cont++)
	{
		$rutaprueba = $ruta . ($cont + 1) . "/";
		mkdir($rutaprueba);		
		echo "Se ha creado el directorio: " . $rutaprueba;
	}


// En versiones de PHP anteriores a la 4.1.0, debería utilizarse $HTTP_POST_FILES en lugar
// de $_FILES.
	echo '<pre>';
	echo 'Más información de depuración:';
	print_r($_FILES);

# Subida de fichero para cada prueba, almacenamiento de ficheros necesarios, compilación y ejecución.
	for ($cont = 0; $cont < $_POST['numpruebas']; $cont++)
	{
		$dir_subida = '/var/www/WEBots/competiciones/'.$_SESSION['correo'].'/'.$_POST['competicion'].'/Prueba' . ($cont + 1) . '/';
		$fichero_subido = $dir_subida . basename($_FILES['fichero_usuario']['name'][$cont]);

		if (move_uploaded_file($_FILES['fichero_usuario']['tmp_name'][$cont], $fichero_subido)) {
		    echo "El fichero es válido y se subió con éxito.\n";
		} else {
		    echo "¡Posible ataque de subida de ficheros!\n";
		}

		$eficiencia = "/var/www/WEBots/uploads/EsqueletoEficiencia/eficiencia.cpp";

		$esqueletoeficiencia = "#include \"".$_FILES['fichero_usuario']['name'][$cont]."\"" . PHP_EOL . file_get_contents($eficiencia);

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

$(OBJS): ".$_FILES['fichero_usuario']['name'][$cont]."

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

		$resultado = substr($last_line, 6);
		$puntuación = 10 - $resultado;
		echo "<h1> Puntuación Prueba".($cont+1).": ".$puntuación."</h1>";

		passthru('cd '.$dir_subida.' && make clean');
	} # Fin del FOR que recorre las pruebas.

	echo '</pre>';
} # Fin de if(!file_exists($ruta . "/" . $_POST['competicion']))
# Si EXISTE la carpeta competición significa que el usuario ya ha participado.
# Deshabilitar botón Participar de las competiciones en las que el usuario haya participado.
else 	{	header('Location: ../html/competicion.html');	}

?>