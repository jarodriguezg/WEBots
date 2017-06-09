<?php
	header('Content-Type: text/html; charset=utf-8');

	# require porque necesitamos la dirección de correo para registrar usuario con éxito.
	require ("../include/inicia_ses.inc.php"); 		# Usamos sesion activa para obtener valor correo.
	include_once ("../include/datos.inc.php"); 		# Incluimos datos básicos de la BBDD.

	// Crear conexión
	$c = new mysqli($_SESSION['servidor'], $_SESSION['login'], $_SESSION['pass'], $_SESSION['BBDD']);
	$c->set_charset('utf8');
	// Comprobar conexión
	if ($c->connect_error){
		$_SESSION['OKregistro'] = 0;
		$_SESSION['BBDDError'] = "Conexión fallida: " . $c->connect_error;
		die();
	}

	$nombresusuario = $c->query("SELECT nom_usuario FROM ".$_SESSION['usuarios']."");

	# Comprobamos si nom_usuario existe en la BBDD.
	if ($nombresusuario->num_rows > 0)
	{	
		while($fila = $nombresusuario->fetch_array())
		{	
			if ($_POST['nombreusuario'] == $fila["nom_usuario"])
			{	
				$_SESSION['ExisteNomUsuario'] = 1;
				$_SESSION['ExisteNombreUsuario'] = $fila["nom_usuario"];	
				header('Location: registro.html');
				exit();
			}
		}
	}

	# Insertar datos en tabla usuarios
	if ($c->query("INSERT INTO ".$_SESSION['usuarios']." (email, nom_usuario, nombre, apellido, clave) 
		VALUES ('".$_SESSION['correo']."', '".$_POST['nombreusuario']."','".$_POST['nombre']."', '".$_POST['apellido']."', '".$_POST['clave']."')"))
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