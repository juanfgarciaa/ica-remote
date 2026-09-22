<?php
// Cargamos los datos de conexión y la clave AES desde config.php (no incluido en el repositorio)
require __DIR__ . "/config.php";

// Creamos la conexión con la base de datos
$conexion = new mysqli($servidor, $usuario_bd, $contrasena_bd, $nombre_bd);

// Comprobamos si ha ocurrido algún error al conectar sin mostrar detalles internos al usuario
if ($conexion->connect_error) {
    error_log("Error de conexión: " . $conexion->connect_error);
    die("Error al conectar con la base de datos.");
}
$conexion->set_charset("utf8mb4");
?>