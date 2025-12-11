# Super App - Quick Start Guide

## Quick Start (Windows)

**PENTING**: Semua proses dilakukan di dalam Docker, tidak perlu install PHP/Composer di host!

```bash
# 1. Buka WSL2 terminal atau PowerShell
cd C:\kerjaan\super-app

# 2. Build dan jalankan Docker container terlebih dahulu
docker-compose up -d --build

# 3. Install Laravel di dalam Docker container (jika belum ada project Laravel)
# Opsi A: Menggunakan script (lebih mudah)
bash install-laravel.sh
# atau di PowerShell:
# .\install-laravel.bat

# Opsi B: Manual
docker-compose exec app composer create-project laravel/laravel:^12.0 . --prefer-dist --no-interaction
docker-compose exec app cp .env.example .env
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan storage:link
docker-compose exec app chmod -R 775 storage bootstrap/cache

# 4. Copy file env.example ke .env (jika belum ada)
cp env.example .env

# 5. Edit .env dengan konfigurasi database staging

# 6. Setup database connection di config/database.php (lihat MULTIPLE-DATABASE.md)

# 7. Akses aplikasi
# http://localhost:8000
```

## Quick Start (Linux)

**PENTING**: Semua proses dilakukan di dalam Docker, tidak perlu install PHP/Composer di host!

```bash
# 1. Navigasi ke folder
cd /path/to/super-app

# 2. Build dan jalankan Docker container terlebih dahulu
docker-compose up -d --build

# 3. Install Laravel di dalam Docker container (jika belum ada project Laravel)
# Opsi A: Menggunakan script (lebih mudah)
bash install-laravel.sh

# Opsi B: Manual
docker-compose exec app composer create-project laravel/laravel:^12.0 . --prefer-dist --no-interaction
docker-compose exec app cp .env.example .env
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan storage:link
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www:www storage bootstrap/cache

# 4. Copy file env.example ke .env (jika belum ada)
cp env.example .env

# 5. Edit .env dengan konfigurasi staging
nano .env

# 6. Setup database connection di config/database.php (lihat MULTIPLE-DATABASE.md)

# 7. Untuk production, cache config
docker-compose exec app php artisan config:cache

# 8. Akses aplikasi
# http://localhost:8000
```

## Setup Multiple Database Connection

Aplikasi ini mendukung **3 database sekaligus**:
- **1 MySQL** (default connection)
- **2 SQL Server** (sqlsrv dan sqlsrv2)

### 1. Konfigurasi Environment Variables

Edit file `.env` dan isi dengan konfigurasi semua database:

```env
# Database MySQL (Default)
DB_CONNECTION=mysql
DB_HOST=your_mysql_host
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Database SQL Server 1
SQLSRV_HOST=your_sqlserver_host
SQLSRV_PORT=1433
SQLSRV_DATABASE=your_database_name
SQLSRV_USERNAME=your_username
SQLSRV_PASSWORD=your_password

# Database SQL Server 2
SQLSRV2_HOST=your_sqlserver2_host
SQLSRV2_PORT=1433
SQLSRV2_DATABASE=your_database2_name
SQLSRV2_USERNAME=your_username2
SQLSRV2_PASSWORD=your_password2
```

### 2. Setup Database Configuration

Setelah Laravel terinstall, edit `config/database.php`:

1. Buka file `config/database.php`
2. Di dalam array `'connections'`, tambahkan konfigurasi dari file `database-sqlsrv-config.php`

Atau copy konfigurasi lengkap untuk 3 database (lihat file `database-sqlsrv-config.php`)

### 3. Penggunaan di Code

```php
// Menggunakan MySQL (default)
DB::table('users')->get();

// Menggunakan SQL Server 1
DB::connection('sqlsrv')->table('orders')->get();

// Menggunakan SQL Server 2
DB::connection('sqlsrv2')->table('products')->get();
```

**Lihat file `MULTIPLE-DATABASE.md` untuk dokumentasi lengkap penggunaan multiple database.**

## Setup MinIO Storage

1. Pastikan `.env` sudah dikonfigurasi:
```env
FILESYSTEM_DRIVER=s3
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your_bucket_name
AWS_ENDPOINT=https://your_minio_endpoint
AWS_USE_PATH_STYLE_ENDPOINT=true
```

2. Untuk local development dengan MinIO di Docker:
```env
AWS_ENDPOINT=http://minio:9000
AWS_ACCESS_KEY_ID=minioadmin
AWS_SECRET_ACCESS_KEY=minioadmin
```

3. Buat bucket di MinIO Console (http://localhost:9001 untuk local)

4. Gunakan di code:
```php
use Illuminate\Support\Facades\Storage;

// Upload
Storage::disk('s3')->put('path/file.jpg', $contents);

// Get URL
$url = Storage::disk('s3')->url('path/file.jpg');

// Download
$contents = Storage::disk('s3')->get('path/file.jpg');
```

## Cek Status

```bash
# Cek container berjalan
docker-compose ps

# Cek log
docker-compose logs -f app

# Cek PHP extensions
docker-compose exec app php -m | grep sqlsrv
docker-compose exec app php -m | grep pdo_sqlsrv
```

## Troubleshooting

### Container tidak jalan
```bash
docker-compose logs app
docker-compose logs nginx
```

### Permission error
```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www:www storage bootstrap/cache
```

### Port conflict
Edit `docker-compose.yml`, ubah port 8000 ke port lain

### Database connection error
- Pastikan kredensial di `.env` benar
- Pastikan network/firewall mengizinkan koneksi
- Untuk SQL Server, pastikan extension terinstall

