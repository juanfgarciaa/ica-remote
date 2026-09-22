<?php
// Iniciamos una nueva sesión e incluimos el archivo de conexión a la base de datos
session_start();
include("../php/conexion.php");
// Comprobamos que el usuario está logueado y su rol es user, en caso de no serlo lo redirigimos al login
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "user") {
    header("Location: login.php");
    exit();
}
// Definimos la consulta sql para mostrar las peticiones del usuario junto a sus servicios
$id_usuario = $_SESSION["id"];
$sql_peticiones = "SELECT 
                        p.id, 
                        p.fecha_peticion, 
                        p.estado, 
                        p.descripcion,
                        /* Concatenamos todos los servicios de la petición en una sola cadena separada por comas */
                        GROUP_CONCAT(s.nombre SEPARATOR ', ') AS servicios
                    FROM peticiones p
                    LEFT JOIN peticion_servicios ps ON p.id = ps.id_peticion
                    LEFT JOIN servicios s ON ps.id_servicio = s.id
                    WHERE p.id_usuario = ?
                    GROUP BY p.id
                    ORDER BY p.fecha_peticion DESC";

// Preparamos y ejecutamos la consulta SQL de forma segura para obtener las peticiones del usuario logueado y evitar inyecciones SQL
$consulta_peticiones = $conexion->prepare($sql_peticiones);
$consulta_peticiones->bind_param("i", $id_usuario);
$consulta_peticiones->execute();
$resultado_peticiones = $consulta_peticiones->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Cliente - ICA Remote</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="pagina-usuario">

<header>
    <div class="header-titulo">
        <img src="../img/logo.jpg" alt="ICA Remote" class="logo-mini">
        <h1>Panel Usuario</h1>
    </div>
    <div>
        <!-- Mostramos un mensaje de bienvenida personalizado -->
        Bienvenido, <strong><?php echo htmlspecialchars($_SESSION["nombre"], ENT_QUOTES, "UTF-8"); ?></strong>
        <a href="Pagina_inicio.php" class="btn-inicio">Inicio</a>
        <a href="../php/logout.php" class="btn-cerrar-sesion">Cerrar Sesión</a>
    </div>
</header>

<?php 
// Mostramos mensajes según la acción realizada por el usuario
?>
<?php if (isset($_GET["success"])): ?>
    <!-- Mostramos un mensaje cuando la petición se envía correctamente -->
    <div class="success">Petición enviada correctamente</div>
<?php endif; ?>

<?php if (isset($_GET["eliminada"])): ?>
    <!-- Mostramos un mensaje cuando la petición se elimina correctamente -->
    <div class="success">Petición eliminada correctamente</div>
<?php endif; ?>

<?php if (isset($_GET["error"])): ?>
    <!-- Mostramos un mensaje de error correspondiente cuando algo ha fallado -->
    <div class="error-msg">
        <?php
        $errores = [
            "vacio" => "Debes seleccionar al menos un servicio y escribir un mensaje",
            "no_permitido" => "No puedes eliminar esta petición",
            "no_existe" => "La petición no existe"
        ];
        echo $errores[$_GET["error"]] ?? "Ha ocurrido un error";
        ?>
    </div>
<?php endif; ?>

