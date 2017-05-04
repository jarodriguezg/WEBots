<?php
	include_once("../include/inicia_ses.inc.php");

	# Borramos los datos de la sesión 
	session_unset($_SESSION['DatosUsuario']);

	# Cerramos la sesion y llamamos a la página principal
	header('Location: ../index.html');
?>