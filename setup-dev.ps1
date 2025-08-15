# Booking Travel - Automatic Development Setup
# This script automatically detects your IP and configures both Laravel and Flutter

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "   Booking Travel - Development Setup" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Get local IP address with better detection
try {
    # Try to get Wi-Fi adapter first
    $localIP = (Get-NetIPAddress -AddressFamily IPv4 -InterfaceAlias "*Wi-Fi*" | Where-Object {$_.IPAddress -like "192.168.*" -or $_.IPAddress -like "10.*" -or $_.IPAddress -like "172.*"} | Select-Object -First 1).IPAddress
    
    # If no Wi-Fi, try Ethernet
    if (-not $localIP) {
        $localIP = (Get-NetIPAddress -AddressFamily IPv4 -InterfaceAlias "*Ethernet*" | Where-Object {$_.IPAddress -like "192.168.*" -or $_.IPAddress -like "10.*" -or $_.IPAddress -like "172.*"} | Select-Object -First 1).IPAddress
    }
    
    # If still no IP, try any non-loopback IPv4 address
    if (-not $localIP) {
        $localIP = (Get-NetIPAddress -AddressFamily IPv4 | Where-Object {$_.IPAddress -ne "127.0.0.1" -and $_.IPAddress -notlike "169.254.*" -and $_.IPAddress -like "192.168.*"} | Select-Object -First 1).IPAddress
    }
    
    if ($localIP) {
        Write-Host "Detected IP Address: $localIP" -ForegroundColor Green
        Write-Host "This IP will work for both Chrome and real devices on your network" -ForegroundColor Green
    } else {
        throw "No suitable IP found"
    }
} catch {
    Write-Host "Could not auto-detect network IP. Using localhost (Chrome only)..." -ForegroundColor Yellow
    $localIP = "localhost"
    Write-Host "WARNING: localhost will only work in Chrome, not on real devices!" -ForegroundColor Red
}

Write-Host ""

# Create Flutter .env file with better configuration
Write-Host "Creating Flutter .env configuration..." -ForegroundColor Yellow

$envContent = @"
# Auto-generated environment configuration
# Generated on $(Get-Date)

# API Base URL - Auto-detected IP address
# This IP ($localIP) should work for both Chrome and real devices
API_BASE_URL=http://$localIP:8000

# Image Base URL
IMAGE_BASE_URL=http://$localIP:8000

# Debug mode
DEBUG_MODE=true

# Network Configuration Info
# Chrome: Works with both localhost and IP
# Real Device: Requires IP address ($localIP)
# Make sure your phone is on the same Wi-Fi network!
"@

Set-Content -Path "booking_travel_flutter\.env" -Value $envContent
Write-Host "Flutter .env file created successfully!" -ForegroundColor Green
Write-Host ""

# Configure Laravel for cross-origin requests (needed for real devices)
Write-Host "Configuring Laravel CORS for mobile devices..." -ForegroundColor Yellow

# Check if Laravel CORS config exists, if not create it
$corsConfigPath = "booking_travel_api\config\cors.php"
if (-not (Test-Path $corsConfigPath)) {
    $corsConfig = @"
<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
"@
    Set-Content -Path $corsConfigPath -Value $corsConfig
    Write-Host "CORS configuration created!" -ForegroundColor Green
}

Write-Host ""

# Start Laravel server with proper host binding
Write-Host "Starting Laravel API server..." -ForegroundColor Yellow
Write-Host "Server will be accessible at: http://$localIP:8000" -ForegroundColor Cyan
Set-Location "booking_travel_api"

# Start Laravel with host binding to accept connections from any IP
Start-Process powershell -ArgumentList "-NoExit", "-Command", "php artisan serve --host=0.0.0.0 --port=8000"

# Wait for Laravel to start
Start-Sleep -Seconds 3

# Start Flutter app
Write-Host "Starting Flutter app..." -ForegroundColor Yellow
Set-Location "..\booking_travel_flutter"
Start-Process powershell -ArgumentList "-NoExit", "-Command", "flutter run"

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "   Development servers are starting..." -ForegroundColor Cyan
Write-Host "   Laravel API: http://$localIP:8000" -ForegroundColor Green
Write-Host "   Flutter app will connect automatically" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Press any key to exit this setup script..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
