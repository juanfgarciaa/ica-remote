<?php
// Iniciamos la sesión e incluimos la conexión a la base de datos
session_start();
include("conexion.php");

// Comprobamos que el usuario logueado es el admin, y en caso de no serlo lo redirigimos al login
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../paginas/login.php");
    exit();
}

// Comprobamos que se han recibido el ID y el estado actualizado
if (!isset($_GET["id"]) || !isset($_GET["estado"])) {
    header("Location: ../paginas/admin.php");
    exit();
}

// Convertimos ID a entero por seguridad y guardar el nuevo estado
$id_peticion = intval($_GET["id"]);
$nuevo_estado = $_GET["estado"];

// Definimos los estados permitidos para evitar valores no válidos
$estados_validos = ["pendiente", "completada", "rechazada"];
if (!in_array($nuevo_estado, $estados_validos)) {
    header("Location: ../paginas/admin.php?error=estado_invalido");
    exit();
}

// Preparamos y ejecutamos la consulta para actualizar el estado de la petición en la base de datos
$consulta = $conexion->prepare("UPDATE peticiones SET estado = ? WHERE id = ?");
$consulta->bind_param("si", $nuevo_estado, $id_peticion);

// Comprobamos si la actualización se ha realizado correctamente
if ($consulta->execute()) {
    header("Location: ../paginas/admin.php?msj=estado_actualizado");
} else {
    error_log($conexion->error);
    echo "No se ha podido actualizar el estado";
}

// Cerramos la consulta y la conexión
$consulta->close();
$conexion->close();
?>