<?php
	header('Content-Type: text/html; charset=utf-8');

	require ("../include/inicia_ses.inc.php");
	require '../PHPMailer/PHPMailerAutoload.php';
	include_once ("../include/datos.inc.php"); 		# Incluimos datos básicos de la BBDD.

	if (isset($_POST['correo'])){	$_SESSION['correo'] = $_POST['correo'];	}

	// Crear conexión
	$c = new mysqli($_SESSION['servidor'], $_SESSION['login'], $_SESSION['pass'], $_SESSION['BBDD']);
	$c->set_charset('utf8');
	// Comprobar conexión
	if ($c->connect_error){
		$_SESSION['OKcorreo'] = 0;
		$_SESSION['MailerError'] = "Conexión fallida: " . $c->connect_error;
		die();
	}

	# Comprobamos que el correo se encuentra en la BBDD.
	$existecorreo = $c->query("SELECT clave FROM ".$_SESSION['usuarios']."
			WHERE (email='".$_SESSION['correo']."')");

	# Correo se encuentra registrado en la BBDD (existe una fila como resultado de la consulta).
	if ($existecorreo->num_rows == 1)
	{		
		# Generar clave aleatoria (10 caracteres, MAY-min-num) - 62 elementos.
		$cad = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
		$may = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$min = "abcdefghijklmnopqrstuvwxyz";
		$num = "1234567890";
		$clave = "";
		$clave .= substr($may, rand(0, 25), 1);
		$clave .= substr($min, rand(0, 25), 1);
		$clave .= substr($num, rand(0, 9), 1);
		for ($i=0; $i<6; $i++) {	$clave .= substr($cad, rand(0, 61), 1);		}
		# Fin generación clave.

		# Envío de correo con información necesaria para restablecer clave.
		$mail = new PHPMailer;
		# Cargar version española para errores
		$mail->setLanguage('es', '../PHPMailer/language/');
		# Necesario para que aparezcan caracteres latinos y acentos. (No funciona)
		# $mail­->CharSet = 'utf-8';
		# $mail­->Encoding = 'quoted­-printable';

		//$mail->SMTPDebug = 2;                             // Enable verbose debug output

		$mail->isSMTP();                                    // Set mailer to use SMTP
		$mail->Host = 'smtp.live.com';  					// Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                             // Enable SMTP authentication
		$mail->Username = 'rodriguez_1_23@hotmail.com';     // SMTP username
		$mail->Password = 'R0dr1gu3z19_9';                  // SMTP password
		$mail->SMTPSecure = 'tls';                          // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                  // TCP port to connect to

		$mail->setFrom('info@webots.com', 'WeBots');
		$mail->addAddress($_SESSION['correo'], 'Usuario: ');
		// $mail->addCC('cc@example.com');
		// $mail->addBCC('bcc@example.com');

		$mail->isHTML(true);                                  // Set email format to HTML
		# Incluir imagen del logo para titulo del mensaje.
		$mail->AddEmbeddedImage('../images/logonegro.png', 'logoWB', 'logonegro.png');

		$mail->Subject 	= 'Restablece tu clave de WeBots';
		$mail->msgHTML('<div style="background-color:#f9f9f9">
	<table style="width: 600px; margin: auto; padding: 10px 110px; background-color:#ccffcc; 
			border-left: 15px solid #003300;">	
		<tr>
     		<td><div style="width: 140px; margin: auto;"><img src="cid:logoWB" alt="logoWeBots"/></div></td>
  		</tr>
		<tr>
     		<td style="padding-bottom: 10px;">Solicitud para restablecer clave recibida correctamente.</td>
  		</tr>
  		<tr>
  			<td>Hemos procedido a cambiar su clave por la que se visualiza en la siguiente linea.</td>
  		</tr>
  		<tr>
  			<td style="text-align: center; padding-bottom: 10px;">Clave: <b>'.$clave.'</b></td>
  		</tr>
  		<tr>
  			<td style="padding-bottom: 5px;">
  				Use la clave proporcionada en el siguiente acceso.<br>
  				Le recomendamos que modifique su clave una vez que haya accedido
     			al sistema por temas de seguridad. <br>
     		</td>
  		</tr>
  		<tr style="font-size: 12px; text-align: center;"">
  			<td>Correo generado por el sistema. No responda a este e-mail.</td>
  		</tr>
	</table>
</div>');
		/*	msgHTML() genera automaticamente el texto alternativo. 
		$mail->AltBody 	= 'Petición para restablecer contraseña recibida con éxito.
						Hemos procedido a cambiar su contraseña por la que se visualiza a continuación.
						Contraseña: '.$clave.'.
						Deberá usar esta nueva contraseña en el siguiente acceso.'; */

		if(!$mail->send()) {
		    $_SESSION['OKcorreo'] = 0;
		    $_SESSION['MailerError'] = $mail->ErrorInfo;
		} else {
		    $_SESSION['OKcorreo'] = 1;

		    # Modificar clave dentro de la tabla Usuario.
			$c->query("UPDATE ".$_SESSION['usuarios']." 
			SET clave='".password_hash($clave, PASSWORD_DEFAULT)."' WHERE (email='".$_SESSION['correo']."')");
		}
	}
	else
	{	$_SESSION['OKcorreo'] = 2;	$_SESSION['MailerError'] = "No se encuentra registrado en el sistema";	}
	
	# Cerrar conexión
	$c->close();
	
	header('Location: olvidoclave.html');
?>

