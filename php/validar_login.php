<?php
// Iniciamos la sesión e incluimos la conexión a la base de datos
session_start();
include("conexion.php");

// Recogemos los datos del formulario evitando errores si no llegan
$correo = $_POST["email"] ?? "";
$contrasena = $_POST["password"] ?? "";

// Comprobamos los campos vacíos
if (empty($correo) || empty($contrasena)) {
    header("Location: ../paginas/login.php?error=vacio");
    exit();
}

// Preparamos y ejecutamos la consulta SQL de forma segura para obtener las peticiones del usuario logueado y evitar inyecciones SQL
$consulta = $conexion->prepare("SELECT * FROM usuarios WHERE email = ?");
$consulta->bind_param("s", $correo);
$consulta->execute();
$resultado = $consulta->get_result();

// Comprobamos si existe un usuario con ese correo
if ($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();
    
    // Comprobamos si la contraseña introducida coincide con la contraseña cifrada guardada mediante password_verify
    if (password_verify($contrasena, $usuario["password"])) {
        // Si el login es correcto, regeneramos el ID de sesión (evita la fijación de sesión) y guardamos los datos
        session_regenerate_id(true);
        $_SESSION["id"] = $usuario["id"];
        $_SESSION["nombre"] = $usuario["nombre"];
        $_SESSION["rol"] = $usuario["rol"];
        
        // Redirigimos según si el rol es usuario o admin el rol
        if ($usuario["rol"] == "admin") {
            header("Location: ../paginas/admin.php");
        } else {
            header("Location: ../paginas/usuario.php");
        }
        exit();
    } else {
        // Le indicamos que la contraseña no coincide
        header("Location: ../paginas/login.php?error=password");
        exit();
    }
} else {
    // Le indicamos que no existe ningún usuario con ese correo
    header("Location: ../paginas/login.php?error=usuario");
    exit();
}
?>