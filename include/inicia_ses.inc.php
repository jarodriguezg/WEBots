<?php
	// Evita que las páginas se almacenen en la caché del navegador 
	session_cache_limiter('nocache,private');
	// Le damos un nombre a la sesion
	session_name('ContenidosWeBots');
	// Iniciamos la sesion o continuamos con la sesión actual
	session_start();
	// Importante que se escriban en este orden.
?>