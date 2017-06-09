$(document).ready(function(){
	$("#clave").select();

	$("#regfinal").submit(function(){
		// Eliminamos mensajes de alerta (Evitar adicción de más de uno).
		$(".alert-warning").remove();
		// Requisitos minimos de la contraseña.
		$("#condiciones").remove();

		var formulario = this;

		var clave = formulario.clave.value;
		var clave2 = formulario.clave2.value;

		if (clave == "" && clave2 == "")
		{
			formulario.clave.select();
			$(".alert-danger").after("<div class=\"alert alert-warning\">" +
						   	   "<strong>Editar Perfil -</strong> Introduzca algún dato a modificar" +
						   	   "</div>");
			return false;
		}

		if (clave == "" && clave2 != "")
		{
			formulario.clave2.select();
			$("#clave2").after("<div class=\"alert alert-warning\">" +
						   	   "<strong>Contraseña -</strong> Falta contraseña" +
						   	   "</div>");
			return false;
		}

		if (clave != "")
		{
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
		}
	});
});