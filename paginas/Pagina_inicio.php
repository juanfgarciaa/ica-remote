<?php 
// Iniciamos la sesión para gestionar el estado del usuario
session_start(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de inicio - ICA Remote</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="pagina-inicio">

    <header>
        <div class="header-content">
            <img src="../img/logo.jpg" alt="ICA Remote Logo" class="logo-principal">
            <p class="subtitle">Soporte técnico remoto seguro</p>
        </div>
    </header>

    <nav>
        <div class="nav-content">
            <ul>
                <li><a href="#inicio">Inicio</a></li>
                <li><a href="#empresa">Empresa</a></li>
                <li><a href="#servicios">Servicios</a></li>
                <li><a href="#contacto">Contacto</a></li>
            </ul>
            
            <!-- Mostramos diferentes botones según el estado de la sesión -->
            <div class="auth-buttons">
                <?php if (isset($_SESSION["id"])): ?>
                    <!-- Si el usuario está logueado le mostramos un saludo personalizado y su panel correspondiente -->
                    <span class="bienvenida">Hola, <strong><?php echo htmlspecialchars($_SESSION["nombre"]); ?></strong></span>
                    
                    <?php if ($_SESSION["rol"] == "admin"): ?>
                        <!-- Si el usuario es admin el botón lo redirige al panel del admin -->
                        <a href="admin.php" class="btn btn-registro">Mi Panel</a>
                    <?php else: ?>
                        <!-- Si es un usuario estándar el botón lo redirige al panel del usuario -->
                        <a href="usuario.php" class="btn btn-registro">Mi Panel</a>
                    <?php endif; ?>
                    
                    <a href="../php/logout.php" class="btn btn-cerrar-sesion">Cerrar Sesión</a>
                <?php else: ?>
                    <!-- Si el usuario no está logueado mostramos los botones de login y registro -->
                    <a href="login.php" class="btn btn-login">Iniciar sesión</a>
                    <a href="registro.php" class="btn btn-registro">Registrarse</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container">

        <section class="seccion" id="inicio">
            <h2>Bienvenido a ICA Remote</h2>
            <div class="descripcion">
                <p>Somos un servicio de soporte técnico remoto dedicado a ayudarte con todos tus problemas informáticos. Nos conectamos de forma segura a tu ordenador para resolver cualquier inconveniente que tengas.</p>
                <p>Tu seguridad es nuestra prioridad. Todos los datos se cifran y se eliminan automáticamente después de 3 meses.</p>
            </div>
        </section>

        <section class="seccion" id="empresa">
            <h2>Sobre Nosotros</h2>
            <div class="descripcion">
                <p>ICA Remote es un pequeño servicio técnico creado por estudiantes de 2º de SMR (Sistemas Microinformáticos y Redes). Nuestro objetivo es proporcionar asistencia técnica remota de calidad, combinando conocimientos en seguridad informática y redes.</p>
            </div>
            <div class="info-equipo">
                <h3>Equipo de Desarrollo</h3>
                <p>Juan Francisco Garcia Lovera</p>
                <p>Iván Martín Saiz</p>
                <p>Ian Martinez Lopez</p>
            </div>
        </section>

        <section class="seccion" id="anydesk">
            <h2>Herramienta que Utilizamos</h2>
            <div class="anydesk-container">
                <div class="anydesk-info">
                    <h3>AnyDesk - Conexión Remota Segura</h3>
                    <p>Para poder ofrecerte nuestro servicio de soporte técnico remoto, utilizamos <strong>AnyDesk</strong>, una de las aplicaciones de escritorio remoto más fiables y seguras del mercado.</p>
                    <p>AnyDesk nos permite conectarnos a tu ordenador de forma segura, con tu permiso, para resolver los problemas técnicos que tengas sin que tengas que moverte de casa. La conexión está cifrada de extremo a extremo y solo se establece cuando tú la aceptas mediante un código único de identificación.</p>
                    <p><strong>¿Por qué AnyDesk?</strong> Es ligera, rápida, gratuita para uso personal y compatible con Windows, Mac y Linux. Solo necesitas descargarla, abrirla y darnos tu ID de AnyDesk para que podamos ayudarte.</p>
                    <a href="guia_anydesk.html" class="btn btn-anydesk">Cómo instalar y usar AnyDesk</a>
                </div>
            </div>
        </section>

        <section class="seccion" id="servicios">
            <h2>Servicios que Ofrecemos</h2>
            <div class="servicios">
                <div class="servicio">
                    <h3>Cambiar Directiva de Seguridad</h3>
                    <p>Configuramos las políticas de seguridad de tu ordenador para protegerte mejor contra amenazas.</p>
                </div>
                <div class="servicio">
                    <h3>Bloqueo de Puertos USB</h3>
                    <p>Bloqueamos los puertos USB no autorizados para evitar acceso no permitido a tu equipo.</p>
                </div>
                <div class="servicio">
                    <h3>Optimización NVIDIA</h3>
                    <p>Configuramos y optimizamos tus drivers NVIDIA para mejorar el rendimiento gráfico.</p>
                </div>
                <div class="servicio">
                    <h3>Componentes de Ordenador</h3>
                    <p>Te asesoramos sobre qué componentes necesitas y cuál es la mejor opción para tu presupuesto.</p>
                </div>
                <div class="servicio">
                    <h3>Instalación de Aplicaciones</h3>
                    <p>Instalamos y configuramos todas las aplicaciones que necesites de forma rápida y segura.</p>
                </div>
                <div class="servicio">
                    <h3>Instalación de Antivirus</h3>
                    <p>Instalamos y configuramos antivirus para proteger tu ordenador contra malware.</p>
                </div>
                <div class="servicio">
                    <h3>Limpiador de Archivos</h3>
                    <p>Eliminamos archivos basura y temporales para liberar espacio y mejorar el rendimiento.</p>
                </div>
            </div>
        </section>
    </div>
    
    <!-- Mostramos distintas opciones según si el usuario ha iniciado sesión -->
    <div class="footer-section" id="contacto">
        <div class="footer-content">
            <h2>¿Necesitas Ayuda?</h2>
            <?php if (isset($_SESSION["id"])): ?>
                <!-- Si el usuario ya está logueado se le ofrece ir a su panel correspondiente -->
                <p>Accede a tu panel para solicitar soporte técnico</p>
                <div class="auth-buttons">
                    <?php if ($_SESSION["rol"] == "admin"): ?>
                        <a href="admin.php" class="btn btn-registro">Ir al Panel Admin</a>
                    <?php else: ?>
                        <a href="usuario.php" class="btn btn-registro">Ir a Mi Panel</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <!-- Si el usuario no está logueado le invitamos a registrarse -->
                <p>Regístrate para solicitar soporte técnico remoto</p>
                <div class="auth-buttons">
                    <a href="registro.php" class="btn btn-registro">Registrarse</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> ICA Remote. Todos los derechos reservados. Proyecto de estudiantes de 2º SMR.</p>
    </footer>
</body>
</html>