<?php
	header('Content-Type: text/html; charset=utf-8');

	include_once ("../include/inicia_ses.inc.php"); 	# Usamos sesion activa para obtener datos.
	include_once ("../include/datos.inc.php");  		# Incluimos datos básicos de la BBDD.

	// Crear conexión
	$c = new mysqli($_SESSION['servidor'], $_SESSION['login'], $_SESSION['pass'], $_SESSION['BBDD']);
	$c->set_charset('utf8');
	// Comprobar conexión
	if ($c->connect_error){
		$_SESSION['BBDDError'] = "Conexión fallida: " . $c->connect_error;
		die();
	}

	$existecompeticion 	= $c->query("SELECT * FROM ".$_SESSION['competiciones']."");

	# Cerrar conexión
	$c->close();

	# Obtenemos todas las competiciones disponibles.
	if ($existecompeticion->num_rows == 0)
	{	
		# ¿Existen competiciones?
		$_SESSION['NumCompeticiones'] = 0;
	}
	else {	
		$_SESSION['NumCompeticiones'] = $existecompeticion->num_rows;
		$_SESSION['nom_competicion'] = array();
		$_SESSION['num_pruebas'] = array();
		$_SESSION['fecha_inicio'] = array();
		$_SESSION['fecha_fin'] = array();
		$_SESSION['descripcion_competicion'] = array();

		while($fila = $existecompeticion->fetch_array())
		{	
			array_push($_SESSION['nom_competicion'], $fila["nom_competicion"]);
			array_push($_SESSION['num_pruebas'], $fila["num_pruebas"]);	
			array_push($_SESSION['fecha_inicio'], $fila["fecha_inicio"]);	
			array_push($_SESSION['fecha_fin'], $fila["fecha_fin"]);	
			array_push($_SESSION['descripcion_competicion'], $fila["descripcion"]);	
		}
	}

	$existecompeticion->free();

	header('Location: administrarcompeticion.html');
?>