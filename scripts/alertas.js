// Ocultar y eliminar alertas producidas por el sistema.
$(document).ready(function(){
	function eliminar_alertas (){
    	$(".mensajes").hide("fast");
    	$(".mensajes").remove();
    }

    // Almacena una cadena, convertimos en tipo Number y hallamos la distancia.
    var ancho = $("#header").css("width");
    var anchopag = parseInt(ancho);
    anchopag /= 3;

    // Cambiar valores CSS con JQuery.
    $(".mensajes").css("width", anchopag);
	$(".mensajes").css("margin-left", anchopag);
	$(".mensajes").css("margin-top", $("#header").css("height"));

    setTimeout(eliminar_alertas, 5000);
});