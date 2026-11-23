@echo off
title UniSpacePro - Servidor Local
cd /d "%~dp0"

echo ============================================================
echo   UNISPACEPRO - INICIANDO SISTEMA
echo ============================================================
echo.

:: 1. Buscar PHP (PATH o XAMPP)
set "PHP_EXE=php"
where php >nul 2>nul
if errorlevel 1 (
    if exist "C:\xampp\php\php.exe" (
        set "PHP_EXE=C:\xampp\php\php.exe"
    ) else (
        echo [ERROR] No se encontro PHP ni en el PATH ni en C:\xampp\php\php.exe.
        echo Por favor instala XAMPP o agrega PHP a tus variables de entorno.
        pause
        exit /b 1
    )
)

:: 2. Verificar si MySQL esta encendido en el puerto 3306
netstat -an | findstr ":3306 " | findstr "LISTENING" >nul
if errorlevel 1 (
    echo [AVISO] MySQL no esta encendido. Intentando iniciar MySQL de XAMPP...
    if exist "C:\xampp\mysql\bin\mysqld.exe" (
        start "" /B "C:\xampp\mysql\bin\mysqld.exe" --defaults-file="C:\xampp\mysql\bin\my.ini" --standalone
        timeout /t 3 /nobreak >nul
    ) else (
        echo [AVISO] No se encontro XAMPP en C:\xampp. Asegurate de encender MySQL manualmente.
    )
)

:: 3. Verificar dependencias de Laravel (vendor)
if not exist "vendor\autoload.php" (
    echo [INFO] No se encontro la carpeta vendor. Ejecutando composer install...
    call composer install
    if errorlevel 1 (
        echo [ERROR] Fallo composer install. Verifica que Composer e internet esten disponibles.
        pause
        exit /b 1
    )
)

:: 4. Limpiar caches de configuracion y vistas
echo [INFO] Preparando Laravel...
"%PHP_EXE%" artisan config:clear >nul 2>nul
"%PHP_EXE%" artisan view:clear >nul 2>nul

:: 5. Abrir navegador e iniciar servidor
echo.
echo [OK] Abriendo http://127.0.0.1:8000 en tu navegador...
echo      (No cierres esta ventana mientras uses la pagina)
echo      Para detener el servidor, presiona Ctrl + C o cierra esta ventana.
echo ============================================================
echo.

start "" "http://127.0.0.1:8000"
"%PHP_EXE%" -S 127.0.0.1:8000 -t public

