<?php
	include_once ("../include/inicia_ses.inc.php"); 	# Usamos sesion activa para obtener datos.
	include_once ("../include/datos.inc.php");  		# Incluimos datos básicos de la BBDD.

	// Crear conexión
	$c = new mysqli($_SESSION['servidor'], $_SESSION['login'], $_SESSION['pass'], $_SESSION['BBDD']);
	// Comprobar conexión
	if ($c->connect_error){
		$_SESSION['BBDDError'] = "Conexión fallida: " . $c->connect_error;
		die();
	}

	# Cada vez que mostramos puntuaciones actualizamos datos.
	unset($_SESSION['DatosCompeticion']);
	unset($_SESSION['NumCompeticiones']);

	$existecompeticion	= $c->query("SELECT nom_competicion FROM ".$_SESSION['competiciones']."");

	# Obtenemos todas las competiciones.
	if ($existecompeticion->num_rows == 0)
	{	
		# ¿Existen competiciones?
		$_SESSION['NumCompeticiones'] = 0;
	}
	else {	
		$_SESSION['NumCompeticiones'] = $existecompeticion->num_rows;
		$cont = 0;

		while($fila = $existecompeticion->fetch_array())
		{	
			$existepuntuacion 	= $c->query("SELECT num_prueba, nom_fichero, puntuacion_prueba FROM ".$_SESSION['pruebas']." WHERE (nom_competicion='".$fila["nom_competicion"]."') 
											AND (nom_usuario='".$_SESSION['NombreUsuario']."') ORDER BY ".$_SESSION['pruebas'].".num_prueba");

			# Array que almacena las puntuacion para cada competicion.
			$_SESSION['DatosCompeticion'][$cont] = array();

			# Obtenemos todas las puntuaciones de cada competicion.
			if ($existepuntuacion->num_rows == 0)
			{	
				# ¿Existen puntuaciones?
				array_push($_SESSION['DatosCompeticion'][$cont], $fila["nom_competicion"], $existepuntuacion->num_rows);
			}
			else {	
				$num_prueba = array();
				$nom_fichero = array();
				$puntuacion_prueba = array();

				while($filapunt = $existepuntuacion->fetch_array())
				{	
					array_push($num_prueba, $filapunt["num_prueba"]);
					array_push($nom_fichero, $filapunt["nom_fichero"]);
					array_push($puntuacion_prueba, $filapunt["puntuacion_prueba"]);	
				}
				array_push($_SESSION['DatosCompeticion'][$cont], $fila["nom_competicion"], $existepuntuacion->num_rows, $num_prueba, $nom_fichero, $puntuacion_prueba);
			}
			$cont++;
		}
	}
	$_SESSION['NumPuntuacionesUsuario'] = $existepuntuacion->num_rows;
	
	# Cerrar conexión
	$c->close();

	$existecompeticion->free();
	$existepuntuacion->free();

	header('Location: mispuntuaciones.html');
?>