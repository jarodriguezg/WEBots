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

	$nombresusuario = $c->query("SELECT nom_usuario FROM ".$_SESSION['usuarios']."");

	# Comprobamos si nom_usuario existe en la BBDD.
	if ($nombresusuario->num_rows > 0)
	{	
		while ($fila = $nombresusuario->fetch_array())
		{	
			if ($_POST['nombreusuario'] == $fila["nom_usuario"])
			{	
				$_SESSION['ExisteNombreUsuario'] = $_POST['nombreusuario'];
				$_SESSION['ExisteNomUsuario'] = 1;
				header('Location: perfil.html');
				exit();
			}
		}
	}

	# Condiciones para comprobar que datos ha introducido el usuario (datos a modificar).
	if ($_POST['nombre'] != ""){
		$actualiza_nombre = $c->query("UPDATE ".$_SESSION['usuarios']." 
		SET nombre='".$_POST['nombre']."' WHERE (nom_usuario='".$_SESSION['NombreUsuario']."')");
	}

	if ($_POST['apellido'] != ""){
		$actualiza_apellido = $c->query("UPDATE ".$_SESSION['usuarios']." 
		SET apellido='".$_POST['apellido']."' WHERE (nom_usuario='".$_SESSION['NombreUsuario']."')");
	}

	if ($_POST['clave'] != ""){
		$actualiza_clave = $c->query("UPDATE ".$_SESSION['usuarios']." 
		SET clave='".password_hash($_POST['clave'], PASSWORD_DEFAULT)."' WHERE (nom_usuario='".$_SESSION['NombreUsuario']."')");
	}
	if ($_POST['nombreusuario'] != ""){
		$actualiza_nombreusuario = $c->query("UPDATE ".$_SESSION['usuarios']." 
		SET nom_usuario='".$_POST['nombreusuario']."' WHERE (nom_usuario='".$_SESSION['NombreUsuario']."')");
		$c->query("UPDATE ".$_SESSION['pruebas']." SET nom_usuario='".$_POST['nombreusuario']."' WHERE (nom_usuario='".$_SESSION['NombreUsuario']."')");
		$c->query("UPDATE ".$_SESSION['puntuaciones']." SET nom_usuario='".$_POST['nombreusuario']."' WHERE (nom_usuario='".$_SESSION['NombreUsuario']."')");
		$ultimalinea = system('cd /var/www/WEBots/competiciones/ && mv '.$_SESSION['NombreUsuario'].' '.$_POST['nombreusuario'].'');
		$_SESSION['NombreUsuario'] = $_POST['nombreusuario'];
	}

	# Modificar datos de tabla Usuarios.
	if (isset($actualiza_nombreusuario) || isset($actualiza_nombre) || isset($actualiza_apellido) || isset($actualiza_clave))
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

	header('Location: perfil.html');
?>