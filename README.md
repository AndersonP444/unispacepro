# UnispacePro - Sistema de Gestión de Espacios UMC

**Equipo de Desarrollo:** Oscar (Líder Frontend), Greymel (Co-líder y Fullstack), Anderson (Base de Datos) y Diego (Backend).

## Requisitos Previos para correr en otra PC
Para que este proyecto funcione en otra computadora, asegúrate de tener instalado:
1. **XAMPP** o PHP 8.1+
2. **Composer** (Gestor de paquetes de PHP)

## Instrucciones de Instalación
Sigue estos pasos si acabas de descomprimir el proyecto en una computadora nueva:

1. **Abre una terminal** en la carpeta raíz del proyecto (`unispacepro`).
2. **Instala las dependencias de Laravel** (Dado que la carpeta `vendor` no se incluye para ahorrar espacio):
   ```bash
   composer install
   ```
3. **Copia el archivo de entorno** (Si no existe un `.env`, copia el de ejemplo):
   ```bash
   cp .env.example .env
   ```
4. **Genera la llave de la aplicación**:
   ```bash
   php artisan key:generate
   ```
5. **Prepara la Base de Datos** (Asegúrate de que la extensión SQLite esté habilitada en tu `php.ini`):
   ```bash
   php artisan migrate
   ```
   *(Nota: La base de datos ya viene incluida en `database/database.sqlite` con los datos de prueba, así que este paso podría no ser estrictamente necesario si el archivo .sqlite se copió intacto).*

6. **Enlaza el almacenamiento de fotos de perfil**:
   ```bash
   php artisan storage:link
   ```

7. **Inicia el Servidor Local**:
   ```bash
   php artisan serve
   ```

El proyecto estará corriendo en `http://127.0.0.1:8000`.

## Cuentas de Prueba
- Puedes registrarte con un correo nuevo.
- O usar las cuentas existentes en la base de datos de SQLite incluida.
