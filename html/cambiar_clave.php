<?php
	header('Content-Type: text/html; charset=utf-8');

	include_once ("../include/inicia_ses.inc.php"); # Usamos sesion activa.
	include_once ("../include/datos.inc.php"); 		# Incluimos datos básicos de la BBDD.

	// Crear conexión
	$c = new mysqli($_SESSION['servidor'], $_SESSION['login'], $_SESSION['pass'], $_SESSION['BBDD']);
	$c->set_charset('utf8');
	// Comprobar conexión
	if ($c->connect_error){
		$_SESSION['OKeditar'] = 0;
		$_SESSION['BBDDError'] = "Conexión fallida: " . $c->connect_error;
		die();
	}

	# Condiciones para comprobar que datos ha introducido el usuario (datos a modificar).
	if ($_POST['clave'] != ""){
		$actualiza_clave = $c->query("UPDATE ".$_SESSION['usuarios']." 
		SET clave='".password_hash($_POST['clave'], PASSWORD_DEFAULT)."' WHERE (email='".$_SESSION['correo']."')");
	}

	# Modificar datos de tabla Usuarios.
	if (isset($actualiza_clave))
	{	
		# Variable para comprobar que la modificación del usuario ha sido un éxito.
		# Se usa para mostrar mensaje éxito.
		$_SESSION['OKeditar'] = 1;
	}
	else
	{
		$_SESSION['OKeditar'] = 0;
		$_SESSION['BBDDError'] = "Error al editar usuario: (" . $c->errno . ") " . $c->error;
	}

	# Cerrar conexión
	$c->close();

	# ¿Forzar al cierre de sesión (Modificar datos implica cerrar sesión)?
	# header('Location: procesa_cierre.php'); 

	header('Location: cambiar_clave.html');
?>