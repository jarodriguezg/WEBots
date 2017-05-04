<?php
	include_once ("inicia_ses.inc.php");

	function validar_datosacceso() {
		if (isset($_SESSION['OKdatos']))
		{
			echo "<script type=\"text/JavaScript\">"; ?>
				$(document).ready(function(){
					$("#VModal").removeClass("fade");
				   	$("#VModal").modal('show'); <?php
			if ($_SESSION['OKdatos'] == 1){		?>
				  	formulario();
				  	$("#correo").val("<?php echo $_SESSION['correo'] ?>");
				   	$("#correo").select();
					$("#correo").after(	"<div class=\"alert alert-warning\">" +
				   		"<strong>Correo -</strong> La dirección YA se encuentra registrada en el sistema" +
						"</div>");			<?php
			}
			if ($_SESSION['OKdatos'] == 2){	?>
				   	ingreso();
				   	$("#reginicial").prepend("<div class=\"alert alert-warning\">" +
							"<strong>Usuario incorrecto -</strong> Email o contraseña no válidos" +
							"</div>");
					$("#correo").val("<?php echo $_SESSION['correo'] ?>");
					$("#clave").val("<?php echo $_SESSION['clave'] ?>");
					$("#clave").select();
				<?php
			}	?>
				});
				<?php echo "</script>";
			unset($_SESSION['OKdatos']);
		}
	}

	function estado_registro() {
		if (isset($_SESSION['OKregistro']))
		{
			if ($_SESSION['OKregistro'] == 0) {	
				echo 	"<div class=\"mensajes alert alert-danger alert-dismissible\" role=\"alert\">
 					 		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
 					 		<span aria-hidden=\"true\">&times;</span></button>
  								<strong>Registro Usuario -</strong> ".$_SESSION['BBDDError']."
						</div>";			
			}
			if ($_SESSION['OKregistro'] == 1) {	
				echo 	"<div class=\"mensajes alert alert-success alert-dismissible\" role=\"alert\">
 				 			<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
 				 			<span aria-hidden=\"true\">&times;</span></button>
  								<strong>Registro Usuario -</strong> Registro realizado con éxito.
						</div>";
			}
			unset($_SESSION['OKregistro']);
		}
	}

	# Editar usuario
	function estado_editar() {
		if (isset($_SESSION['OKeditar']))
		{
			if ($_SESSION['OKeditar'] == 0) {
				echo 	"<div class=\"mensajes alert alert-danger alert-dismissible\" role=\"alert\">
 					 		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
 					 		<span aria-hidden=\"true\">&times;</span></button>
  								<strong>Editar Usuario -</strong> ".$_SESSION['BBDDError']."
						</div>";
			}
			if ($_SESSION['OKeditar'] == 1) {
				echo 	"<div class=\"mensajes alert alert-success alert-dismissible\" role=\"alert\">
 					 		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
 					 		<span aria-hidden=\"true\">&times;</span></button>
  								<strong>Editar Usuario -</strong> Campo/s modificados con éxito.
						</div>";
			}
			unset($_SESSION['OKeditar']);
		}
	}

	function estado_restablecerclave() {
		if (isset($_SESSION['OKcorreo']))
		{	
			if ($_SESSION['OKcorreo'] == 2) {	
				echo 	"<div class=\"alert alert-danger\" role=\"alert\">
	  						<strong>Correo: ".$_SESSION['correo']." -</strong> Error (".$_SESSION['MailerError'].") al enviar intrucciones. 
						</div>";		
			}
			else
			{
				# Codigo JS para dejar unicamente el mensaje de éxito o error.
				echo "<script type=\"text/JavaScript\">"; ?>
						$(document).ready(function(){
					    	$(".alert-info").remove();
					    	$(".form-group").remove();
					    	$(".btn").remove();
						});
				<?php echo "</script>";

				if ($_SESSION['OKcorreo'] == 0) {	
					echo 	"<div class=\"alert alert-danger\" role=\"alert\">
		  						<strong>Correo: ".$_SESSION['correo']." -</strong> Error (".$_SESSION['MailerError'].") al enviar intrucciones. 
		  						<a href=\"nueva_clave.php\" class=\"alert-link\">Reintentar envio</a>
							</div>";		
				}
				if ($_SESSION['OKcorreo'] == 1) {
					
					echo 	"<div class=\"alert alert-success\" role=\"alert\">
		  						<strong>Correo: ".$_SESSION['correo']." -</strong> Instrucciones para restablecer contraseña enviadas con éxito.
							</div>";	
					unset($_SESSION['correo']);
				}
			}
			unset($_SESSION['OKcorreo']);
		}
	}

	# Acceso de usuario
	function acceso_usuario() {
		if (isset($_SESSION['OKingreso']))
		{
			if ($_SESSION['OKingreso'] == 0) {
				echo 	"<div class=\"mensajes alert alert-danger alert-dismissible\" role=\"alert\">
 					 		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
 					 		<span aria-hidden=\"true\">&times;</span></button>
  								<strong>Acceso Usuario -</strong> ".$_SESSION['BBDDError']."
						</div>";
				unset($_SESSION['OKingreso']);
			}
			else if ($_SESSION['OKingreso'] == 1) {	
				# Codigo JS para eliminar botón Ingresar y agregar información Usuario.
				echo "<script type=\"text/JavaScript\">"; ?>
					$(document).ready(function(){
				    	$(".navbar-text").before("<ul class=\"nav navbar-nav\">" +
	        			"<li><a href=\"/WEBots/index.html\">Inicio<span class=\"sr-only\"></span></a></li>" +
 						"<li><a href=\"/WEBots/html/competicion.html\">Nueva Competición</a></li>" +
  						"<li><a href=\"#\">Función1</a></li>" +
	      				"</ul>");
	      				$(".navbar-text").remove();

						$("#btnusuario").append("<div id=\"usuarioreg\" class=\"navbar-right\">" +
				    	"<div class=\"dropdown\">" +
  						"<button class=\"btn dropdown-toggle\" type=\"button\" id=\"btnMenuUsuario\"" +
  						"data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"true\">" +
  							"<span class=\"glyphicon glyphicon-user\" aria-hidden=\"true\"></span>" +
  							" <?php echo $_SESSION['DatosUsuario']; ?>" +
  						"</button>" +
  						"<ul class=\"dropdown-menu\" aria-labelledby=\"btnMenuUsuario\">" +
    						"<li><a id=\"editarusuario\" href=\"/WEBots/html/perfil.html\">" +
    						"<span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Editar Usuario" +
    						"</a></li>" +
    						"<li><a href=\"#\">Another action</a></li>" +
    						"<li role=\"separator\" class=\"divider\"></li>" +
    						"<li><a href=\"/WEBots/html/procesa_cierre.php\">" +
    						"<span class=\"glyphicon glyphicon-off\" aria-hidden=\"true\"></span> Cerrar Sesión" +
    						"</a></li>" +
  						"</ul>" +
						"</div>");
						$("#btnregistro").remove();

						$("#MenuUsuario").css("width", $(".dropdown").css("width"));
					});
				<?php echo "</script>";
			}
		}
	}
?>