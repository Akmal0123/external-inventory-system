@echo off
echo ====================================================
echo Starting External Inventory System (Port 9000)
echo ====================================================
echo.
echo [INFO] Catatan Arsitektur:
echo        Fastify Integration Service TIDAK lagi dijalankan dari sini.
echo        Integration service kini berdiri sendiri di folder:
echo        ..\integration-service\
echo.
echo        Gunakan start-all.bat di root project untuk menjalankan
echo        semua service sekaligus (AMS + Fastify + EIS).
echo.

echo [1/1] Starting Laravel Server (php artisan serve --port=9000)...
start "EIS Laravel Server (Port 9000)" cmd /k "php artisan serve --port=9000"

echo.
echo Server started successfully!
echo - External Inventory System: http://localhost:9000
echo.
echo Untuk menjalankan SEMUA service, gunakan:
echo   ..\start-all.bat
echo ====================================================
pause
