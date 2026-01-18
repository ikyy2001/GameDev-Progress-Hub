# Progress Tracker Game Dev - Server (PowerShell)
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Progress Tracker Game Dev - Server" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Check if PHP is installed
$phpCheck = Get-Command php -ErrorAction SilentlyContinue
if (-not $phpCheck) {
    Write-Host "[ERROR] PHP tidak ditemukan!" -ForegroundColor Red
    Write-Host "Silakan install PHP terlebih dahulu." -ForegroundColor Yellow
    Write-Host "Download: https://www.php.net/downloads" -ForegroundColor Yellow
    Read-Host "Press Enter to exit"
    exit 1
}

# Change to public directory
$scriptPath = Split-Path -Parent $MyInvocation.MyCommand.Path
$publicPath = Join-Path $scriptPath "public"

if (-not (Test-Path $publicPath)) {
    Write-Host "[ERROR] Folder 'public' tidak ditemukan!" -ForegroundColor Red
    Read-Host "Press Enter to exit"
    exit 1
}

Set-Location $publicPath

# Check if database config exists
$configPath = Join-Path $scriptPath "config\database.php"
if (-not (Test-Path $configPath)) {
    Write-Host "[ERROR] File config\database.php tidak ditemukan!" -ForegroundColor Red
    Write-Host "Silakan setup konfigurasi database terlebih dahulu." -ForegroundColor Yellow
    Read-Host "Press Enter to exit"
    exit 1
}

Write-Host "[INFO] Menjalankan PHP built-in server..." -ForegroundColor Green
Write-Host "[INFO] Server akan berjalan di: http://localhost:8000" -ForegroundColor Green
Write-Host "[INFO] Tekan Ctrl+C untuk menghentikan server" -ForegroundColor Yellow
Write-Host ""

# Start PHP server
php -S localhost:8000



