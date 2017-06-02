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

	function validar_nombreusuario() {
		if (isset($_SESSION['ExisteNomUsuario']))
		{
			echo "<script type=\"text/JavaScript\">"; ?>
				$(document).ready(function(){
					$("#nombreusuario").val("<?php echo $_SESSION['NombreUsuario'] ?>");
					$("#nombreusuario").select();
					$("#nombreusuario").after(	"<div class=\"alert alert-warning\">" +
						"<strong>Nombre Usuario: <?php echo $_SESSION['NombreUsuario'] ?> -</strong> YA se encuentra registrado en el sistema. Pruebe otro nombre distinto." +
						"</div>");
				});
			<?php echo "</script>";
			unset($_SESSION['ExisteNomUsuario']);
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
 						"<li><a href=\"/WEBots/html/mostrarcompeticiones.php\">Competiciones</a></li>" +
  						"<li><a href=\"/WEBots/html/mostrarpuntuaciones.php\">Puntuaciones</a></li>" +
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
    						"<li><a href=\"/WEBots/html/mostrarmiscompeticiones.php\">" +
    						"<span class=\"glyphicon glyphicon-tower\" aria-hidden=\"true\"></span> Mis Competiciones" +
    						"</a></li>" +
    						"<li><a href=\"/WEBots/html/mostrarmispuntuaciones.php\">" +
    						"<span class=\"glyphicon glyphicon-sort-by-order\" aria-hidden=\"true\"></span> Mis Puntuaciones" +
    						"</a></li>" +
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

	# Muestra todas las competiciones.
	function mostrar_competiciones()
	{
		if ($_SESSION['NumCompeticiones'] == 0) {
			# Codigo JS para mostrar competiciones.
			echo "<script type=\"text/JavaScript\">"; ?>
				$(document).ready(function(){
			    	$(".mostrar_competiciones").append("<h4 class=\"centrar-texto\">NO EXISTEN COMPETICIONES</h4>");
	      		});
	      	<?php echo "</script>";
		}
		else
		{
			# Recorremos array PHP para introducir datos en competicion.html con JS.
			echo "<script type=\"text/JavaScript\">"; ?>
				$(document).ready(function(){
					<!-- json_encode(): funcion para convertir array PHP en array JS -->
					var nom_competicion = <?php echo json_encode($_SESSION['nom_competicion']); ?>;
					var num_pruebas = <?php echo json_encode($_SESSION['num_pruebas']); ?>;

					$(".mostrar_competiciones").append("<div class=\"row\">");
					for (i = 0; i < <?php echo $_SESSION['NumCompeticiones']; ?>; i++) { 
			    		$(".mostrar_competiciones").append(
			    		"<div class=\"col-sm-12 col-md-6 col-lg-6\">" +
							"<div class=\"thumbnail\">" +
								"<div class=\"caption\">" +
									"<h4 class=\"centrar-texto\">" + nom_competicion[i] + "  Pruebas: " + num_pruebas[i] + "</h4>" +
									"<p>Descripción competición</p>" +
									"<form class=\"form-inline subirfichero\" enctype=\"multipart/form-data\" action=\"subirfichero.php\" method=\"POST\">" +
  										"<div class=\"form-group " + nom_competicion[i] + "\">" +
    										"<label>Envío archivos</label>" +
    										<!-- Nombre competición para crear carpeta -->
    										"<input type=\"hidden\" name=\"competicion\" value=\""+ nom_competicion[i] + "\" />" +
    										<!-- Número de pruebas para crear una carpeta por Prueba -->
    										"<input type=\"hidden\" name=\"numpruebas\" value=\""+ num_pruebas[i] + "\" />" +
    										<!-- MAX_FILE_SIZE debe preceder al campo de entrada del fichero -->
    										"<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"30000\" />");
									for (j = 0; j < num_pruebas[i]; j++) { 
			    					$("." + nom_competicion[i] + "").append(
    										<!-- El nombre del elemento de entrada determina el nombre en el array $_FILES[] -->
    										"<div class=\"subidafichero\">" +
    										"<label for=\"" + nom_competicion[i] + "prueba" + (j+1) + "\" class=\"btn btn-file btn-warning\">Examinar</label>" +
    										"<input id=\"" + nom_competicion[i] + "prueba" + (j+1) + "\" class=\"ficheros\" name=\"fichero_usuario[]\" type=\"file\"/>" + 
    										"</div>");
    								}
  										$("." + nom_competicion[i] + "").after(
  											"</div>" +
  										"<input id=\"" + nom_competicion[i] + "btn-participar\" class=\"btn btn-primary btn-lg btn-block\" type=\"submit\" value=\"¡Participar!\" />" +
									"</form>" +
								"</div>" +
							"</div>" +
						"</div>"	
						);
			    	}
			    	$(".mostrar_competiciones").append("</div>");
	      		});
	      	<?php echo "</script>";

	      	# Deshabilitar botón participar de las competiciones en las que el usuario ya ha participado.
	      	for($i = 0; $i < count($_SESSION['competicionrealizada']); $i++) 
			{
	      		echo "<script type=\"text/JavaScript\">"; ?>
					$(document).ready(function(){
						$("#<?php echo $_SESSION['competicionrealizada'][$i]; ?>btn-participar").attr("disabled", "true");
						$(".<?php echo $_SESSION['competicionrealizada'][$i]; ?>").before("<h3 class=\"centrar-texto\">YA HAS PARTICIPADO EN ESTA COMPETICIÓN</h3>");
						$(".<?php echo $_SESSION['competicionrealizada'][$i]; ?>").remove();
					});
	      		<?php echo "</script>";
			}

			if (isset($_SESSION['ErrorFichero'])) {
				echo 	"<div class=\"mensajes alert alert-danger alert-dismissible\" role=\"alert\">
 				 			<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
 				 			<span aria-hidden=\"true\">&times;</span></button>";
 				 			for ($prueba = 0; $prueba < $_SESSION['PruebasCompeticion']; $prueba++)
 				 			{	
 				 				if(isset($_SESSION['ErrorFichero'][$prueba]))
 				 				{	echo "<div><strong>Subir Ficheros Prueba".($prueba + 1)." -</strong> ".$_SESSION['ErrorFichero'][$prueba]."</div>";	}
 				 			}
  				echo  	"</div>";
				unset($_SESSION['ErrorFichero']);
				unset($_SESSION['PruebasCompeticion']);
			}
		}
	}

	# Muestra los datos de las competiciones en las que ha participado el usuario.
	function mostrar_miscompeticiones()
	{
		if ($_SESSION['NumCompeticionesUsuario'] == 0) {
			# Codigo JS para mostrar competiciones.
			echo "<script type=\"text/JavaScript\">"; ?>
				$(document).ready(function(){
			    	$(".mostrar_miscompeticiones").append("<h4 class=\"centrar-texto\">NO EXISTEN COMPETICIONES</h4>");
	      		});
	      	<?php echo "</script>";
		}
		else
		{
			# Recorremos array PHP para introducir datos en puntuaciones.html con JS.
			echo "<script type=\"text/JavaScript\">"; ?>
				$(document).ready(function(){
					<!-- json_encode(): funcion para convertir array PHP en array JS -->
					var CompeticionUsuario = <?php echo json_encode($_SESSION['competicion']); ?>;
					var PuntuacionUsuario = <?php echo json_encode($_SESSION['puntuacioncomp']); ?>;

					$(".mostrar_miscompeticiones").append("<div class=\"row\">" +
						"<div class=\"col-xs-12 col-sm-12 col-md-12 col-lg-offset-3 col-lg-6\">" +
							"<table id=\"MisCompeticiones\" class=\"table\">" +
							"<caption><h4>Mis Competiciones</h4></caption>" +
		  						"<tr id=\"filacabecera\">" +
		    						"<th>Nombre Competicion</th>" +
		    						"<th>Puntuación</th>" +
		  						"</tr>");
		  				for (i = 0; i < <?php echo $_SESSION['NumCompeticionesUsuario']; ?>; i++) {
		  					$("#MisCompeticiones").append(
					 			"<tr>" +
		    						"<td>" + CompeticionUsuario[i] + "</td>" +
		    						"<td>" + PuntuacionUsuario[i] + "</td>" +
		  						"</tr>" +
		  					"</table>");
					}
			    $(".mostrar_miscompeticiones").append("</div>");
	      	});
	      	<?php echo "</script>";
		}
	}


	# Muestra todas las puntuaciones de cada competición.
	function mostrar_puntuaciones()
	{
		if ($_SESSION['NumCompeticiones'] == 0) {
			# Codigo JS para mostrar competiciones.
			echo "<script type=\"text/JavaScript\">"; ?>
				$(document).ready(function(){
			    	$(".mostrar_puntuaciones").append("<h4 class=\"centrar-texto\">NO EXISTEN COMPETICIONES</h4>");
	      		});
	      	<?php echo "</script>";
		}
		else
		{
			# Recorremos array PHP para introducir datos en puntuaciones.html con JS.
			echo "<script type=\"text/JavaScript\">"; ?>
				$(document).ready(function(){
					<!-- json_encode(): funcion para convertir array PHP en array JS -->
					var DatosCompeticion = <?php echo json_encode($_SESSION['DatosCompeticion']); ?>;

					$(".mostrar_puntuaciones").append("<div class=\"row\">");
					for (i = 0; i < <?php echo $_SESSION['NumCompeticiones']; ?>; i++) { 			    		
						if (DatosCompeticion[i][1] != 0)
						{
							$(".mostrar_puntuaciones").append(
							"<div class=\"col-xs-12 col-sm-12 col-md-12 col-lg-6\">" +
								"<table id=\"Puntuaciones" + DatosCompeticion[i][0] + "\" class=\"table\">" +
								"<caption><h4>" + DatosCompeticion[i][0] + "</h4></caption>" +
		  							"<tr id=\"filacabecera\">" +
		    							"<th>#</th>" +
		    							"<th>Usuario</th>" +
		    							"<th>Puntuación</th>" +
		  							"</tr>");
							for (j = 0; j < DatosCompeticion[i][1]; j++)
							{
								if (DatosCompeticion[i][2][j] == "<?php echo $_SESSION['NombreUsuario']; ?>")
								{
									$("#Puntuaciones" + DatosCompeticion[i][0] + "").append(
					 					"<tr id=\"puntuacionusuario\">" +
											"<td>" + (j + 1) + "</td>" +
		    								"<td>" + DatosCompeticion[i][2][j] + "</td>" +
		    								"<td>" + DatosCompeticion[i][3][j] + "</td>" +
		  								"</tr>");
		  						}
		  						else
		  						{
		  							$("#Puntuaciones" + DatosCompeticion[i][0] + "").append(
					 					"<tr>" +
											"<td>" + (j + 1) + "</td>" +
		    								"<td>" + DatosCompeticion[i][2][j] + "</td>" +
		    								"<td>" + DatosCompeticion[i][3][j] + "</td>" +
		  								"</tr>");
		  						}
							}
			 				$("#" + DatosCompeticion[i][0] + "").append("</table>");
						}
			    	}
			    	$(".mostrar_puntuaciones").append("</div>");
	      		});
	      	<?php echo "</script>";
		}
	}

	# Muestra las puntuaciones obtenidas en cada una de las pruebas en las que ha participado el usuario.
	function mostrar_mispuntuaciones()
	{
		if ($_SESSION['NumCompeticionesUsuario'] == 0) {
			# Codigo JS para mostrar competiciones.
			echo "<script type=\"text/JavaScript\">"; ?>
				$(document).ready(function(){
			    	$(".mostrar_mispuntuaciones").append("<h4 class=\"centrar-texto\">NO EXISTEN PUNTUACIONES</h4>");
	      		});
	      	<?php echo "</script>";
		}
		else
		{
			# Recorremos array PHP para introducir datos en puntuaciones.html con JS.
			echo "<script type=\"text/JavaScript\">"; ?>
				$(document).ready(function(){
					<!-- json_encode(): funcion para convertir array PHP en array JS -->
					var DatosCompeticion = <?php echo json_encode($_SESSION['DatosCompeticion']); ?>;

					$(".mostrar_mispuntuaciones").append("<div class=\"row\">");
					for (i = 0; i < <?php echo $_SESSION['NumCompeticiones']; ?>; i++) { 			    		
						if (DatosCompeticion[i][1] != 0)
						{
							$(".mostrar_mispuntuaciones").append(
							"<div class=\"col-xs-12 col-sm-12 col-md-12 col-lg-6\">" +
								"<table id=\"MisPuntuaciones" + DatosCompeticion[i][0] + "\" class=\"table\">" +
								"<caption><h4>" + DatosCompeticion[i][0] + "</h4></caption>" +
		  							"<tr id=\"filacabecera\">" +
		    							"<th>Prueba</th>" +
		    							"<th>Fichero</th>" +
		    							"<th>Puntuación</th>" +
		  							"</tr>");
							for (j = 0; j < DatosCompeticion[i][1]; j++)
							{
								$("#MisPuntuaciones" + DatosCompeticion[i][0] + "").append(
					 				"<tr>" +
										"<td>" + DatosCompeticion[i][2][j] + "</td>" +
		    							"<td>" + DatosCompeticion[i][3][j] + "</td>" +
		    							"<td>" + DatosCompeticion[i][4][j] + "</td>" +
		  							"</tr>");
							}
			 				$("#" + DatosCompeticion[i][0] + "").append("</table>");
						}
			    	}
			    	$(".mostrar_mispuntuaciones").append("</div>");
	      		});
	      	<?php echo "</script>";
		}
	}
?>