<?php
// Iniciamos una nueva sesión e incluimos el archivo de conexión a la base de datos
session_start();
include("../php/conexion.php");
// Comprobamos que el usuario está logueado y su rol es admin, en caso de no serlo lo redirigimos al login
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Admin - ICA Remote</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="pagina-admin">

    <header>
        <div class="header-content">
            <div class="header-titulo">
                <img src="../img/logo.jpg" alt="ICA Remote" class="logo-mini">
                <h1 class="titulo-panel">Panel Admin</h1>
            </div>
            <div class="admin-info">
                <p class="nombre-admin">Administrador</p>
                <a href="Pagina_inicio.php" class="btn-inicio-admin">Inicio</a>
                <a href="../php/logout.php" class="btn-cerrar-sesion">Cerrar Sesión</a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="tabla-container">
            <h2>Peticiones Recibidas</h2>
            
             <div class="filtros-container">
                <div class="filtro-grupo">
                    <label for="filtro-servicio">Servicio</label>
                    <select id="filtro-servicio">
                        <option value="">Todos los servicios</option>
                        <option value="Cambiar las directivas de seguridad">Cambiar las directivas de seguridad</option>
                        <option value="Bloqueo de puertos de USB">Bloqueo de puertos de USB</option>
                        <option value="Optimizar los gráficos de Nvidia">Optimizar los gráficos de Nvidia</option>
                        <option value="Asesoría de componentes de ordenador">Asesoría de componentes de ordenador</option>
                        <option value="Instalación de aplicaciones">Instalación de aplicaciones</option>
                        <option value="Instalación de antivirus">Instalación de antivirus</option>
                        <option value="Limpiador de archivos">Limpiador de archivos</option>
                    </select>
                </div>
                <div class="filtro-grupo">
                    <label for="filtro-estado">Estado</label>
                    <select id="filtro-estado">
                        <option value="">Todos</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="completada">Completada</option>
                        <option value="rechazada">Rechazada</option>
                    </select>
                </div>
                <div class="filtro-grupo">
                    <label for="filtro-fecha">Fecha</label>
                    <input type="date" id="filtro-fecha">
                </div>
                <button class="btn-limpiar" onclick="limpiarFiltro()">Limpiar</button>
            </div>
            
            <table id="tabla-peticiones">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Servicio</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Definimos la consulta sql para mostrar todas las peticiones junto con los datos del cliente
                $sql = "SELECT 
                            p.id, 
                            u.nombre, 
                            u.email,
                            /* Utilizamos AES_DECRYPT con UNHEX para descifrar el número de AnyDesk directamente en la base de datos */
                            AES_DECRYPT(UNHEX(u.numero_anydesk), ?) AS numero_anydesk,
                            p.fecha_peticion, 
                            p.estado, 
                            p.descripcion,
                            /* Concatenamos todos los servicios de una petición en una sola cadena separada por comas */
                            GROUP_CONCAT(s.nombre SEPARATOR ', ') AS todos_servicios
                        FROM peticiones p
                        JOIN usuarios u ON p.id_usuario = u.id
                        LEFT JOIN peticion_servicios ps ON p.id = ps.id_peticion
                        LEFT JOIN servicios s ON ps.id_servicio = s.id
                        GROUP BY p.id
                        ORDER BY p.fecha_peticion DESC";
                // Preparamos y ejecutamos la consulta pasando la clave de cifrado de forma segura
                $consulta = $conexion->prepare($sql);
                $consulta->bind_param("s", $clave_aes);
                $consulta->execute();
                $resultado = $consulta->get_result();
                // Verificamos si la base de datos ha devuelto algún registro
                if ($resultado->num_rows > 0) {
                    // Hacemos un bucle para recorrer cada fila de las peticiones y obtenemos la variable estado para mostrarla
                    while($fila = $resultado->fetch_assoc()) {
                        $estado = $fila["estado"];
                        // Creamos una una clase dinámica para cambiar el color con CSS
                        $claseEstado = "estado-" . $estado;
                        
                        // Convertimos los datos a JSON para pasarlos al JavaScript al pulsar "Ver Detalles"
                        $json_peticion = htmlspecialchars(json_encode($fila), ENT_QUOTES, "UTF-8");
                        
                        // Generamos la fila HTML de la tabla con el id, los servicios, el nombre del usuario, la fecha de la petición con el formato día/mes/año Hora:minuto, y el estado
                        echo "<tr>
                                <td>#".$fila["id"]."</td>
                                <td><strong style='color:#00bcd4;'>".htmlspecialchars($fila["todos_servicios"] ?? "", ENT_QUOTES, "UTF-8")."</strong></td>
                                <td>".htmlspecialchars($fila["nombre"], ENT_QUOTES, "UTF-8")."</td>
                                <td>".date("d/m/Y H:i", strtotime($fila["fecha_peticion"]))."</td>
                                <td><span class='estado $claseEstado'>".$estado."</span></td>
                                <td>
                                    <div class='acciones-container'>
                                        <button class='btn-accion btn-ver' onclick='abrirModalReal($json_peticion)'>Ver Detalles</button>
                                        <select class='btn-accion select-estado select-$estado' onchange='cambiarEstado(this, ".$fila["id"].")'>
                                            <option value='pendiente' ".($estado=='pendiente'?'selected':'').">Pendiente</option>
                                            <option value='completada' ".($estado=='completada'?'selected':'').">Completada</option>
                                            <option value='rechazada' ".($estado=='rechazada'?'selected':'').">Rechazada</option>
                                        </select>
                                    </div>
                            </td>";
                    }
                } else {
                    // Si no hay peticiones, mostrar un mensaje
                    echo "<tr><td colspan='6' class='mensaje-vacio'>No hay peticiones pendientes</td></tr>";
                }
                ?>
                </tbody>
            </table>
            
            <div id="mensaje-vacio" class="mensaje-vacio" style="display: none;">
                No hay peticiones de clientes en este momento.
            </div>
        </div>
    </div>
    
    <div id="modal-detalles" class="modal">
        <div class="modal-content">
            <span class="cerrar-modal" onclick="cerrarModal()">&times;</span>
            <h2>Detalles de la Petición</h2>
            <p><strong>ID de Petición:</strong> <span id="modal-id"></span></p>
            <p><strong>Servicio:</strong> <span id="modal-servicio"></span></p>
            <p><strong>Email del Cliente:</strong> <span id="modal-email"></span></p>
            <p><strong>Número de AnyDesk:</strong> <span id="modal-anydesk"></span></p>
            <p>
                <a id="btn-gmail" href="#" target="_blank" class="btn-gmail">
                    📧 Responder por Gmail
                </a>
            </p>
            <p><strong>Fecha de Envío:</strong> <span id="modal-fecha"></span></p>
            <p><strong>Estado:</strong> <span id="modal-estado"></span></p>
            <p><strong>Mensaje del Cliente:</strong></p>
            <p id="modal-mensaje" style="background-color: #0a1628; padding: 15px; border-radius: 5px; border-left: 4px solid #00bcd4;"></p>
        </div>
    </div>
    
    <footer>
        <p>&copy; <?php echo date("Y");?> ICA Remote. Todos los derechos reservados. Proyecto de estudiantes de 2º SMR.</p>
    </footer>

