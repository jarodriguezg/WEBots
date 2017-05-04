<?php
	include_once ("../include/inicia_ses.inc.php"); # Usamos sesion activa
	include_once ("../include/datos.inc.php"); 		# Incluimos datos básicos de la BBDD.

	// Crear conexión
	$c = new mysqli($_SESSION['servidor'], $_SESSION['login'], $_SESSION['pass'], $_SESSION['BBDD']);
	// Comprobar conexión
	if ($c->connect_error){
		$_SESSION['OKeliminar'] = 0;
		$_SESSION['BBDDError'] = "Conexión fallida: " . $c->connect_error;
		die();
	}

	# Eliminar usuario de la tabla Usuarios
	if ($c->query("DELETE FROM ".$_SESSION['usuarios']." WHERE ".$_SESSION['usuarios'].".email='".$_SESSION['correo']."'"))
	{	
		# Variable para comprobar que la eliminación del usuario ha sido un éxito.
		# Se usa para mostrar mensaje éxito en página ppal.
		$_SESSION['OKeliminar'] = 1;
	}
	else
	{
		$_SESSION['OKeliminar'] = 0;
		$_SESSION['BBDDError'] = "Error al eliminar usuario: (" . $c->errno . ") " . $c->error;
	}

	# Cerrar conexión
	$c->close();

	header('Location: ../html/procesa_cierre.php');
?>