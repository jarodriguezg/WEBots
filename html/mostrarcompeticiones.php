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

	$existecompeticion = $c->query("SELECT * FROM ".$_SESSION['competiciones']."");

	# Cerrar conexión
	$c->close();

	if ($existecompeticion->num_rows == 0)
	{	
		# ¿Existen competiciones?
		echo "No existen competiciones";
		$_SESSION['DatosCompeticiones'] = 0;
	}
	else {	
		$_SESSION['DatosCompeticiones'] = $existecompeticion->num_rows;
		$_SESSION['nom_competicion'] = array();
		$_SESSION['num_pruebas'] = array();
		while($fila = $existecompeticion->fetch_array())
		{	
			array_push($_SESSION['nom_competicion'], $fila["nom_competicion"]);
			array_push($_SESSION['num_pruebas'], $fila["num_pruebas"]);	
		}
	}

	$existecompeticion->free();

	header('Location: competicion.html');
?>