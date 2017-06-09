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

	$competicion = $c->query("SELECT nom_competicion, puntuacion_total FROM ".$_SESSION['puntuaciones']." WHERE (nom_usuario='".$_SESSION['NombreUsuario']."') ORDER BY puntuacion_total DESC");

	# Cerrar conexión
	$c->close();

	# Eliminamos variables para actualizar datos.
	unset($_SESSION['competicion']);
	unset($_SESSION['puntuacioncomp']);
	unset($_SESSION['NumCompeticionesUsuario']);
	
	# Obtenemos las competiciones en las que el usuario ha participado.
	if ($competicion->num_rows > 0)
	{	
		$_SESSION['competicion'] 	= array();
		$_SESSION['puntuacioncomp']	= array();

		while($fila = $competicion->fetch_array())
		{	
			array_push($_SESSION['competicion'], $fila["nom_competicion"]);
			array_push($_SESSION['puntuacioncomp'], $fila["puntuacion_total"]);
		}
	}
	$_SESSION['NumCompeticionesUsuario'] = $competicion->num_rows;

	$competicion->free();

	header('Location: miscompeticiones.html');
?>