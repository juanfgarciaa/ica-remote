<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - ICA Remote</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="pagina-registro">
    <div class="registro-container">
        <img src="../img/logo.jpg" alt="ICA Remote" class="logo-formulario">
        <h1>Crear Cuenta</h1>
        <div id="mensaje-registro"></div>
        
        <!-- Creamos un formulario de registro que envía los datos a procesar_registro.php por POST que con onsubmit llama a la función para validarse antes -->
        <form action="../php/procesar_registro.php" method="POST" onsubmit="return validarFormulario(event)">
            
            <div class="form-group">
                <label for="nombre">Nombre Completo</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirmar">Confirmar Contraseña</label>
                    <input type="password" id="confirmar" name="confirmar" required>
                </div>
            </div>
            
            <div class="info-password">
                <p><strong>La contraseña debe tener:</strong></p>
                <ul>
                    <li>Mínimo 10 caracteres</li>
                    <li>Al menos una letra mayúscula</li>
                    <li>Al menos un carácter especial (! @ # $ % & * etc.)</li>
                </ul>
            </div>
            
            <div class="form-group">
                <label for="numero-anydesk">
                    Número de AnyDesk
                    <!-- Botón circular con interrogación que lleva a la guía de AnyDesk -->
                    <a href="guia_anydesk.html" target="_blank" class="btn-ayuda-anydesk" title="¿No sabes cuál es tu número de AnyDesk? Haz clic aquí">?</a>
                </label>
                <input type="text" id="numero-anydesk" name="numero_anydesk" placeholder="Ej: 123 456 789" required>
            </div>
            
            <div class="checkbox-group">
                <label for="terminos">
                    <input type="checkbox" id="terminos" name="terminos">
                    <span class="checkbox-text">
                        <!-- Llamamos a la función para mostrar los Términos y condiciones -->
                        Acepto los <a href="#" onclick="mostrarTerminos(event)">Términos y Condiciones</a> de ICA Remote. Autorizo el acceso a mis datos para comunicación, soporte técnico y mejora del servicio.
                    </span>
                </label>
            </div>
            
            <button type="submit">Registrarse</button>
        </form>
        
        <div class="link">
            ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
        </div>
    </div>
    
    <script>
        // Validamos el formulario antes de enviarlo antes de enviarlo y mostramos errores si algún campo no es válido
        function validarFormulario(evento) {
            // Obtenemos los valores de los campos del formulario
            const nombre = document.getElementById("nombre").value;
            const contrasena = document.getElementById("password").value;
            const confirmar = document.getElementById("confirmar").value;
            const terminosAceptados = document.getElementById("terminos").checked;
            const divMensaje = document.getElementById("mensaje-registro");

            // Exigimos que el nombre tenga al menos 3 caracteres
            if (nombre.length < 3) {
                evento.preventDefault();
                divMensaje.innerHTML = "<div class='error'>El nombre debe tener al menos 3 caracteres</div>";
                return false;
            }
            
            // Exigimos que la contraseña tenga mínimo 10 caracteres
            if (contrasena.length < 10) {
                evento.preventDefault();
                divMensaje.innerHTML = "<div class='error'>La contraseña debe tener al menos 10 caracteres</div>";
                return false;
            }
            
            // Exigimos que la contraseña contenga al menos una mayúscula
            if (!/[A-Z]/.test(contrasena)) {
                evento.preventDefault();
                divMensaje.innerHTML = "<div class='error'>La contraseña debe contener al menos una letra mayúscula</div>";
                return false;
            }
            
            // Exigimos que la contraseña contenga al menos un carácter especial
            if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?¡¿]/.test(contrasena)) {
                evento.preventDefault();
                divMensaje.innerHTML = "<div class='error'>La contraseña debe contener al menos un carácter especial (! @ # $ % & * etc.)</div>";
                return false;
            }

            // Exigimos que las contraseñas coincidan
            if (contrasena !== confirmar) {
                evento.preventDefault();
                divMensaje.innerHTML = "<div class='error'>Las contraseñas no coinciden</div>";
                return false;
            }
            
            // Exigimos que se acepten los términos y condiciones
            if (!terminosAceptados) {
                evento.preventDefault();
                divMensaje.innerHTML = "<div class='error'>Debes aceptar los términos y condiciones para continuar</div>";
                return false;
            }
            
            // En caso de todo estar bien, enviamos el formulario a procesar_registro.php
            return true;
        }

        // Creamos una función para mostrar los términos y condiciones en una ventana emergente
        function mostrarTerminos(evento) {
            // Evitamos que el enlace navegue a otra parte    
            evento.preventDefault();   
            
            alert("TÉRMINOS Y CONDICIONES DE ICA REMOTE\n\n" +
                "1. ACEPTACIÓN DE TÉRMINOS\n" +
                "Al registrarte en ICA Remote, aceptas estos términos y condiciones.\n\n" +
                "2. ACCESO A DATOS\n" +
                "Autorizas a ICA Remote a acceder a tu ordenador de forma remota solo cuando solicites el servicio de soporte técnico.\n\n" +
                "3. PRIVACIDAD\n" +
                "Los datos proporcionados los usaremos solo para prestarte el servicio y comunicarnos contigo.\n\n" +
                "4. SEGURIDAD\n" +
                "Todos los datos se almacenan cifrados.\n\n" +
                "5. COMUNICACIÓN\n" +
                "Podemos contactarte por correo electrónico sobre tu soporte técnico y actualizaciones del servicio.\n\n" +
                "6. RESPONSABILIDAD\n" +
                "ICA Remote no se responsabiliza por daños en sistemas debido a instrucciones del usuario durante el soporte.\n\n" +
                "7. DERECHO A CANCELAR\n" +
                "Puedes eliminar tu petición en cualquier momento desde tu panel de usuario."
            );
        }
    </script>
</body>
</html>