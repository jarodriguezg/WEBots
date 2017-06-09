<?php
	header('Content-Type: text/html; charset=utf-8');
	
	include_once ("../include/inicia_ses.inc.php"); # Usamos sesion activa
	include_once ("../include/datos.inc.php"); 		# Incluimos datos básicos de la BBDD.

	// Crear conexión
	$c = new mysqli($_SESSION['servidor'], $_SESSION['login'], $_SESSION['pass'], $_SESSION['BBDD']);
	$c->set_charset('utf8');
	// Comprobar conexión
	if ($c->connect_error){
		$_SESSION['OKeliminar'] = 0;
		$_SESSION['BBDDError'] = "Conexión fallida: " . $c->connect_error;
		die();
	}

	# Eliminar usuario de la tabla Usuarios
	if ($c->query("DELETE FROM ".$_SESSION['usuarios']." WHERE ".$_SESSION['usuarios'].".email='".$_SESSION['correo']."'") &&
		$c->query("DELETE FROM ".$_SESSION['pruebas']." WHERE ".$_SESSION['pruebas'].".nom_usuario='".$_SESSION['NombreUsuario']."'") &&
		$c->query("DELETE FROM ".$_SESSION['puntuaciones']." WHERE ".$_SESSION['puntuaciones'].".nom_usuario='".$_SESSION['NombreUsuario']."'"))
	{	

		# Borrar los ficheros almacenados en la carpeta con el nombre de la competición de los usuarios que han participado en ella.
		$ultimalinea = system('cd /var/www/WEBots/competiciones/ && rm -r '.$_SESSION['NombreUsuario'].'');

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