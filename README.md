# ICA REMOTE — Plataforma web de soporte técnico remoto

Aplicación web para la gestión y resolución de incidencias informáticas mediante acceso remoto. Los usuarios registrados crean peticiones de soporte seleccionando los servicios que necesitan, y un administrador las gestiona desde un panel de control con soporte remoto integrado a través de AnyDesk.

Proyecto intermodular del Ciclo Formativo de Grado Medio en Sistemas Microinformáticos y Redes (SMR) — Colegio Liceo Sorolla B, 2025–2026.

## Funcionalidades

- Registro e inicio de sesión de usuarios.
- Control de acceso por roles (usuario y administrador).
- Creación de peticiones de soporte con selección de múltiples servicios.
- Panel de administración con listado de peticiones y filtros por estado y fecha.
- Gestión de estados de cada petición (pendiente, completada, rechazada).
- Asistencia remota mediante AnyDesk, con guía de uso para el cliente.
- Respuesta al cliente por correo desde el panel de administración.

## Seguridad

- Cifrado de contraseñas con `password_hash`.
- Cifrado AES del número de AnyDesk almacenado en la base de datos.
- Consultas preparadas para prevenir inyección SQL.
- Validación de propiedad de las peticiones y control de acceso por rol.

## Tecnologías

| Componente     | Tecnología                        |
|----------------|-----------------------------------|
| Frontend       | HTML, CSS, JavaScript             |
| Backend        | PHP                               |
| Base de datos  | MySQL (gestionada con phpMyAdmin) |
| Servidor local | XAMPP (Apache + MySQL + PHP)      |
| Editor         | Visual Studio Code                |
| Acceso remoto  | AnyDesk                           |

## Instalación y uso en local

1. Instala [XAMPP](https://www.apachefriends.org/) y arranca **Apache** y **MySQL** desde el panel.
2. Copia la carpeta del proyecto dentro de `xampp/htdocs/` (por ejemplo `xampp/htdocs/ica_remote`).
3. Crea la base de datos:
   - Abre phpMyAdmin (`http://localhost/phpmyadmin`).
   - Crea una base de datos llamada `ica_remote`.
   - Importa el archivo `ica_remote.sql` incluido en el repositorio.
4. Configura la conexión:
   - Dentro de la carpeta `php/`, copia `config.example.php` y renómbralo a `config.php`.
   - Rellena tus datos de conexión y una clave AES propia.
5. Abre la aplicación en el navegador: `http://localhost/ica_remote`.

## Datos de demostración

El archivo `ica_remote.sql` incluye datos de ejemplo (usuarios y peticiones ficticios) únicamente con fines de demostración. No contiene información personal real.

> Los hashes de contraseña incluidos en el SQL son de ejemplo y no corresponden a contraseñas válidas. Para crear un usuario funcional, regístrate desde la propia web o genera un hash con la utilidad `prueba/hash.php`.

## Mejoras futuras

- Publicar la aplicación en un servidor real.
- Notificaciones por correo al cambiar el estado de una petición.
- Chat interno entre administrador y cliente.
- Diseño totalmente responsive para móvil.

## Autores

Proyecto desarrollado en equipo por Iván Martín Saiz, Ian Martínez López y Juan Francisco García.
