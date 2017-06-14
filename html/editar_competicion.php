<?php
	header('Content-Type: text/html; charset=utf-8');

	include_once ("../include/inicia_ses.inc.php"); # Usamos sesion activa.
	include_once ("../include/datos.inc.php"); 		# Incluimos datos básicos de la BBDD.

	// Crear conexión
	$c = new mysqli($_SESSION['servidor'], $_SESSION['login'], $_SESSION['pass'], $_SESSION['BBDD']);
	$c->set_charset('utf8');
	// Comprobar conexión
	if ($c->connect_error){
		$_SESSION['OKeditarcompeticion'] = 0;
		$_SESSION['BBDDError'] = "Conexión fallida: " . $c->connect_error;
		die();
	}

	$existecompeticion = $c->query("SELECT nom_competicion FROM ".$_SESSION['competiciones']." 	WHERE nom_competicion='".$_POST['nombrecompeticion']."'");

	# Si la consulta devuelve algún dato, NombreCompetición Existe.
	if ($existecompeticion->num_rows == 1)
	{	
		$_SESSION['NombreCompeticion'] = $_POST['nombrecompeticion'];	
		$existecompeticion->free();	
		header('Location: datoscompeticiones.php');
		exit();	
	}

	# Reemplazamos los retornos de carro y los saltos de linea.
	$descripcion = str_replace("\r\n", "</br>", $_POST['descripcion']);
	
	# Condiciones para comprobar los datos que ha introducido el usuario (datos a modificar).
	$diafechainicial = substr($_POST['fechainicio'], 0, 2);
	$mesfechainicial = substr($_POST['fechainicio'], 3, 2);
	$aniofechainicial = substr($_POST['fechainicio'], 6, 4);

	$diafechafinal = substr($_POST['fechafin'], 0, 2);
	$mesfechafinal = substr($_POST['fechafin'], 3, 2);
	$aniofechafinal = substr($_POST['fechafin'], 6, 4);

	if ($_POST['fechainicio'] != ""){
		if (checkdate($mesfechainicial, $diafechainicial, $aniofechainicial))
		{
			$actualiza_fechainicio = $c->query("UPDATE ".$_SESSION['competiciones']." 
			SET fecha_inicio='".$_POST['fechainicio']."' WHERE (nom_competicion='".$_POST['nombrecompeticioninicial']."')");
		}
		else {	$_SESSION['OKeditarcompeticion'] = 2;	header('Location: datoscompeticiones.php'); exit(); }
	}

	if ($_POST['fechafin'] != ""){
		if (checkdate($mesfechafinal, $diafechafinal, $aniofechafinal))
		{
			$actualiza_fechafin = $c->query("UPDATE ".$_SESSION['competiciones']." 
			SET fecha_fin='".$_POST['fechafin']."' WHERE (nom_competicion='".$_POST['nombrecompeticioninicial']."')");
		}
		else {	$_SESSION['OKeditarcompeticion'] = 2;	header('Location: datoscompeticiones.php'); exit(); }
	}

	if ($_POST['numpruebas'] != ""){
		$actualiza_numpruebas = $c->query("UPDATE ".$_SESSION['competiciones']." 
		SET num_pruebas='".$_POST['numpruebas']."' WHERE (nom_competicion='".$_POST['nombrecompeticioninicial']."')");
	}
	if ($_POST['descripcion'] != ""){
		$actualiza_descripcion = $c->query("UPDATE ".$_SESSION['competiciones']." 
		SET descripcion='".$descripcion."' WHERE (nom_competicion='".$_POST['nombrecompeticioninicial']."')");
	}

	# Se modifica el último para que los demás cambios se lleven a cabo correctamente.
	if ($_POST['nombrecompeticion'] != ""){
		$actualiza_nombrecompeticion = $c->query("UPDATE ".$_SESSION['competiciones']." 
		SET nom_competicion='".$_POST['nombrecompeticion']."' WHERE (nom_competicion='".$_POST['nombrecompeticioninicial']."')");
		$c->query("UPDATE ".$_SESSION['pruebas']." SET nom_competicion='".$_POST['nombrecompeticion']."' WHERE (nom_competicion='".$_POST['nombrecompeticioninicial']."')");
		$c->query("UPDATE ".$_SESSION['puntuaciones']." SET nom_competicion='".$_POST['nombrecompeticion']."' WHERE (nom_competicion='".$_POST['nombrecompeticioninicial']."')");

		$usuarios = $c->query("SELECT nom_usuario FROM ".$_SESSION['puntuaciones']." WHERE nom_competicion = '".$_POST['nombrecompeticion']."'");
		if ($usuarios->num_rows > 0)
		{
			while ($fila = $usuarios->fetch_array())
			{
				$ultimalinea = system('cd /var/www/WEBots/competiciones/'.$fila["nom_usuario"].'/ && mv '.$_POST['nombrecompeticioninicial'].' '.$_POST['nombrecompeticion'].'');
			}
		}
	}

	# Modificar datos de tabla competiciones.
	if (isset($actualiza_nombrecompeticion) || isset($actualiza_numpruebas) || isset($actualiza_fechainicio) || isset($actualiza_fechafin) ||isset($actualiza_descripcion))
	{	
		# Variable para comprobar que la modificación de la competición ha sido un éxito.
		# Se usa para mostrar mensaje éxito.
		$_SESSION['OKeditarcompeticion'] = 1;
	}
	else
	{
		$_SESSION['OKeditarcompeticion'] = 0;
		$_SESSION['BBDDError'] = "Error al editar competición: (" . $c->errno . ") " . $c->error;
	}

	# Cerrar conexión
	$c->close();

	# ¿Forzar al cierre de sesión (Modificar datos implica cerrar sesión)?
	# header('Location: procesa_cierre.php'); 

	header('Location: datoscompeticiones.php');
?>