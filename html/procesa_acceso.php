<?php
	include_once ("../include/inicia_ses.inc.php"); # Usamos sesion activa para obtener valor correo.
	include_once ("../include/datos.inc.php"); 		# Incluimos datos básicos de la BBDD.

	if(!isset($_SESSION['DatosUsuario'])){

		# Crear conexión
		$c = new mysqli($_SESSION['servidor'], $_SESSION['login'], $_SESSION['pass'], $_SESSION['BBDD']);
		# Comprobar conexión
		if ($c->connect_error){
			$_SESSION['OKingreso'] = 0;
			$_SESSION['BBDDError'] = "Conexión fallida: " . $c->connect_error;
			die();
		}
	
		# Obtener datos de tabla usuarios
		$usuario = 	$c->query("SELECT * FROM ".$_SESSION['usuarios']." 
					WHERE (email = '".$_SESSION['correo']."' AND clave = '".$_SESSION['clave']."')");

		# Eliminamos variable con la contraseña del usuario por seguridad.
		unset($_SESSION['clave']);
		# Cerrar conexión
		$c->close();

		if ($usuario->num_rows == 1) {
    		# Información usuario encontrado.
    		$fila = $usuario->fetch_assoc();
    	 	$_SESSION['DatosUsuario'] = $fila["nombre"]." " .$fila["apellido"];	
    	 	$_SESSION['NombreUsuario'] = $fila["nom_usuario"];
			# Variable para comprobar que el registro del usuario ha sido un éxito.
			# Se usa para mostrar mensaje éxito en página ppal.
			$_SESSION['OKingreso'] = 1;
		}
		else
		{
			$_SESSION['OKingreso'] = 0;
			$_SESSION['BBDDError'] = "Error acceso usuario";
		}

		header('Location: ../index.html');
	}
?>