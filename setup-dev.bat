@echo off
echo ========================================
echo   Booking Travel - Development Setup
echo ========================================
echo.

:: Get the local IP address
for /f "tokens=2 delims=:" %%i in ('ipconfig ^| findstr /c:"IPv4 Address"') do (
    for /f "tokens=1" %%j in ("%%i") do (
        set LOCAL_IP=%%j
        goto :found_ip
    )
)

:found_ip
set LOCAL_IP=%LOCAL_IP: =%
echo Detected IP Address: %LOCAL_IP%
echo.

:: Create Flutter .env file
echo Creating Flutter .env configuration...
cd booking_travel_flutter
(
echo # Auto-generated environment configuration
echo # Generated on %date% at %time%
echo.
echo # API Base URL - Auto-detected IP address
echo API_BASE_URL=http://%LOCAL_IP%:8000
echo.
echo # Image Base URL
echo IMAGE_BASE_URL=http://%LOCAL_IP%:8000
echo.
echo # Debug mode
echo DEBUG_MODE=true
) > .env

echo Flutter .env file created successfully!
echo.

:: Start Laravel server
echo Starting Laravel API server...
cd ..\booking_travel_api
start "Laravel Server" cmd /k "php artisan serve --host=%LOCAL_IP% --port=8000"

:: Wait a moment for Laravel to start
timeout /t 3 /nobreak > nul

:: Start Flutter app
echo Starting Flutter app...
cd ..\booking_travel_flutter
start "Flutter App" cmd /k "flutter run"

echo.
echo ========================================
echo   Development servers are starting...
echo   Laravel API: http://%LOCAL_IP%:8000
echo   Flutter app will connect automatically
echo ========================================
echo.
echo Press any key to exit this setup script...
pause > nul
