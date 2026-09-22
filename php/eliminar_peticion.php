<?php
// Iniciamos una nueva sesión e incluimos el archivo de conexión a la base de datos
session_start();
include("conexion.php");

// Solo usuarios con rol "user" pueden borrar sus peticiones
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "user") {
    header("Location: ../paginas/login.php");
    exit();
}

// Comprobamos que se ha pasado un ID por la URL
if (!isset($_GET["id"])) {
    header("Location: ../paginas/usuario.php");
    exit();
}

// Convertimos el ID de la petición a entero y obtenemos el ID del usuario logueado 
$id_peticion = intval($_GET["id"]);
$id_usuario = $_SESSION["id"];

// Utilizamos una consulta para comprobar que la petición pertenece al usuario y se puede eliminar
$sql_comprobar = "SELECT estado FROM peticiones WHERE id = ? AND id_usuario = ?";
$consulta_comprobar = $conexion->prepare($sql_comprobar);
$consulta_comprobar->bind_param("ii", $id_peticion, $id_usuario);
$consulta_comprobar->execute();
$resultado = $consulta_comprobar->get_result();

// Comprobamos si la petición existe y pertenece a este usuario
if ($resultado->num_rows == 0) {
    header("Location: ../paginas/usuario.php?error=no_existe");
    exit();
}

// Obtenemos el estado actual de la petición
$peticion = $resultado->fetch_assoc();

// Comprobamos que la petición está pendiente o rechazada para poder eliminarla
if ($peticion["estado"] != "pendiente" && $peticion["estado"] != "rechazada") {
    header("Location: ../paginas/usuario.php?error=no_permitido");
    exit();
}
$consulta_comprobar->close();

// Eliminamos primero los servicios asociados a la petición de la tabla intermedia
$sql_servicios = "DELETE FROM peticion_servicios WHERE id_peticion = ?";
$consulta_servicios = $conexion->prepare($sql_servicios);
$consulta_servicios->bind_param("i", $id_peticion);
$consulta_servicios->execute();
$consulta_servicios->close();

// Eliminamos la petición de la base de datos
$sql_eliminar = "DELETE FROM peticiones WHERE id = ? AND id_usuario = ?";
$consulta_eliminar = $conexion->prepare($sql_eliminar);
$consulta_eliminar->bind_param("ii", $id_peticion, $id_usuario);
// Comprobamos si se ha eliminado la petición
if ($consulta_eliminar->execute()) {
    header("Location: ../paginas/usuario.php?eliminada=1");
} else {
    error_log($conexion->error);
    echo "No se ha podido eliminar la petición";
}
// Cerramos la consulta y la conexión
$consulta_eliminar->close();
$conexion->close();
?>