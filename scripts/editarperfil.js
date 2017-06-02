$(document).ready(function(){
	$("#nombreusuario").select();

	$("#eliminarusuario").click(function(){
		var res = confirm("¿Seguro que quieres eliminar el usuario?");
		if (res == true)
		{	location.href = "../html/eliminar_usuario.php";		}
	});

	$("#regfinal").submit(function(){
		// Eliminamos mensajes de alerta (Evitar adicción de más de uno).
		$(".alert-warning").remove();
		// Requisitos minimos de la contraseña.
		$("#condiciones").remove();

		var formulario = this;

		var nombreusuario = formulario.nombreusuario.value;
		var nombre = formulario.nombre.value;
		var apellido = formulario.ap.value;
		var clave = formulario.clave.value;
		var clave2 = formulario.clave2.value;

		if (nombreusuario == "" && nombre == "" && apellido == "" && clave == "" && clave2 == "")
		{
			formulario.nombre.select();
			$(".alert-danger").after("<div class=\"alert alert-warning\">" +
						   	   "<strong>Editar Perfil -</strong> Introduzca algún dato a modificar" +
						   	   "</div>");
			return false;
		}

		if(nombreusuario.length >= 15)
		{
			formulario.nombreusuario.select();
			$("#nombreusuario").after("<div class=\"alert alert-warning\">" +
							  "<strong>Nombre Usuario -</strong> Contiene mas de 15 caracteres" +
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