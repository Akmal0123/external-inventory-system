@echo off
echo ====================================================
echo Starting External Inventory System (Port 9000)
echo ====================================================

echo [1/2] Starting Laravel Server (php artisan serve --port=9000)...
start "External Inventory Server (Port 9000)" cmd /k "php artisan serve --port=9000"

echo [2/2] Starting Fastify Integration Service (Port 5000)...
start "Fastify Integration Service" cmd /k "cd integration-service && npm run dev"

echo.
echo Servers started successfully!
echo - External Inventory System: http://localhost:9000
echo - Fastify Integration Service: http://localhost:5000
echo ====================================================
pause
