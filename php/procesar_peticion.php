<?php
// Iniciamos una nueva sesión e incluimos el archivo de conexión a la base de datos
session_start();
include("conexion.php");

// Comprobamos que el usuario está logueado y su rol es user, en caso de no serlo lo redirigimos al login
if (!isset($_SESSION["id"]) || $_SESSION["rol"] != "user") {
    header("Location: ../paginas/login.php");
    exit();
}

// Recogemos el ID del usuario logueado, los servicios seleccionados y el mensaje escrito
$id_usuario = $_SESSION["id"];
$servicios_seleccionados = $_POST["servicios"] ?? [];
$descripcion = trim($_POST["mensaje"] ?? '');

// Comprobamos que se haya enviado al menos un servicio y un mensaje
if (empty($servicios_seleccionados) || $descripcion === "") {
    header("Location: ../paginas/usuario.php?error=vacio");
    exit();
}

// Iniciamos una transacción para que, si ocurre un error, no se guarde nada incompleto dentro de la base de datos
try {
    $conexion->begin_transaction();
    
    // Insertamos la petición principal en la tabla peticiones con estado pendiente
    $sql_peticion = "INSERT INTO peticiones (id_usuario, descripcion, estado) VALUES (?, ?, 'pendiente')";
    $consulta = $conexion->prepare($sql_peticion);
    $consulta->bind_param("is", $id_usuario, $descripcion);
    $consulta->execute();
    
    // Guardamos el ID de la petición recién creada para relacionarla con los servicios
    $id_peticion_generada = $conexion->insert_id;
    
    // Definimos la consulta para insertar los servicios seleccionados en la tabla intermedia
    $sql_intermedia = "INSERT INTO peticion_servicios (id_peticion, id_servicio) VALUES (?, ?)";
    $consulta_servicios = $conexion->prepare($sql_intermedia);
    // Recorremos todos los servicios seleccionados por el usuario convirtiendo sus IDs a enteros
    foreach ($servicios_seleccionados as $servicio_id) {
        $id_servicio = (int)$servicio_id;
        // Insertamos cada servicio junto al ID de la petición creada
        $consulta_servicios->bind_param("ii", $id_peticion_generada, $id_servicio);
        $consulta_servicios->execute();
    }
    
    // Confirmamos todos los cambios realizados en la base de datos
    $conexion->commit();
    header("Location: ../paginas/usuario.php?success=1");
    
} catch (Exception $e) {
    // Si algo falló, cancelamos todos los cambios realizados
    $conexion->rollback();
    error_log($e->getMessage());
    die("No se ha podido registrar la petición");
}
// Cerramos las consultas y la conexión a la base de datos
$consulta->close();
$conexion->close();
exit();
?>