@echo off
:: 1. Cambia la codificación para evitar problemas con la 'ñ'
chcp 65001 > nul

:: 2. EL TRUCO: %~dp0 obtiene la ruta de la carpeta donde vive este archivo .bat
set PROJECT_PATH=%~dp0

:: 3. Entra a esa carpeta automáticamente
cd /d "%PROJECT_PATH%"

:: 4. Ejecuta Laravel usando la ruta detectada para el log
php artisan schedule:run >> "%PROJECT_PATH%storage\logs\cron_windows.log" 2>&1