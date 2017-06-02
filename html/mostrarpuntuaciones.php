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
			$existepuntuacion 	= $c->query("SELECT * FROM ".$_SESSION['puntuaciones']." WHERE (nom_competicion='".$fila["nom_competicion"]."') ORDER BY ".$_SESSION['puntuaciones'].".puntuacion_total DESC");

			# Array que almacena las puntuacion para cada competicion.
			$_SESSION['DatosCompeticion'][$cont] = array();

			# Obtenemos todas las puntuaciones de cada competicion.
			if ($existepuntuacion->num_rows == 0)
			{	
				# ¿Existen puntuaciones?
				array_push($_SESSION['DatosCompeticion'][$cont], $fila["nom_competicion"], $existepuntuacion->num_rows);
			}
			else {	
				$nom_usuario = array();
				$puntuacion_total = array();

				while($filapunt = $existepuntuacion->fetch_array())
				{	
					array_push($nom_usuario, $filapunt["nom_usuario"]);
					array_push($puntuacion_total, $filapunt["puntuacion_total"]);	
				}
				array_push($_SESSION['DatosCompeticion'][$cont], $fila["nom_competicion"], $existepuntuacion->num_rows, $nom_usuario, $puntuacion_total);
			}
			$cont++;
		}
	}
	
	# Cerrar conexión
	$c->close();

	$existecompeticion->free();
	$existepuntuacion->free();

	header('Location: puntuaciones.html');
?>