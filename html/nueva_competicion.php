<?php
	header('Content-Type: text/html; charset=utf-8');

	require ("../include/inicia_ses.inc.php"); 		
	include_once ("../include/datos.inc.php"); 		# Incluimos datos básicos de la BBDD.

	// Crear conexión
	$c = new mysqli($_SESSION['servidor'], $_SESSION['login'], $_SESSION['pass'], $_SESSION['BBDD']);
	$c->set_charset('utf8');
	// Comprobar conexión
	if ($c->connect_error){
		$_SESSION['OKcompeticion'] = 0;
		$_SESSION['BBDDError'] = "Conexión fallida: " . $c->connect_error;
		die();
	}

	$existecompeticion = $c->query("SELECT nom_competicion FROM ".$_SESSION['competiciones']." 	WHERE nom_competicion='".$_POST['nombrecompeticion']."'");

	# Si la consulta devuelve algún dato, NombreCompetición Existe.
	if ($existecompeticion->num_rows == 1)
	{	
		$_SESSION['NombreCompeticion'] = $_POST['nombrecompeticion'];	
		$existecompeticion->free();	
		header('Location: crearcompeticion.html');
		exit();	
	}

	$diafechainicial = substr($_POST['fechainicio'], 0, 2);
	$mesfechainicial = substr($_POST['fechainicio'], 3, 2);
	$aniofechainicial = substr($_POST['fechainicio'], 6, 4);

	$diafechafinal = substr($_POST['fechafin'], 0, 2);
	$mesfechafinal = substr($_POST['fechafin'], 3, 2);
	$aniofechafinal = substr($_POST['fechafin'], 6, 4);

	# Reemplazamos los retornos de carro y los saltos de linea.
	$descripcion = str_replace("\r\n", "</br>", $_POST['descripcion']);

	# Fechas introducidas son VALIDAS.
	if (checkdate($mesfechainicial, $diafechainicial, $aniofechainicial) && checkdate($mesfechafinal, $diafechafinal, $aniofechafinal))
	{	
		# Insertar datos en tabla competiciones
		if ($c->query("INSERT INTO ".$_SESSION['competiciones']." (nom_competicion, num_pruebas, fecha_inicio, fecha_fin, descripcion) 
			VALUES ('".$_POST['nombrecompeticion']."', '".$_POST['numpruebas']."', '".$_POST['fechainicio']."', '".$_POST['fechafin']."', '".$descripcion."')"))
		{
			$_SESSION['OKcompeticion'] = 1;
		}
		else
		{
			$_SESSION['OKcompeticion'] = 0;
			$_SESSION['BBDDError'] = "Error al insertar datos de competición: (" . $c->errno . ") " . $c->error;
		}	
	}
	else{	$_SESSION['OKcompeticion'] = 2;	}
	
	# Cerrar conexión
	$c->close();

	header('Location: crearcompeticion.html');
?>