<script>
    // Creamos la función para abrir el modal de detalles con los datos de la petición mediante el JSON con los datos generado en PHP
    function abrirModalReal(datos) {
        // Rellenamos cada campo del modal con sus valores
        document.getElementById("modal-id").textContent = datos.id;
        document.getElementById("modal-servicio").textContent = datos.todos_servicios;
        document.getElementById("modal-email").textContent = datos.email;
        document.getElementById("modal-anydesk").textContent = datos.numero_anydesk;
        document.getElementById("modal-fecha").textContent = datos.fecha_peticion;
        document.getElementById("modal-mensaje").textContent = datos.descripcion;
        
        // Utilizamos la clase CSS dinámica estado para cambiar el color
        const elementoEstado = document.getElementById("modal-estado");
        elementoEstado.textContent = datos.estado;
        elementoEstado.className = "estado estado-" + datos.estado;

        // Definimos el contenido de los campos asunto y cuerpo según el id de petición
        const asunto = encodeURIComponent("Respuesta a tu petición #" + datos.id + " - ICA Remote");
        const cuerpo = encodeURIComponent(
            "Estimado/a " + datos.nombre + ",\n\n" +
            "Le escribimos desde el departamento de soporte técnico de ICA Remote en relación a su petición #" + datos.id + ", referente al servicio: " + datos.todos_servicios + ".\n\n" +
            "[Escriba aquí su respuesta]\n\n" +
            "Quedamos a su disposición para cualquier consulta adicional.\n\n" +
            "Atentamente,\n" +
            "Equipo de Soporte Técnico\n" +
            "ICA Remote"
        );
        // Definimos la URL de Gmail
        const urlGmail = "https://mail.google.com/mail/?view=cm&fs=1&to=" + 
                         encodeURIComponent(datos.email) + 
                         "&su=" + asunto + 
                         "&body=" + cuerpo;
        document.getElementById("btn-gmail").href = urlGmail;

        // Mostramos el modal añadiendo la clase activo
        document.getElementById("modal-detalles").classList.add("activo");
    }
    
    // Cerramos el modal quitando la clase activo
    function cerrarModal() {
        document.getElementById("modal-detalles").classList.remove("activo");
    }

    // Creamos la función para cambiar el estado de la petición mediante un desplegable
    function cambiarEstado(elementoSelect, idPeticion) {
        const nuevoEstado = elementoSelect.value;      
        // Pedimos confirmación antes de cambiar el estado
        if (!confirm("¿Cambiar el estado de la petición #" + idPeticion + " a \"" + nuevoEstado + "\"?")) {
            // Si cancelamos, que la página se recargue
            location.reload();
            return;
        }     
        // Redirigimos al archivo PHP para que haga el cambio en la base de datos
        window.location.href = "../php/actualizar_estado.php?id=" + idPeticion + "&estado=" + nuevoEstado;
    }
    
    // Cerrarramos el modal si se hace clic fuera de su contenido
    window.onclick = function(evento) {
        const modal = document.getElementById("modal-detalles");
        if (evento.target == modal) {
            cerrarModal();
        }
    }

    // Creamos la función para filtar las peticiones, y esconda las filas que no coincidan con los filtros seleccionados
    function filtrarPeticiones() {
        // Creamos las constantes para obtener los valores de los tres filtros
        const servicioSeleccionado = document.getElementById("filtro-servicio").value.toLowerCase();
        const estadoSeleccionado = document.getElementById("filtro-estado").value;
        const fechaSeleccionada = document.getElementById("filtro-fecha").value;
        const filas = document.querySelectorAll("#tabla-peticiones tbody tr");
        let filasVisibles = 0;

        filas.forEach(fila => {
            // Si la fila no tiene los 6 campos esperados la saltamos
            if (fila.cells.length < 6) return;
            const servicio = fila.cells[1].textContent.toLowerCase();
            const fecha = fila.cells[3].textContent;
            const estado = fila.cells[4].textContent.trim().toLowerCase();

            // Comprobamos si coincide con los filtros de servicio y estado
            const coincideServicio = servicioSeleccionado === "" || servicio.includes(servicioSeleccionado);
            const coincideEstado = estadoSeleccionado === "" || estado === estadoSeleccionado;

            // Creamos la variable para convertir el formato del input date en HTML (año, mes, día) al visual de la tabla (día/mes/año) para comparar
            let coincideFecha = true;
            if (fechaSeleccionada !== "") {
                const partes = fechaSeleccionada.split("-");
                const fechaFormateada = partes[2] + "/" + partes[1] + "/" + partes[0];
                coincideFecha = fecha.startsWith(fechaFormateada);
            }

            // Mostramos u ocultamos la fila según si coincide con los 3 filtros
            if (coincideServicio && coincideEstado && coincideFecha) {
                fila.style.display = "";
                filasVisibles++;
            } else {
                fila.style.display = "none";
            }
        });

        // Creamos la variable para mostrar un mensaje si el filtro no encuentra nada
        let filaSinResultados = document.getElementById("fila-sin-resultados");
        if (filasVisibles === 0 && filas.length > 0) {
            if (!filaSinResultados) {
                const tbody = document.querySelector("#tabla-peticiones tbody");
                const nuevaFila = document.createElement("tr");
                nuevaFila.id = "fila-sin-resultados";
                nuevaFila.innerHTML = "<td colspan='6' class='mensaje-vacio'>No hay peticiones que coincidan con los filtros</td>";
                tbody.appendChild(nuevaFila);
            }
        } else if (filaSinResultados) {
            // Si aparecen resultados en el filtro, quitar el mensaje
            filaSinResultados.remove();
        }
    }

    //Creamos la función para resetear los filtros
    function limpiarFiltro() {
        document.getElementById("filtro-servicio").value = "";
        document.getElementById("filtro-estado").value = "";
        document.getElementById("filtro-fecha").value = "";
        // Ejecutamos la función
        filtrarPeticiones();
    }

    // Conectamos el filtrado con los eventos de cambio de cada filtro
    document.getElementById("filtro-servicio").addEventListener("change", filtrarPeticiones);
    document.getElementById("filtro-estado").addEventListener("change", filtrarPeticiones);
    document.getElementById("filtro-fecha").addEventListener("change", filtrarPeticiones);
</script>
</body>
</html>