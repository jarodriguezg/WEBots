$(document).ready(function(){
	$("#correo").select();

	$("#regfinal").submit(function(){
		// Eliminamos mensajes de alerta (Evitar adicción de más de uno).
		$(".alert-warning").remove();
		$(".alert-danger").remove();
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
					   	   	"<strong>Correo -</strong> Dirección inválida" +
					   		"</div>");
			return false;
		}
	});
});