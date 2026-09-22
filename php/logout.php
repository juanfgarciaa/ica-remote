<?php
// Iniciamos la sesión para poder cerrarla correctamente
session_start();
// Eliminamos todos los datos de la sesión actual
session_destroy();
// Redirigimos al usuario a la página de inicio
header("Location: ../paginas/Pagina_inicio.php");
exit();
?>