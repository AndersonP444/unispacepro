# UnispacePro - Resumen de Actividades y Contribuciones por Integrante

Este documento detalla el rol, las responsabilidades y las actividades realizadas por cada uno de los integrantes del equipo de desarrollo para el proyecto **UnispacePro**.

---

## 1. Oscar - Líder de Proyecto
**Rol:** Líder de Proyecto / Integrador y Arquitecto de Software

### Actividades Realizadas:
* **Coordinación y Planificación:** Definición de los hitos del proyecto, asignación de tareas a los integrantes del equipo y seguimiento del progreso de la aplicación.
* **Arquitectura de Software:** Configuración inicial del entorno del proyecto utilizando Laravel, definiendo las convenciones de codificación y la estructura de directorios.
* **Integración del Sistema:** Fusión e integración continua de los módulos de Frontend desarrollados por Greymel, el esquema de Base de Datos de Anderson y la lógica de negocio en Backend de Diego.
* **Gestión de Recursos y Despliegue:** Configuración del servidor local de desarrollo, resolución de conflictos de versión e inicialización de enlaces simbólicos del sistema de almacenamiento (`storage:link`) para la correcta carga de archivos multimedia.

---

## 2. Greymel - Co-líder / Frontend
**Rol:** Diseñadora UI/UX / Maquetadora Frontend

### Actividades Realizadas:
* **Diseño Visual e Interfaz de Usuario:** Diseño estético premium, moderno y responsivo de todas las vistas del sistema utilizando Blade y CSS nativo (evitando plantillas genéricas para dar una identidad única y corporativa).
* **Carrusel Multimedia de Bienvenida:** Diseño e implementación de un carrusel dinámico interactivo con imágenes reales de las instalaciones de la Universidad Marítima del Caribe (UMC) (Laboratorio de Computación, Entrada Principal, Escuela Náutica, Áreas Verdes y Laboratorios).
* **Mapa Interactivo del Campus:** Implementación visual y distribución espacial interactiva para la ubicación física del campus y Google Maps integrado en el inicio.
* **Rediseño del Menú de Navegación (Navbar):** Creación del panel de navegación para usuarios autenticados utilizando un menú compacto e intuitivo con íconos para: **Mi Perfil**, **Dashboard** y **Cerrar Sesión**.
* **Vistas de Recuperación de Contraseña:** Diseño de formularios estilizados para la solicitud de restablecimiento de contraseña y el ingreso de la nueva clave.

---

## 3. Anderson - DB
**Rol:** Administrador de Base de Datos / Ingeniero de Datos

### Actividades Realizadas:
* **Modelado y Esquema de Base de Datos:** Diseño del modelo entidad-relación del sistema que soporta usuarios, roles, carreras, asignaturas, salones, butacas y reservas (tickets).
* **Migraciones de Base de Datos:** Creación y mantenimiento de las migraciones en Laravel para estructurar físicamente las tablas (`usuarios`, `roles`, `carreras`, `salones`, `funciones`, `butacas`, `tickets`, `tokens_reset_password`).
* **Poblamiento de Datos (Seeders y Scripts):**
  * Desarrollo de `DatabaseSeeder.php` y scripts personalizados en Laravel Tinker para poblar las carreras reales de la UMC (Ingeniería Marítima, Informática, Ambiental, Licenciaturas en Administración y Turismo, y TSU en Transporte Acuático).
  * Generación masiva y automática de salones de clase y butacas asociadas a cada salón.
* **Relaciones y Restricciones:** Configuración de llaves foráneas y reglas de integridad referencial para garantizar la coherencia de los datos del sistema.

---

## 4. Diego - Backend
**Rol:** Desarrollador Backend / Lógica de Negocio y APIs

### Actividades Realizadas:
* **Desarrollo de Controladores:** Creación de toda la lógica de backend en controladores de Laravel (`AuthController`, `DashboardController`, `PublicController`, `ReservaController`, `ProfileController`).
* **Sistema de Autenticación Personalizado:** Implementación manual del sistema de inicio de sesión, registro y cierre de sesión adaptado al modelo personalizado `Usuario` (en español), garantizando la seguridad en el acceso a las rutas protegidas.
* **Flujo de Recuperación de Contraseña:** Desarrollo de la lógica para solicitar el enlace de restablecimiento, envío del correo electrónico (`ResetPasswordMail`) y actualización segura de las claves usando la tabla personalizada `tokens_reset_password`.
* **Lógica del Portal del Profesor:** Desarrollo de la sección de visualización en tiempo real para que los profesores puedan ver cuáles estudiantes verán sus clases, listando el nombre del alumno, correo y el número de butaca asignada para cada sesión.
* **Control de Reservación de Asientos:** Lógica backend para procesar reservas de asientos individuales en tiempo real y evitar solapamientos de butacas.
