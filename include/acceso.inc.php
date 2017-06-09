<?php
	header('Content-Type: text/html; charset=utf-8');

	include_once ("inicia_ses.inc.php");

	# Comprobación de que los datos de acceso se encuentran en la BBDD.
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

	# Comprobación que el nombre de usuario sea único, si existe previamente -> Error.
	function validar_nombreusuario() {
		if (isset($_SESSION['ExisteNomUsuario']))
		{
			echo "<script type=\"text/JavaScript\">"; ?>
				$(document).ready(function(){
					$("#nombreusuario").val("<?php echo $_SESSION['ExisteNombreUsuario'] ?>");
					$("#nombreusuario").select();
					$("#nombreusuario").after(	"<div class=\"alert alert-warning\">" +
						"<strong>Nombre Usuario: <?php echo $_SESSION['ExisteNombreUsuario'] ?> -</strong> YA se encuentra registrado en el sistema. Pruebe otro nombre distinto." +
						"</div>");
				});
			<?php echo "</script>";
			unset($_SESSION['ExisteNombreUsuario']);
			unset($_SESSION['ExisteNomUsuario']);
		}
	}

	# Comprobación que el nombre de competición sea único, si existe previamente -> Error.
	function validar_nombrecompeticion() {
		if (isset($_SESSION['NombreCompeticion'])) {	
			echo "<script type=\"text/JavaScript\">"; ?>
			$(document).ready(function(){
				$("#nombrecompeticion").val("<?php echo $_SESSION['NombreCompeticion'] ?>");
				$("#nombrecompeticion").select();
				$("#nombrecompeticion").after(	"<div class=\"alert alert-warning\">" +
					"<strong>Nombre Competicion: <?php echo $_SESSION['NombreCompeticion'] ?> -</strong> YA se encuentra registrado en el sistema. Pruebe otro nombre distinto." +
					"</div>");
				$("#competiciones").val("<?php echo $_SESSION['NombreCompeticion'] ?>");
				$("#modificarCompeticion").collapse('show');
				$("#modificarCompeticion").on('hidden.bs.collapse', function(){
					$(".alert-warning").remove();
				});
			});
			<?php echo "</script>";
		}
		unset($_SESSION['NombreCompeticion']);
	}

	# Estado del registro.
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

	# Estado relativo al registro de una nueva competición.
	function estado_nuevacompeticion() {
		if (isset($_SESSION['OKcompeticion']))
		{
			if ($_SESSION['OKcompeticion'] == 0) {	
				echo 	"<div class=\"mensajes alert alert-danger alert-dismissible\" role=\"alert\">
 					 		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
 					 		<span aria-hidden=\"true\">&times;</span></button>
  								<strong>Crear Competición -</strong> ".$_SESSION['BBDDError']."
						</div>";			
			}
			if ($_SESSION['OKcompeticion'] == 1) {	
				echo 	"<div class=\"mensajes alert alert-success alert-dismissible\" role=\"alert\">
 				 			<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
 				 			<span aria-hidden=\"true\">&times;</span></button>
  								<strong>Crear Competición -</strong> Realizado con éxito.
						</div>";
			}
			if ($_SESSION['OKcompeticion'] == 2) {	
				echo 	"<div class=\"mensajes alert alert-danger alert-dismissible\" role=\"alert\">
 				 			<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
 				 			<span aria-hidden=\"true\">&times;</span></button>
  								<strong>Crear Competición -</strong> La Fecha introducida no es válida (NO existe).
						</div>";
			}
			unset($_SESSION['OKcompeticion']);
		}
	}

	# Eliminar competición
	function estado_eliminarcompeticion() {
		if (isset($_SESSION['OKeliminarcompeticion']))
		{
			if ($_SESSION['OKeliminarcompeticion'] == 0) {
				echo 	"<div class=\"mensajes alert alert-danger alert-dismissible\" role=\"alert\">
 					 		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
 					 		<span aria-hidden=\"true\">&times;</span></button>
  								<strong>Administrar Competición -</strong> ".$_SESSION['BBDDError']."
						</div>";
			}
			if ($_SESSION['OKeliminarcompeticion'] == 1) {
				echo 	"<div class=\"mensajes alert alert-success alert-dismissible\" role=\"alert\">
 					 		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
 					 		<span aria-hidden=\"true\">&times;</span></button>
  								<strong>Administrar Competición -</strong> Competición eliminada con éxito.
						</div>";
			}
			unset($_SESSION['OKeliminarcompeticion']);
		}
	}

		# Editar usuario
	function estado_editarcompeticion() {
		if (isset($_SESSION['OKeditarcompeticion']))
		{
			if ($_SESSION['OKeditarcompeticion'] == 0) {
				echo 	"<div class=\"mensajes alert alert-danger alert-dismissible\" role=\"alert\">
 					 		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
 					 		<span aria-hidden=\"true\">&times;</span></button>
  								<strong>Administrar Competición -</strong> ".$_SESSION['BBDDError']."
						</div>";
			}
			if ($_SESSION['OKeditarcompeticion'] == 1) {
				echo 	"<div class=\"mensajes alert alert-success alert-dismissible\" role=\"alert\">
 					 		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
 					 		<span aria-hidden=\"true\">&times;</span></button>
  								<strong>Administrar Competición -</strong> Campo/s modificados con éxito.
						</div>";
			}
			if ($_SESSION['OKeditarcompeticion'] == 2) {	
				echo 	"<div class=\"mensajes alert alert-danger alert-dismissible\" role=\"alert\">
 				 			<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
 				 			<span aria-hidden=\"true\">&times;</span></button>
  								<strong>Administrar Competición -</strong> La Fecha introducida no es válida (NO existe).
						</div>";
			}
			unset($_SESSION['OKeditarcompeticion']);
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
				# Generamos la información para el administrador del sistema.
				if ($_SESSION['NombreUsuario'] == "administrador")
				{
					echo "<script type=\"text/JavaScript\">"; ?>
					$(document).ready(function(){
				    	$(".navbar-text").before("<ul class=\"nav navbar-nav\">" +
 						"<li><a href=\"/WEBots/html/crearcompeticion.html\">Crear Competición</a></li>" +
  						"<li><a href=\"/WEBots/html/datoscompeticiones.php\">Administrar Competición</a></li>" +
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
  							"<li><p id=\"usuario\"><em>Nombre Usuario: </em> <?php echo $_SESSION['NombreUsuario'] ?></p></li>" +
  							"<li role=\"separator\" class=\"divider\"></li>" +
    						"<li><a id=\"editarusuario\" href=\"/WEBots/html/cambiar_clave.html\">" +
    						"<span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Cambiar Contraseña" +
    						"</a></li>" +
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
				else {
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
	  							"<li><p id=\"usuario\"><em>Nombre Usuario: </em> <?php echo $_SESSION['NombreUsuario'] ?></p></li>" +
	  							"<li role=\"separator\" class=\"divider\"></li>" +
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
					var fecha_inicio = [];
					var fecha_fin = [];
					var descripcion_competicion = [];
					<?php 	for ($i = 0; $i < $_SESSION['NumCompeticiones']; $i++)
							{	
								echo "fecha_inicio[".$i."] = \"".$_SESSION['fecha_inicio'][$i]."\";";
								echo "fecha_fin[".$i."] = \"".$_SESSION['fecha_fin'][$i]."\";";
								echo "descripcion_competicion[".$i."] = \"".$_SESSION['descripcion_competicion'][$i]."\";";	
							}
					?>

					$(".mostrar_competiciones").append("<div class=\"row\">");
					for (i = 0; i < <?php echo $_SESSION['NumCompeticiones']; ?>; i++) { 
			    		$(".mostrar_competiciones").append(
			    		"<div id=\"Bloque" + nom_competicion[i] + "\" class=\"col-sm-12 col-md-6 col-lg-6\">" +
							"<div class=\"thumbnail\">" +
								"<div class=\"caption\">" +
									"<h4 class=\"centrar-texto\">" + nom_competicion[i] + "  Pruebas: " + num_pruebas[i] + "</h4>" +
									"<p>" + descripcion_competicion[i] + "</p>" +
									"<div class=\"centrar-texto\">Comienza: " + fecha_inicio[i] + " Finaliza: " + fecha_fin[i] + "</div>" +
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

					// Obtenemos fecha para comprobar competiciones disponibles.

					var fechaactual = new Date();
					if (fechaactual.getDate() < 10){ dia = "0" + fechaactual.getDate();	}
					if ((fechaactual.getMonth()+1) < 10){ mes = "0" + (fechaactual.getMonth()+1); }
					anio = fechaactual.getFullYear();

					for (i = 0; i < <?php echo $_SESSION['NumCompeticiones']; ?>; i++) { 
			    		// Obtenemos datos de cada fecha introducida para realizar comprobaciones.
						var diafechainicio = fecha_inicio[i].substring(0,2);
						var diafechafin = fecha_fin[i].substring(0,2);
						var mesfechainicio = fecha_inicio[i].substring(3,5);
						var mesfechafin = fecha_fin[i].substring(3,5);
						var aniofechainicio = fecha_inicio[i].substring(6);
						var aniofechafin = fecha_fin[i].substring(6);
						
						// Competición no ha comenzado todavía.
						if ((aniofechainicio > anio) || (aniofechainicio == anio && mesfechainicio > mes) || (aniofechainicio == anio && mesfechainicio == mes && diafechainicio > dia))
						{							
							$("#" + nom_competicion[i] + "btn-participar").attr("disabled", "true");
							$("." + nom_competicion[i] + "").before("<h3 class=\"centrar-texto\">COMPETICIÓN NO DISPONIBLE</h3>");
							$("." + nom_competicion[i] + "").remove();
						}
						// Competición FINALIZADA.
						if ((aniofechafin < anio) || (aniofechafin == anio && mesfechafin < mes) || (aniofechafin == anio && mesfechafin == mes && diafechafin < dia))
						{
							$("#Bloque" + nom_competicion[i] + "").remove();
						}
			    	}
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

			# Visualizamos errores si existen.
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

			# Detectamos la versión del navegador usado por el usuario para adaptar el botón "Examinar...".
			# Ancho asignado por defecto para navegador Mozilla Firefox.
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			if(strpos($user_agent, 'Chromium') !== FALSE)
			{	
				# Codigo JS para cambiar el btn-file.
				echo "<script type=\"text/JavaScript\">"; ?>
					$(document).ready(function(){
			    		$(".btn-file").css("width","138px");
	      			});
	      		<?php echo "</script>";
	      	}
			elseif(strpos($user_agent, 'Edge') !== FALSE) //IE Edge
			{	
				# Codigo JS para cambiar el btn-file.
				echo "<script type=\"text/JavaScript\">"; ?>
					$(document).ready(function(){
			    		$(".btn-file").css("width","138px");
	      			});
	      		<?php echo "</script>";
	      	}
			elseif(strpos($user_agent, 'Trident') !== FALSE) //IE 11
			{	
				# Codigo JS para cambiar el btn-file.
				echo "<script type=\"text/JavaScript\">"; ?>
					$(document).ready(function(){
			    		$(".btn-file").css("width","138px");
	      			});
	      		<?php echo "</script>";
	      	}
			elseif(strpos($user_agent, 'MSIE') !== FALSE)
			{	
				# Codigo JS para cambiar el btn-file.
				echo "<script type=\"text/JavaScript\">"; ?>
					$(document).ready(function(){
			    		$(".btn-file").css("width","138px");
	      			});
	      		<?php echo "</script>";
	      	}
			elseif(strpos($user_agent, 'Chrome') !== FALSE)
			{	
				# Codigo JS para cambiar el btn-file.
				echo "<script type=\"text/JavaScript\">"; ?>
					$(document).ready(function(){
			    		$(".btn-file").css("width","138px");
	      			});
	      		<?php echo "</script>";
	      	}
			elseif(strpos($user_agent, 'Safari') !== FALSE)
			{	
				# Codigo JS para cambiar el btn-file.
				echo "<script type=\"text/JavaScript\">"; ?>
					$(document).ready(function(){
			    		$(".btn-file").css("width","138px");
	      			});
	      		<?php echo "</script>";
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
		if ($_SESSION['NumPuntuaciones'] == 0) {
			# Codigo JS para mostrar competiciones.
			echo "<script type=\"text/JavaScript\">"; ?>
				$(document).ready(function(){
			    	$(".mostrar_puntuaciones").append("<h4 class=\"centrar-texto\">NO HAY PUNTUACIONES QUE MOSTRAR</h4>");
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
		if ($_SESSION['NumPuntuacionesUsuario'] == 0) {
			# Codigo JS para mostrar competiciones.
			echo "<script type=\"text/JavaScript\">"; ?>
				$(document).ready(function(){
			    	$(".mostrar_mispuntuaciones").append("<h4 class=\"centrar-texto\">NO HAY PUNTUACIONES QUE MOSTRAR</h4>");
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

	# Muestra los datos de todas las competiciones.
	function datos_competiciones()
	{
		if ($_SESSION['NumCompeticiones'] == 0) {
			# Codigo JS para mostrar competiciones.
			echo "<script type=\"text/JavaScript\">"; ?>
				$(document).ready(function(){
			    	$(".administrar_competicion").append("<h4 class=\"centrar-texto\">NO EXISTEN COMPETICIONES</h4>");
	      		});
	      	<?php echo "</script>";
		}
		else
		{
			# Recorremos array PHP para introducir datos en administrarcompeticion.html con JS.
			echo "<script type=\"text/JavaScript\">"; ?>
				$(document).ready(function(){
					<!-- json_encode(): funcion para convertir array PHP en array JS -->
					var nom_competicion = <?php echo json_encode($_SESSION['nom_competicion']); ?>;
					var num_pruebas = <?php echo json_encode($_SESSION['num_pruebas']); ?>;
					var fecha_inicio = [];
					var fecha_fin = [];
					var descripcion_competicion = [];
					<?php 	for ($i = 0; $i < $_SESSION['NumCompeticiones']; $i++)
							{	
								echo "fecha_inicio[".$i."] = \"".$_SESSION['fecha_inicio'][$i]."\";";
								echo "fecha_fin[".$i."] = \"".$_SESSION['fecha_fin'][$i]."\";";
								echo "descripcion_competicion[".$i."] = \"".$_SESSION['descripcion_competicion'][$i]."\";";	
							}
					?>

					$(".administrar_competicion").prepend(
					"<div class=\"row elegircompeticion\">" +
						"<div class=\"form-group col-xs-offset-1 col-xs-10 col-sm-offset-2 col-sm-8 col-md-offset-2 col-md-8 col-lg-offset-2 col-lg-8\">" +
					    "<label>Competiciones:</label>" +
					    "<select name=\"competiciones\" id=\"competiciones\" class=\"form-control\">"					
					);
					for (i = 0; i < <?php echo $_SESSION['NumCompeticiones']; ?>; i++) { 
			    		$("#competiciones").append(
			    			"<option value=\""+ nom_competicion[i] + "\">" + nom_competicion[i] + "</option>"	
						);
			    	}
			    	$(".elegircompeticion").append(
			    		"</select>" + 
			    			"</div>" +
			    			"<div class=\"col-xs-offset-1 col-xs-10 col-sm-offset-2 col-sm-8 col-md-offset-2 col-md-8 col-lg-offset-2 col-lg-8\">" + 
			    			"<button type=\"button\" id=\"btn-modificar\" class=\"btn btn-warning btn-block\" data-toggle=\"collapse\"" +
			    				"data-target=\"#modificarCompeticion\" aria-expanded=\"false\" aria-controls=\"modificarCompeticion\">Modificar</button>" +
			    			"</div>" +
			    	"</div>"
			   		);

			   		$("#modificarCompeticion").on('show.bs.collapse', function(){
						var pos = nom_competicion.indexOf($("#competiciones").val());
						var descripcion = descripcion_competicion[pos].replace(/<\/br>/g, "\r\n");

						// Obtenemos datos de cada fecha introducida para realizar comprobaciones.
						var diafechainicio = fecha_inicio[pos].substring(0,2);
						var diafechafin = fecha_fin[pos].substring(0,2);
						var mesfechainicio = fecha_inicio[pos].substring(3,5);
						var mesfechafin = fecha_fin[pos].substring(3,5);
						var aniofechainicio = fecha_inicio[pos].substring(6);
						var aniofechafin = fecha_fin[pos].substring(6);

						$("#nombrecompeticion").val(nom_competicion[pos]);
						$("#numpruebas").val(num_pruebas[pos]);
						$("#fechainicio").val(fecha_inicio[pos]);
						$("#fechafin").val(fecha_fin[pos]);
						$("#descripcion").val(descripcion);
						$("#competiciones").attr("disabled", "true");
						$("#regfinal").prepend("<input type=\"hidden\" name=\"nombrecompeticioninicial\" value=\""+ nom_competicion[pos] + "\" />");

						// Competición EMPEZADA.
						if ((aniofechainicio < anio) || (aniofechainicio == anio && mesfechainicio < mes) || (aniofechainicio == anio && mesfechainicio == mes && diafechainicio <= dia))
						{							
							$(".alert-info").after(
							"<div class=\"alert alert-warning\">" +
								"<p><strong>" + nom_competicion[pos] + " -</strong> Competición COMENZADA, sólo se permite modificar Fecha Fin y Descripción.</p>" +
							"</div>");
							$("#nombrecompeticion").attr("disabled", "true");
							$("#numpruebas").attr("disabled", "true");
							$("#fechainicio").attr("disabled", "true");
							$(":reset").remove();
						}
						// Competición FINALIZADA.
						if ((aniofechafin < anio) || (aniofechafin == anio && mesfechafin < mes) || (aniofechafin == anio && mesfechafin == mes && diafechafin < dia))
						{
							$(".alert-warning").remove();
							$(".alert-info").after(
							"<div class=\"alert alert-warning\">" +
								"<p><strong>" + nom_competicion[pos] + " -</strong> Competición FINALIZADA, NO MODIFICABLE.</p>" +
							"</div>");
							$("#nombrecompeticion").attr("disabled", "true");
							$("#numpruebas").attr("disabled", "true");
							$("#fechainicio").attr("disabled", "true");
							$("#fechafin").attr("disabled", "true");
							$("#descripcion").attr("disabled", "true");
							$(".btn-guardar").attr("disabled", "true");
							$(":reset").remove();
						}
					});
					$("#modificarCompeticion").on('hidden.bs.collapse', function(){
						$("#competiciones").removeAttr("disabled");
						$(".alert-warning").remove();
					});
	      		});
	      	<?php echo "</script>";
		}
	}
?>