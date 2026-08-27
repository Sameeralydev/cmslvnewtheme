@echo off
title Running CMSLV New Theme Server (Port 8001)
cd /d "%~dp0"

echo ===================================================
echo   Starting CMSLV New Theme on Port 8001
echo ===================================================
echo.

REM 1. Check if vendor folder exists
if not exist "vendor\autoload.php" (
    echo [1/4] Vendor folder missing. Copying from cmslv...
    if exist "..\cmslv\vendor" (
        xcopy /E /I /Y /Q "..\cmslv\vendor" "vendor"
        echo [1/4] Vendor copied successfully!
    ) else (
        echo [1/4] Running composer install...
        call composer install
    )
) else (
    echo [1/4] Vendor folder is ready.
)

echo.
REM 2. Check if build assets exist
if not exist "public\build\assets\app.css" (
    echo [2/4] Build assets missing. Preparing assets...
    if exist "..\cmslv\public\build" (
        xcopy /E /I /Y /Q "..\cmslv\public\build" "public\build"
    )
)
echo [2/4] Build assets are ready.

echo.
REM 3. Check .env file
if not exist ".env" (
    echo [3/4] Setting up .env file...
    if exist "..\cmslv\.env" (
        copy /Y "..\cmslv\.env" ".env"
    ) else (
        copy /Y ".env.example" ".env"
    )
    php artisan key:generate
) else (
    echo [3/4] .env configuration verified.
)

echo.
REM 4. Open Browser
echo [4/4] Opening browser at http://127.0.0.1:8001...
start http://127.0.0.1:8001

echo.
echo ===================================================
echo   Server is running on: http://127.0.0.1:8001
echo   (Press Ctrl + C to stop the server)
echo ===================================================
echo.

php artisan serve --port=8001
pause