<div class="container">
    
    <h2>Solicitar Soporte Técnico</h2>
    <form method="POST" action="../php/procesar_peticion.php">
        <div class="formulario-peticion">
            <div id="peticiones-container"></div>

            <!-- Llamamos a la función para añadir un nuevo servicio -->
            <button type="button" class="btn-agregar" id="btn-agregar" onclick="agregarServicio()">
                + Añadir servicio
            </button>
            <p class="info-contador"><span id="contador">0</span> / 7 servicios seleccionados</p>

            <br>
            <label>Mensaje global</label>
            <textarea name="mensaje" required placeholder="Describe tu problema..."></textarea>

            <button type="submit" class="btn-enviar">
                Enviar petición
            </button>
        </div>
    </form>

    <h2>Mis Peticiones</h2>
    <div class="panel-peticiones">
        <!-- Comprobamos si el usuario tiene peticiones guardadas -->
        <?php if ($resultado_peticiones->num_rows > 0): ?>
            <!-- Recorremos todas las peticiones obtenidas de la base de datos -->
            <?php while ($peticion = $resultado_peticiones->fetch_assoc()): ?>
                <!-- Creamos la tarjeta en la que mostramos el ID, los servicios, la fecha de creación, el mensaje escrito y el estado de la petición-->
                <div class="peticion-card">
                    <h4>Petición #<?php echo $peticion["id"]; ?></h4>
                    <p><strong>Servicios:</strong> <?php echo htmlspecialchars($peticion["servicios"] ?? "(sin servicio)"); ?></p>
                    <p><strong>Fecha:</strong> <?php echo date("d/m/Y H:i", strtotime($peticion["fecha_peticion"])); ?></p>
                    <p><strong>Mensaje:</strong> <?php echo htmlspecialchars($peticion["descripcion"]); ?></p>
                    <p><strong>Estado:</strong> 
                        <span class="estado estado-<?php echo $peticion["estado"]; ?>">
                            <?php echo $peticion["estado"]; ?>
                        </span>
                    </p>
                    
                    <?php 
                    // Comprobamos si la petición está pendiente o rechazada para permitir eliminarla
                    if ($peticion["estado"] == "pendiente" || $peticion["estado"] == "rechazada"): 
                    ?>
                        <!-- Creamos un botón para eliminar la petición que pida confirmación -->
                        <a href="../php/eliminar_peticion.php?id=<?php echo $peticion["id"]; ?>" 
                           class="btn-eliminar" 
                           onclick="return confirm('¿Seguro que quieres eliminar esta petición?')">
                            Eliminar petición
                        </a>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <!-- Si el usuario aún no tiene peticiones mostramos un mensaje -->
            <p class="mensaje-vacio">Aún no has hecho ninguna petición.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    // Definimos una constante con el máximo de servicios permitidos
    const MAX_SERVICIOS = 7;
    
    // Definimos una lista con los servicios disponibles
    const listaServicios = [
        { id: 1, nombre: "Cambiar las directivas de seguridad" },
        { id: 2, nombre: "Bloqueo de puertos de USB" },
        { id: 3, nombre: "Optimizar los gráficos de Nvidia" },
        { id: 4, nombre: "Asesoría de componentes de ordenador" },
        { id: 5, nombre: "Instalación de aplicaciones" },
        { id: 6, nombre: "Instalación de antivirus" },
        { id: 7, nombre: "Limpiador de archivos" }
    ];

    // Creamos la función para obtener los servicios que se están seleccionando en los desplegables
    function obtenerServiciosSeleccionados() {
        const desplegables = document.querySelectorAll('select[name="servicios[]"]');
        const seleccionados = [];
        // Recorremos los desplegables y guardamos los servicios seleccionados
        desplegables.forEach(desplegable => {
            if (desplegable.value) seleccionados.push(desplegable.value);
        });
        return seleccionados;
    }

    // Creamos la función que actualiza las opciones disponibles de los desplegables
    function actualizarOpcionesSelects() {
        const seleccionados = obtenerServiciosSeleccionados();
        const desplegables = document.querySelectorAll('select[name="servicios[]"]');
        // Obtenemos todos los desplegables con los servicios
        desplegables.forEach(desplegable => {
            const valorActual = desplegable.value;
            // Reiniciamos el desplegable y ponemos la opción por defecto
            desplegable.innerHTML = '<option value="">Selecciona un servicio</option>';
            
            // Añadimos cada servicio si no está ya seleccionado en otro lado
            listaServicios.forEach(servicio => {
                if (!seleccionados.includes(String(servicio.id)) || String(servicio.id) === valorActual) {
                    const opcion = document.createElement("option");
                    opcion.value = servicio.id;
                    opcion.textContent = servicio.nombre;
                    if (String(servicio.id) === valorActual) opcion.selected = true;
                    desplegable.appendChild(opcion);
                }
            });
        });
    }

    // Creamos la función que actualiza el contador con los servicios seleccionados
    function actualizarContador() {
        // Contamos cuántos servicios están añadidos, y actualizamos el número
        const total = document.querySelectorAll(".peticion-item").length;
        document.getElementById("contador").textContent = total;
        
        // Deshabilitamos el botón si se llegó al máximo, y ponemos un mensaje indicándolo
        const boton = document.getElementById("btn-agregar");
        boton.disabled = total >= MAX_SERVICIOS;
        boton.textContent = total >= MAX_SERVICIOS ? "Máximo alcanzado (7/7)" : "+ Añadir servicio";
    }

    // Creamos la función para añadir un nuevo servicio, en la que contamos cuántos servicios están añadidos
    function agregarServicio() {
        const total = document.querySelectorAll(".peticion-item").length;
        
        // Comprobamos que no se supera el límite máximo
        if (total >= MAX_SERVICIOS) {
            alert("Solo puedes solicitar un máximo de 7 servicios.");
            return;
        }
        
        // Creamos el contenedor de la nueva petición con un botón para quitarla y el desplegable de los servicios
        const nuevoItem = document.createElement("div");
        nuevoItem.classList.add("peticion-item");
        nuevoItem.innerHTML = `
            <button type="button" class="btn-quitar" onclick="quitarServicio(this)" title="Quitar servicio">−</button>
            <label>Servicio</label>
            <select name="servicios[]" required onchange="actualizarOpcionesSelects()">
                <option value="">Selecciona un servicio</option>
                ${listaServicios.map(servicio => `<option value="${servicio.id}">${servicio.nombre}</option>`).join("")}
            </select>
        `;
        // Añadimos el nuevo servicio y actualizamos la interfaz
        document.getElementById("peticiones-container").appendChild(nuevoItem);
        actualizarOpcionesSelects();
        actualizarContador();
    }

    // Creamos la función para quitar el desplegable con el servicio seleccionado, eliminando el contenedor y actualizando las opciones y el contador
    function quitarServicio(boton) {
        boton.parentElement.remove();
        actualizarOpcionesSelects();
        actualizarContador();
    }

    // Al cargar la página añadimos automáticamente un servicio vacío
    agregarServicio();
</script>
</body>
</html>