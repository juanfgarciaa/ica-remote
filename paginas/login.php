<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - ICA Remote</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="pagina-login">
    <div class="login-container">
        <img src="../img/logo.jpg" alt="ICA Remote" class="logo-formulario">
        <h1>Iniciar Sesión</h1>       
        <?php
        // Mostramos los mensajes de error si el login falla desde validar_login.php
        if (isset($_GET["error"])) {
            $errores = [
                "vacio" => "Debes rellenar todos los campos",
                "password" => "La contraseña es incorrecta",
                "usuario" => "Este correo no está registrado"
            ];
            $mensaje = $errores[$_GET["error"]] ?? "Ha ocurrido un error al iniciar sesión";
            echo '<div class="error-login"><span class="icono-error">⚠</span>'.$mensaje.'</div>';
        }
        ?>       
        <div id="mensaje-login"></div>       

        <!-- Validamos el formulario con onsubmit antes de enviarlo a validar_login.php -->
        <form action="../php/validar_login.php" method="POST" onsubmit="return validarLogin(event)">
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Iniciar Sesión</button>
        </form>       
        <div class="link">
            ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
        </div>
    </div>

    <script>
        // Validamos el formulario antes de enviarlo y mostramos errores si algún campo no es válido
        function validarLogin(evento) {
            // Obtenemos los valores de los campos del formulario
            const correo = document.getElementById("email").value;
            const contrasena = document.getElementById("password").value;
            const divMensaje = document.getElementById("mensaje-login");

            // Comprobamos que el correo no está vacío
            if (!correo) {
                evento.preventDefault();
                divMensaje.innerHTML = "<div class='error-login'><span class='icono-error'>⚠</span>Por favor ingresa tu correo electrónico</div>";
                return false;
            }

            // Comprobamos que la contraseña no está vacía
            if (!contrasena) {
                evento.preventDefault();
                divMensaje.innerHTML = "<div class='error-login'><span class='icono-error'>⚠</span>Por favor ingresa tu contraseña</div>";
                return false;
            }

            // Comprobamos que la contraseña tenga al menos 6 caracteres
            if (contrasena.length < 6) {
                evento.preventDefault();
                divMensaje.innerHTML = "<div class='error-login'><span class='icono-error'>⚠</span>La contraseña debe tener al menos 6 caracteres</div>";
                return false;
            }

            // Si todo está bien, enviamos el formulario a validar_login.php
            return true;
        }
    </script>
</body>
</html>