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

	$existecompeticion 		= $c->query("SELECT * FROM ".$_SESSION['competiciones']."");
	$competicionrealizada 	= $c->query("SELECT nom_competicion FROM ".$_SESSION['puntuaciones']." WHERE (nom_usuario='".$_SESSION['correo']."')");

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

		while($fila = $existecompeticion->fetch_array())
		{	
			array_push($_SESSION['nom_competicion'], $fila["nom_competicion"]);
			array_push($_SESSION['num_pruebas'], $fila["num_pruebas"]);	
		}
	}

	unset($_SESSION['competicionrealizada']);
	# Obtenemos las competiciones en las que el usuario ha participado.
	if ($competicionrealizada->num_rows > 0)
	{	
		$_SESSION['competicionrealizada'] = array();

		while($fila = $competicionrealizada->fetch_array())
		{	
			array_push($_SESSION['competicionrealizada'], $fila["nom_competicion"]);
		}
	}

	$existecompeticion->free();
	$competicionrealizada->free();

	# unset($_SESSION['competicionrealizada']);

	header('Location: competicion.html');
?>