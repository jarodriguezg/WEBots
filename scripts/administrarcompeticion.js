$(document).ready(function(){
	$("#eliminarcompeticion").click(function(){
		var res = confirm("¿Seguro que quieres eliminar la competición?");
		return res;
	});

	// Obtenemos fecha para colocar fechas por defecto en campos de fecha inicio y fin.
	var fechaactual = new Date();

	if (fechaactual.getDate() < 10){ dia = "0" + fechaactual.getDate();	}
	else { dia = fechaactual.getDate(); }
	if ((fechaactual.getMonth()+1) < 10){ mes = "0" + (fechaactual.getMonth()+1); }
	else { mes = (fechaactual.getMonth()+1); }
	anio = fechaactual.getFullYear();

	$("#regfinal").submit(function(){
		// Eliminamos mensajes de alerta (Evitar adicción de más de uno).
		$(".alert-warning").remove();
		// Requisitos minimos de la contraseña.
		$("#condiciones").remove();

		var formulario = this;

		var nombrecompeticion = formulario.nombrecompeticion.value;
		var numpruebas = formulario.numpruebas.value;
		var descripcion = formulario.descripcion.value;
		var fechainicio = formulario.fechainicio.value;
		var fechafin = formulario.fechafin.value;

		if (nombrecompeticion == "" && descripcion == "" && fechainicio == "" && fechafin == "")
		{
			formulario.nombrecompeticion.select();
			$(".alert-danger").after("<div class=\"alert alert-warning\">" +
						   	   "<strong>Administrar Competición -</strong> Introduzca algún dato a modificar" +
						   	   "</div>");
			return false;
		}

		if (nombrecompeticion.search(" ") != -1)
		{
			formulario.nombrecompeticion.select();
			$("#nombrecompeticion").after("<div class=\"alert alert-warning\">" +
							  "<strong>Nombre Competicion -</strong> Contiene espacios en blanco. Eliminelos" +
						   	  "</div>");
			return false;
		}

		if(nombrecompeticion.length >= 15)
		{
			formulario.nombrecompeticion.select();
			$("#nombrecompeticion").after("<div class=\"alert alert-warning\">" +
							  "<strong>Nombre Competicion -</strong> Contiene mas de 15 caracteres" +
						   	  "</div>");
			return false;
		}

		if (fechainicio != "" && fechafin != "")
		{
			// Formato de Fecha por defecto. (dd/mm/aaaa)
			expregfecha = /^(0[1-9]|[1-2][0-9]|3[0-1])(\/)([0][1-9]|[1][0-2])\2(\d{4})$/;

			// Obtenemos datos de cada fecha introducida para realizar comprobaciones de que la fecha sea válida.
			var diafechainicio = fechainicio.substring(0,2);
			var diafechafin = fechafin.substring(0,2);
			var mesfechainicio = fechainicio.substring(3,5);
			var mesfechafin = fechafin.substring(3,5);
			var aniofechainicio = fechainicio.substring(6);
			var aniofechafin = fechafin.substring(6);

			if (expregfecha.test(fechainicio) && expregfecha.test(fechafin))
			{
				if ((aniofechainicio < anio || aniofechafin < anio) || 
					((aniofechainicio == anio && mesfechainicio < mes) || (aniofechafin == anio && mesfechafin < mes)) ||
					((aniofechainicio == anio && mesfechainicio == mes && diafechainicio < dia) || (aniofechafin == anio && mesfechafin == mes && diafechafin < dia)))
				{
					formulario.fechainicio.select();
					$(".fecha").prepend("<div class=\"alert alert-warning\">" +
									  "<strong>Fecha -</strong> Fecha Inicio o Fecha Fin anterior a la fecha actual" +
								   	  "</div>");
					return false;
				}
				else
				{
					if (diafechainicio >= diafechafin && mesfechainicio == mesfechafin && aniofechainicio == aniofechafin)
					{
						formulario.fechainicio.select();
						$("#fechainicio").after("<div class=\"alert alert-warning\">" +
										  "<strong>Fecha Inicio -</strong> Posterior o igual a Fecha Fin" +
									   	  "</div>");
						return false;
					}
					if (mesfechainicio > mesfechafin && aniofechainicio == aniofechafin)
					{
						formulario.fechainicio.select();
						$("#fechainicio").after("<div class=\"alert alert-warning\">" +
										  "<strong>Fecha Inicio -</strong> Posterior o igual a Fecha Fin" +
									   	  "</div>");
						return false;
					}
					if (aniofechainicio > aniofechafin)
					{
						formulario.fechainicio.select();
						$("#fechainicio").after("<div class=\"alert alert-warning\">" +
										  "<strong>Fecha Inicio -</strong> Posterior o igual a Fecha Fin" +
									   	  "</div>");
						return false;
					}
				}
			}
			else
			{
				if (!expregfecha.test(fechainicio))
				{
					formulario.fechainicio.select();
					$("#fechainicio").after("<div class=\"alert alert-warning\">" +
									  "<strong>Fecha Inicio -</strong> Fecha NO válida" +
								   	  "</div>");
					return false;
				}

				if (!expregfecha.test(fechafin))
				{
					formulario.fechafin.select();
					$("#fechafin").after("<div class=\"alert alert-warning\">" +
									  "<strong>Fecha Fin -</strong> Fecha NO válida" +
								   	  "</div>");
					return false;
				}
			}
		}

		if (fechainicio == "" && fechafin != "")
		{
			// Formato de Fecha por defecto. (dd/mm/aaaa)
			expregfecha = /^(0[1-9]|[1-2][0-9]|3[0-1])(\/)([0][1-9]|[1][0-2])\2(\d{4})$/;

			// Obtenemos datos de cada fecha introducida para realizar comprobaciones de que la fecha sea válida.
			var diafechafin = fechafin.substring(0,2);
			var mesfechafin = fechafin.substring(3,5);
			var aniofechafin = fechafin.substring(6);

			if (expregfecha.test(fechafin))
			{
				if ((aniofechafin < anio) || (aniofechafin == anio && mesfechafin < mes) || (aniofechafin == anio && mesfechafin == mes && diafechafin <= dia))
				{
					formulario.fechafin.select();
					$(".fecha").prepend("<div class=\"alert alert-warning\">" +
									  "<strong>Fecha -</strong> Fecha Fin anterior a la fecha actual" +
								   	  "</div>");
					return false;
				}
			}
			else
			{
				formulario.fechafin.select();
				$("#fechafin").after("<div class=\"alert alert-warning\">" +
								  "<strong>Fecha Fin -</strong> Fecha NO válida" +
							   	  "</div>");
				return false;
			}
			
		}
		

		if (descripcion.length >= 200)
		{
			formulario.descripcion.select();
			$("#descripcion").after("<div class=\"alert alert-warning\">" +
							  "<strong>Descripción -</strong> Contiene mas de 200 caracteres" +
						   	  "</div>");
			return false;
		}
	});
});