#!/bin/bash

# Script untuk install Laravel di dalam Docker container
# Script ini akan install Laravel langsung di root, bahkan jika sudah ada file Docker

echo "🚀 Installing Laravel 12 in Docker container..."

# Cek apakah container sudah running
if ! docker-compose ps | grep -q "super-app-php.*Up"; then
    echo "❌ Container belum running! Jalankan dulu: docker-compose up -d"
    exit 1
fi

# Cek apakah Laravel sudah terinstall
if [ -f "artisan" ]; then
    echo "✅ Laravel sudah terinstall!"
    exit 0
fi

# Install Laravel via Composer di dalam container
# Gunakan --ignore-platform-reqs untuk menghindari masalah jika folder tidak kosong
echo "📦 Installing Laravel 12..."
docker-compose exec -T app composer create-project laravel/laravel:^12.0 temp-install --prefer-dist --no-interaction

# Pindahkan file Laravel dari temp-install ke root
echo "📁 Moving Laravel files to root..."
docker-compose exec app sh -c "cd temp-install && cp -r app bootstrap config database public resources routes storage tests artisan composer.json composer.lock package.json phpunit.xml vite.config.js .env.example .. 2>/dev/null || true"

# Hapus folder temp-install
echo "🧹 Cleaning up..."
docker-compose exec app rm -rf temp-install

# Set permissions
echo "🔐 Setting permissions..."
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www:www storage bootstrap/cache

# Generate app key
echo "🔑 Generating application key..."
docker-compose exec app php artisan key:generate

# Copy env file jika belum ada
if [ ! -f ".env" ]; then
    echo "📝 Copying .env file..."
    docker-compose exec app cp .env.example .env
    echo "⚠️  Jangan lupa edit file .env dengan konfigurasi database dan MinIO!"
fi

echo "✅ Laravel berhasil diinstall!"
echo ""
echo "📋 Langkah selanjutnya:"
echo "1. Edit file .env dengan konfigurasi database dan MinIO"
echo "2. Copy konfigurasi dari database-sqlsrv-config.php ke config/database.php"
echo "3. Setup storage link: docker-compose exec app php artisan storage:link"
echo "4. Akses aplikasi di http://localhost:8000"

