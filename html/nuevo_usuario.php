<?php
	# require porque necesitamos la dirección de correo para registrar usuario con éxito.
	require ("../include/inicia_ses.inc.php"); 		# Usamos sesion activa para obtener valor correo.
	include_once ("../include/datos.inc.php"); 		# Incluimos datos básicos de la BBDD.

	// Crear conexión
	$c = new mysqli($_SESSION['servidor'], $_SESSION['login'], $_SESSION['pass'], $_SESSION['BBDD']);
	// Comprobar conexión
	if ($c->connect_error){
		$_SESSION['OKregistro'] = 0;
		$_SESSION['BBDDError'] = "Conexión fallida: " . $c->connect_error;
		die();
	}

	# Insertar datos en tabla usuarios
	if ($c->query("INSERT INTO ".$_SESSION['usuarios']." (email, nombre, apellido, clave) 
		VALUES ('".$_SESSION['correo']."', '".$_POST['nombre']."', '".$_POST['apellido']."', '".$_POST['clave']."')"))
	{	
		# Variable para comprobar que el registro del usuario ha sido un éxito.
		# Se usa para mostrar mensaje éxito en página ppal.
		$_SESSION['OKregistro'] = 1;
	}
	else
	{
		$_SESSION['OKregistro'] = 0;
		$_SESSION['BBDDError'] = "Error al insertar usuario: (" . $c->errno . ") " . $c->error;
	}

	# Cerrar conexión
	$c->close();

	header('Location: ../index.html');
?>