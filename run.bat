@echo off
echo ========================================
echo Progress Tracker Game Dev - Server
echo ========================================
echo.

REM Check if PHP is installed
php -v >nul 2>&1
if errorlevel 1 (
    echo [ERROR] PHP tidak ditemukan!
    echo Silakan install PHP terlebih dahulu.
    echo Download: https://www.php.net/downloads
    pause
    exit /b 1
)

REM Change to public directory
cd /d "%~dp0public"

REM Check if database config exists
if not exist "..\config\database.php" (
    echo [ERROR] File config\database.php tidak ditemukan!
    echo Silakan setup konfigurasi database terlebih dahulu.
    pause
    exit /b 1
)

echo [INFO] Menjalankan PHP built-in server...
echo [INFO] Server akan berjalan di: http://localhost:8000
echo [INFO] Tekan Ctrl+C untuk menghentikan server
echo.

REM Start PHP server
php -S localhost:8000

pause



