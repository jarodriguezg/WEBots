<?php
	include_once ("inicia_ses.inc.php"); 	# Usamos sesion activa para obtener datos.
	include_once ("datos.inc.php");  		# Incluimos datos básicos de la BBDD.

	# Incluimos variables para usar en archivos posteriores.
	if (isset($_POST['correo'])){	$_SESSION['correo'] = $_POST['correo'];	}
	if (isset($_POST['clave'])){	$_SESSION['clave'] = $_POST['clave']; 	}
	else { unset($_SESSION['clave']); }

	// Crear conexión
	$c = new mysqli($_SESSION['servidor'], $_SESSION['login'], $_SESSION['pass'], $_SESSION['BBDD']);
	// Comprobar conexión
	if ($c->connect_error){
		$_SESSION['OKdatos'] = 0;
		$_SESSION['BBDDError'] = "Conexión fallida: " . $c->connect_error;
		die();
	}

	# Usuario valido registrado en el sistema (correo y clave coinciden).
	if (isset($_SESSION['clave'])){
		$existeusuario = $c->query("SELECT clave FROM ".$_SESSION['usuarios']."
			WHERE (email='".$_SESSION['correo']."') ");
		if ($existeusuario->num_rows == 1)
		{
			$fila = $existeusuario->fetch_assoc();
			if (password_verify($_SESSION['clave'], $fila["clave"])) {	$comprobarclave = 1;	}
		}
	}
	else {
		# Correo se encuentra registrado en el sistema.
		$existecorreo = $c->query("SELECT nombre FROM ".$_SESSION['usuarios']."
			WHERE (email='".$_SESSION['correo']."')");
	}

	# Cerrar conexión
	$c->close();

	# Si existe correo
	if (isset($existecorreo)){
		if ($existecorreo->num_rows == 1)
		{	
			# Prohibir acceso al registro del usuario: header('Location: ../html/registro.html');
			$_SESSION['OKdatos'] = 1; 
		}
		else 
		{	
			$_SESSION['reginicial'] = 1; 
			header('Location: ../html/registro.html');	
		}
	}
	# Si existe usuario válido (usuario-clave).
	else if (isset($existeusuario))
	{	
		# Comprobamos que la consulta devuelve algún valor (indicativo de que Usuario existe en BBDD).
		if ($comprobarclave == 1){	header('Location: ../html/procesa_acceso.php');	}
		else { $_SESSION['OKdatos'] = 2; }	# Consulta sin resultados, usuario introducido no existe en BBDD.
	}
	/* else
	{ 
		$_SESSION['OKdatos'] = 0;
		$_SESSION['BBDDError'] = "Error al consultar usuario: (" . $c->errno . ") " . $c->error;
	}	*/

	if (isset($_SESSION['OKdatos'])){	header('Location: ../index.html');	}
?>