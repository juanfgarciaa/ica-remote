<?php
// incluimos el archivo de conexión a la base de datos
include("conexion.php");

// Recogemos los datos enviados desde el formulario de registro
$nombre = $_POST["nombre"];
$correo = $_POST["email"];
$contrasena = $_POST["password"];
$confirmar = $_POST["confirmar"];
$numero_anydesk = $_POST["numero_anydesk"];

// Comprobamos que el correo tiene un formato válido
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo "El correo electrónico no es válido";
    exit();
}

// Comprobamos que las dos contraseñas introducidas coinciden
if ($contrasena != $confirmar) {
    echo "Las contraseñas no coinciden";
    exit();
}

// Comprobamos que la contraseña tenga al menos 10 caracteres
if (strlen($contrasena) < 10) {
    echo "La contraseña debe tener al menos 10 caracteres";
    exit();
}

// Comprobamos que la contraseña contenga al menos una letra mayúscula
if (!preg_match('/[A-Z]/', $contrasena)) {
    echo "La contraseña debe contener al menos una letra mayúscula";
    exit();
}

// Comprobamos que la contraseña contenga al menos un carácter especial
if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\\\|,.<>\/?¡¿]/', $contrasena)) {
    echo "La contraseña debe contener al menos un carácter especial";
    exit();
}

// Ciframos la contraseña usando password_hash generando un hash seguro que no se puede descifrar
$contrasena_cifrada = password_hash($contrasena, PASSWORD_DEFAULT);

// Preparamos la consulta usando AES_ENCRYPT para cifrar el número de AnyDesk desde en la base de datos, y usamos HEX para guardar el resultado como texto
$consulta = $conexion->prepare("INSERT INTO usuarios (nombre, email, password, numero_anydesk) VALUES (?, ?, ?, HEX(AES_ENCRYPT(?, ?)))");
$consulta->bind_param("sssss", $nombre, $correo, $contrasena_cifrada, $numero_anydesk, $clave_aes);

// Ejecutamos la consulta y redirigimos al login si el registro es correcto
try {
    $consulta->execute();
    header("Location: ../paginas/login.php");
} catch (mysqli_sql_exception $e) {
    // Si el correo ya existe (clave única) lo indicamos; cualquier otro error se registra sin mostrar detalles
    if ($e->getCode() == 1062) {
        echo "Ya existe una cuenta con ese correo";
    } else {
        error_log($e->getMessage());
        echo "No se ha podido completar el registro";
    }
}

// Cerramos la consulta y la conexión
$consulta->close();
$conexion->close();
?>