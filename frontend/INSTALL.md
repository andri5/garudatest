# Panduan Instalasi Docker - Super App

## Instalasi di Windows (Local Development)

### Langkah 1: Install Docker Desktop
1. Download Docker Desktop dari: https://www.docker.com/products/docker-desktop/
2. Install Docker Desktop
3. Pastikan WSL2 sudah terinstall:
   ```powershell
   wsl --install
   ```
4. Restart komputer
5. Buka Docker Desktop dan pastikan status "Running"

### Langkah 2: Setup Project
1. Buka WSL2 terminal (Ubuntu) atau PowerShell
2. Navigasi ke folder project:
   ```bash
   cd C:\kerjaan\super-app
   ```
3. **Build dan jalankan Docker container terlebih dahulu**:
   ```bash
   docker-compose up -d --build
   ```
4. **Install Laravel di dalam Docker container** (jika belum ada project Laravel):
   ```bash
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
   ```

### Langkah 3: Konfigurasi Environment
1. Copy file `env.example` menjadi `.env` (jika belum ada):
   ```bash
   cp env.example .env
   ```
   **Catatan**: Jika sudah install Laravel via script, file `.env` sudah dibuat otomatis.
2. Edit file `.env` dan isi dengan konfigurasi database staging dan MinIO

### Langkah 4: Build dan Jalankan Docker
Jika belum dijalankan di langkah sebelumnya:
```bash
# Build dan jalankan container
docker-compose up -d --build

# Untuk development dengan MinIO lokal
docker-compose --profile local up -d --build
```

### Langkah 5: Setup Database Configuration
Edit file `config/database.php` dan tambahkan konfigurasi dari file `database-sqlsrv-config.php` untuk setup multiple database (MySQL + 2 SQL Server).

### Langkah 6: Akses Aplikasi
- API: http://localhost:8000
- MinIO Console (jika lokal): http://localhost:9001

---

## Instalasi di Linux (Staging Server)

### Langkah 1: Install Docker
```bash
# Install Docker Engine
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Tambahkan user ke grup docker
sudo usermod -aG docker $USER

# Logout dan login kembali
```

### Langkah 2: Setup Project
1. Clone atau copy project ke server
2. Navigasi ke folder project:
   ```bash
   cd /path/to/super-app
   ```
3. **Build dan jalankan Docker container terlebih dahulu**:
   ```bash
   docker-compose up -d --build
   ```
4. **Install Laravel di dalam Docker container** (jika belum ada project Laravel):
   ```bash
   # Opsi A: Menggunakan script (lebih mudah)
   bash install-laravel.sh
   
   # Opsi B: Manual
   docker-compose exec app composer create-project laravel/laravel:^12.0 . --prefer-dist --no-interaction
   docker-compose exec app cp .env.example .env
   docker-compose exec app php artisan key:generate
   docker-compose exec app php artisan storage:link
   docker-compose exec app chmod -R 775 storage bootstrap/cache
   docker-compose exec app chown -R www:www storage bootstrap/cache
   ```

### Langkah 3: Konfigurasi Environment
1. Copy file `env.example` menjadi `.env` (jika belum ada):
   ```bash
   cp env.example .env
   ```
   **Catatan**: Jika sudah install Laravel via script, file `.env` sudah dibuat otomatis.
2. Edit file `.env` dengan konfigurasi staging:
   ```bash
   nano .env
   ```
   
   Pastikan konfigurasi:
   - Database MySQL staging (default)
   - Database SQL Server 1 staging
   - Database SQL Server 2 staging
   - MinIO endpoint staging
   - `APP_ENV=production`
   - `APP_DEBUG=false`

### Langkah 4: Build dan Jalankan Docker
Jika belum dijalankan di langkah sebelumnya:
```bash
# Build dan jalankan (tanpa MinIO lokal)
docker-compose up -d --build
```

### Langkah 5: Setup Database Configuration
Edit file `config/database.php` dan tambahkan konfigurasi dari file `database-sqlsrv-config.php` untuk setup multiple database (MySQL + 2 SQL Server).

### Langkah 6: Setup Laravel (Production)
```bash
# Install dependencies (production)
docker-compose exec app composer install --no-dev --optimize-autoloader

# Cache config (production)
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

### Langkah 7: Setup Permissions
```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www:www storage bootstrap/cache
```

---

## Konfigurasi Database SQL Server

Setelah Laravel terinstall, edit `config/database.php` dan tambahkan:

```php
'sqlsrv' => [
    'driver' => 'sqlsrv',
    'host' => env('SQLSRV_HOST', 'localhost'),
    'port' => env('SQLSRV_PORT', '1433'),
    'database' => env('SQLSRV_DATABASE', 'forge'),
    'username' => env('SQLSRV_USERNAME', 'forge'),
    'password' => env('SQLSRV_PASSWORD', ''),
    'charset' => 'utf8',
    'prefix' => '',
    'prefix_indexes' => true,
    'encrypt' => env('DB_ENCRYPT', 'yes'),
    'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
],
```

---

## Perintah Berguna

```bash
# Start container
docker-compose up -d

# Stop container
docker-compose down

# Restart container
docker-compose restart

# View logs
docker-compose logs -f app
docker-compose logs -f nginx

# Masuk ke container
docker-compose exec app bash

# Rebuild setelah perubahan Dockerfile
docker-compose up -d --build

# Hapus semua (container + volume)
docker-compose down -v
```

---

## Troubleshooting

### Port sudah digunakan
Edit `docker-compose.yml`, ubah port:
```yaml
ports:
  - "8001:80"  # Ubah dari 8000
```

### Permission error
```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www:www storage bootstrap/cache
```

### SQL Server connection error
```bash
# Cek extension terinstall
docker-compose exec app php -m | grep sqlsrv

# Cek koneksi
docker-compose exec app php artisan tinker
# DB::connection('sqlsrv')->select('SELECT 1');
```

### MinIO connection error
- Pastikan endpoint di `.env` benar
- Pastikan bucket sudah dibuat
- Untuk local: gunakan `http://minio:9000`
- Untuk staging: gunakan endpoint external

