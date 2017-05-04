function comprobar_datos()
{
	$("#reginicial").submit(function(){
		// Evitar redundancia mensajes.
		$(".alert-warning").remove();
		var correo = $("#correo").val();
		var clave = $("#clave").val();

		if(correo == "")
		{
			$("#correo").focus();
			$("#correo").after(	"<div class=\"alert alert-warning\">" +
						   	   	"<strong>Campo Vacío -</strong> Introduzca Email" +
						   		"</div>");
			if(clave == "")
			{
				$("#clave").after(	"<div class=\"alert alert-warning\">" +
						   	   		"<strong>Campo Vacío -</strong> Introduzca Contraseña" +
						   			"</div>");
			}
			return false;
		}
		else
		{
			// Comprobamos con expresiones regulares que el correo introducido cumple el formato exigido.
			var expcorreo = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/; 
			if(expcorreo.test(correo) != 1)
			{
				$("#correo").select();
				$("#correo").after(	"<div class=\"alert alert-warning\">" +
							   	   	"<strong>Correo -</strong> Dirección inválida" +
							   		"</div>");
				return false;
			}
			
			if(clave == "")
			{
				$("#clave").focus();
				$("#clave").after(	"<div class=\"alert alert-warning\">" +
							   	   	"<strong>Campo Vacío -</strong> Introduzca Contraseña" +
							   		"</div>");
				return false;
			}
		}
	});
	// Eliminar mensajes de error al cerrar VModal.
	$("#VModal").on('hidden.bs.modal', function(){
		$(".alert-warning").remove();
	})
}

function ingreso()
{
	$(".modal-title").text("Accede a WeBots");
	$(".modal-body").html(
		"<form id=\"reginicial\" action=\"include/validardatos.inc.php\" method=\"post\"> \
			<div class=\"form-group\"> \
				<input name=\"correo\" id=\"correo\" type=\"text\" class=\"form-control\" placeholder=\"Email:\"> \
			</div> \
			<div class=\"form-group\"> \
			    <input name=\"clave\" id=\"clave\" type=\"password\" class=\"form-control\" placeholder=\"Contraseña:\"> \
			</div> \
			<button id=\"acceso\" type=\"submit\" class=\"btn btn-success btn-block\">Ingresar</button> \
			<div class=\"centrar-texto\"><a href=\"html/olvidoclave.html\">¿Olvidaste la contraseña?</a></div>\
		</form>");
	$(".modal-footer").html(
		"<h5>¿No tienes cuenta?</h5> \
		 <button type=\"button\" class=\"btn btn-default\" onclick=\"formulario();\">Crear Cuenta</button>"
	);

	comprobar_datos();
}

function formulario()
{
	$(".modal-title").text("Registrate en WeBots");
	$(".modal-body").html(
		"<form id=\"reginicial\" action=\"include/validardatos.inc.php\" method=\"post\"> \
		 	<div class=\"form-group\"> \
				<input name=\"correo\" id=\"correo\" type=\"text\" class=\"form-control\" placeholder=\"Email:\"> \
		 	</div> \
		 	<button type=\"submit\" class=\"btn btn-success btn-block\">Registrate</button> \
		 </form>"
	);

	$(".modal-footer").html(
		"<h5>¿Ya tienes cuenta?</h5> \
		 <button type=\"button\" class=\"btn btn-default\" onclick=\"ingreso();\">Ingresar</button>"
	);

	$("#reginicial").submit(function(){
		// Eliminamos mensajes de alerta (Evitar adicción de más de uno).
		$(".alert-warning").remove();
		var correo = $("#correo").val();

		if(correo == "")
		{
			$("#correo").select();
			$("#correo").after("<div class=\"alert alert-warning\">" +
						   "<strong>Campo Vacío -</strong> Introduzca Dirección correo electrónico" +
						   "</div>");
			return false;
		}
		// Comprobamos con expresiones regulares que el correo introducido cumple el formato exigido.
		var expcorreo = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/; 
		if(expcorreo.test(correo) != 1)
		{
			$("#correo").select();
			$("#correo").after(	"<div class=\"alert alert-warning\">" +
					   	   	"<strong>Dirección correo electrónico -</strong> Dirección inválida" +
					   		"</div>");
			return false;
		}
	});
	// Eliminar mensajes de error al cerrar VModal.
	$("#VModal").on('hidden.bs.modal', function(){
		$(".alert-warning").remove();
	})
}