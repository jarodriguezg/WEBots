function info_errores(campo, selector){
	$(selector).after("<div class=\"alert alert-warning\">" +
						   "<strong>Campo Vacío -</strong> Introduzca " + campo +
						   "</div>");
}

$(document).ready(function(){
	$("#nombre").select();

	$("#regfinal").submit(function(){
		// Eliminamos mensajes de alerta (Evitar adicción de más de uno).
		$(".alert-warning").remove();
		// Requisitos minimos de la contraseña.
		$("#condiciones").remove();

		var formulario = this;

		var nombre = formulario.nombre.value;
		var apellido = formulario.ap.value;
		var clave = formulario.clave.value;
		var clave2 = formulario.clave2.value;

		// Buscar forma de que aparezcan todos los errores tras SUBMIT.
		if(nombre == "")
		{
			formulario.nombre.select();
			info_errores("Nombre", "#nombre");
			return false;
		}

		if(apellido == "")
		{
			formulario.ap.select();
			info_errores("Apellido", "#ap");
			return false;
		}

		if(clave == "")
		{
			formulario.clave.select();
			info_errores("Contraseña", "#clave");
			return false;
		}
		if(clave2 == "")
		{
			formulario.clave2.select();
			info_errores("Contraseña", "#clave2");
			return false;
		}

		if(clave.length < 8)
		{
			formulario.clave.select();
			$("#clave").after("<div class=\"alert alert-warning\">" +
							  "<strong>Contraseña -</strong> Contiene menos de 8 caracteres" +
						   	  "</div>");
			return false;
		}

		if(clave != clave2)
		{
			formulario.clave2.select();
			$("#clave2").after("<div class=\"alert alert-warning\">" +
						   	   "<strong>Contraseña -</strong> No coinciden" +
						   	   "</div>");
			return false;
		}
		else // Contraseña de 8 o más caracteres, coincidentes (clave == clave2).
		{	
			// Expresion regular(al menos una MAY, una min, y un número).
			var expmay = /[A-Z]+/;
			var expmin = /[a-z]+/;
			var expnum = /[0-9]+/;

			// Comprobamos que la clave cumpla precondiciones.
			if ((expmay.test(clave) && expmin.test(clave) && expnum.test(clave)) == 1)
			{ return true; }
			else
			{
				$("#clave").before("<div id=\"condiciones\" class=\"alert alert-danger\">" +
						   	   	   "<strong>Contraseña -</strong> No cumple condiciones" +
						   	   	   "</div>");
				formulario.clave.select();
				return false;
			}
		}
	});
});