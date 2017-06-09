<?php
	header('Content-Type: text/html; charset=utf-8');
	
	include_once ("../include/inicia_ses.inc.php"); # Usamos sesion activa
	include_once ("../include/datos.inc.php"); 		# Incluimos datos básicos de la BBDD.

	// Crear conexión
	$c = new mysqli($_SESSION['servidor'], $_SESSION['login'], $_SESSION['pass'], $_SESSION['BBDD']);
	$c->set_charset('utf8');
	// Comprobar conexión
	if ($c->connect_error){
		$_SESSION['OKeliminarcompeticion'] = 0;
		$_SESSION['BBDDError'] = "Conexión fallida: " . $c->connect_error;
		die();
	}

	# Eliminar competición de la tabla competiciones, pruebas de la competición, y puntuaciones relacionadas.
	if ($c->query("DELETE FROM ".$_SESSION['competiciones']." WHERE ".$_SESSION['competiciones'].".nom_competicion='".$_POST['nombrecompeticion']."'") &&
		$c->query("DELETE FROM ".$_SESSION['pruebas']." WHERE ".$_SESSION['pruebas'].".nom_competicion='".$_POST['nombrecompeticion']."'") &&
		$c->query("DELETE FROM ".$_SESSION['puntuaciones']." WHERE ".$_SESSION['puntuaciones'].".nom_competicion='".$_POST['nombrecompeticion']."'"))
	{	
		# Borrar los ficheros almacenados en la carpeta con el nombre de la competición de los usuarios que han participado en ella.
		$usuarios = $c->query("SELECT nom_usuario FROM ".$_SESSION['puntuaciones']." WHERE nom_competicion = '".$_POST['nombrecompeticion']."'");
		if ($usuarios->num_rows > 0)
		{
			while ($fila = $usuarios->fetch_array())
			{	
				$ultimalinea = system('cd /var/www/WEBots/competiciones/'.$fila["nom_usuario"].'/ && rm -r '.$_POST['nombrecompeticion'].'');
			}
		}
		# Variable para comprobar que la eliminación de la competición ha sido un éxito.
		# Se usa para mostrar mensaje éxito en página ppal.
		$_SESSION['OKeliminarcompeticion'] = 1;
	}
	else
	{
		$_SESSION['OKeliminarcompeticion'] = 0;
		$_SESSION['BBDDError'] = "Error al eliminar competición: (" . $c->errno . ") " . $c->error;
	}

	# Cerrar conexión
	$c->close();
	
	header('Location: datoscompeticiones.php');
?>