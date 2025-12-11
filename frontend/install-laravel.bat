@echo off
REM Script untuk install Laravel di dalam Docker container (Windows)
REM Script ini akan install Laravel langsung di root, bahkan jika sudah ada file Docker

echo Installing Laravel 12 in Docker container...

REM Cek apakah container sudah running
docker-compose ps | findstr "super-app-php.*Up" >nul
if errorlevel 1 (
    echo Container belum running! Jalankan dulu: docker-compose up -d
    exit /b 1
)

REM Cek apakah Laravel sudah terinstall
if exist "artisan" (
    echo Laravel sudah terinstall!
    exit /b 0
)

REM Install Laravel via Composer di dalam container ke folder temp
echo Installing Laravel 12...
docker-compose exec -T app composer create-project laravel/laravel:^12.0 temp-install --prefer-dist --no-interaction

REM Pindahkan file Laravel dari temp-install ke root
echo Moving Laravel files to root...
docker-compose exec app sh -c "cd temp-install && cp -r app bootstrap config database public resources routes storage tests artisan composer.json composer.lock package.json phpunit.xml vite.config.js .env.example .. 2>/dev/null || true"

REM Hapus folder temp-install
echo Cleaning up...
docker-compose exec app rm -rf temp-install

REM Set permissions
echo Setting permissions...
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www:www storage bootstrap/cache

REM Generate app key
echo Generating application key...
docker-compose exec app php artisan key:generate

REM Copy env file jika belum ada
if not exist ".env" (
    echo Copying .env file...
    docker-compose exec app cp .env.example .env
    echo Jangan lupa edit file .env dengan konfigurasi database dan MinIO!
)

echo Laravel berhasil diinstall!
echo.
echo Langkah selanjutnya:
echo 1. Edit file .env dengan konfigurasi database dan MinIO
echo 2. Copy konfigurasi dari database-sqlsrv-config.php ke config/database.php
echo 3. Setup storage link: docker-compose exec app php artisan storage:link
echo 4. Akses aplikasi di http://localhost:8000

