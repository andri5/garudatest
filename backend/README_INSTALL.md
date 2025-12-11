# Super App - Laravel 12 API dengan Docker

Setup Docker untuk Laravel 12 API dengan PHP 8.3, mendukung **3 database sekaligus** (2 SQL Server + 1 MySQL), dan MinIO untuk storage.

## Persyaratan

### Windows (Local Development)
- Docker Desktop untuk Windows
- WSL2 (Windows Subsystem for Linux 2)
- Git for Windows

### Linux (Staging Server)
- Docker Engine 20.10+
- Docker Compose 2.0+
- Git

## Instalasi

### 1. Persiapan Awal

#### Windows (Local)
1. Install Docker Desktop dari [https://www.docker.com/products/docker-desktop/](https://www.docker.com/products/docker-desktop/)
2. Pastikan WSL2 sudah diaktifkan:
   ```powershell
   wsl --install
   ```
3. Restart komputer setelah instalasi WSL2
4. Buka Docker Desktop dan pastikan status "Running"

#### Linux (Staging)
1. Install Docker Engine:
   ```bash
   curl -fsSL https://get.docker.com -o get-docker.sh
   sudo sh get-docker.sh
   ```
2. Install Docker Compose:
   ```bash
   sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
   sudo chmod +x /usr/local/bin/docker-compose
   ```
3. Tambahkan user ke grup docker:
   ```bash
   sudo usermod -aG docker $USER
   ```
4. Logout dan login kembali

### 2. Setup Docker dan Install Laravel

**PENTING**: Semua proses dilakukan di dalam Docker container! Tidak perlu install PHP/Composer di host machine Anda.

#### Windows (WSL2 Terminal atau PowerShell)
```bash
# Buka WSL2 terminal atau PowerShell
cd C:\kerjaan\super-app

# 1. Build dan jalankan Docker container terlebih dahulu
docker-compose up -d --build

# 2. Install Laravel di dalam Docker container (jika belum ada project Laravel)
# Opsi A: Menggunakan script (lebih mudah)
bash install-laravel.sh

cd C:\kerjaan\super-app; docker-compose exec app composer create-project laravel/laravel:^12.0 temp-laravel --prefer-dist --no-interaction

# Memindahkan file Laravel dari temp-laravel ke root, melewati file Docker yang sudah ada:
cd C:\kerjaan\super-app; docker-compose exec app sh -c "cd temp-laravel && find . -maxdepth 1 -type f -exec cp {} .. \; && find . -maxdepth 1 -type d ! -name '.' ! -name '..' -exec cp -r {} .. \;"

cd C:\kerjaan\super-app; docker-compose exec app php artisan storage:link

# atau di PowerShell:
# .\install-laravel.bat

# Opsi B: Manual
docker-compose exec app composer create-project laravel/laravel:^12.0 . --prefer-dist --no-interaction
docker-compose exec app cp .env.example .env
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan storage:link
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

#### Linux
```bash
cd /path/to/super-app

# 1. Build dan jalankan Docker container terlebih dahulu
docker-compose up -d --build

# 2. Install Laravel di dalam Docker container (jika belum ada project Laravel)
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

### 3. Setup File Konfigurasi

1. Copy file `env.example` menjadi `.env` (jika belum ada):
   ```bash
   cp env.example .env
   ```
   
   **Catatan**: Jika sudah install Laravel via script, file `.env` sudah dibuat otomatis.

2. Edit file `.env` dan sesuaikan konfigurasi:
   - **Database MySQL**: Isi dengan kredensial database staging MySQL (default connection)
   - **Database SQL Server 1**: Isi dengan kredensial database staging SQL Server pertama
   - **Database SQL Server 2**: Isi dengan kredensial database staging SQL Server kedua
   - **MinIO**: Isi dengan endpoint dan kredensial MinIO staging

   Contoh untuk local development dengan MinIO di Docker:
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

   FILESYSTEM_DRIVER=s3
   AWS_ACCESS_KEY_ID=minioadmin
   AWS_SECRET_ACCESS_KEY=minioadmin
   AWS_DEFAULT_REGION=us-east-1
   AWS_BUCKET=super-app-storage
   AWS_ENDPOINT=http://minio:9000
   AWS_USE_PATH_STYLE_ENDPOINT=true
   ```

### 4. Setup Database Configuration

Setelah Laravel terinstall, edit file `config/database.php` dan tambahkan konfigurasi dari file `database-sqlsrv-config.php` untuk setup multiple database (MySQL + 2 SQL Server).

Lihat file `MULTIPLE-DATABASE.md` untuk panduan lengkap.

### 6. Setup MinIO (Jika menggunakan MinIO lokal)

Jika menggunakan MinIO di Docker untuk development:

1. Akses MinIO Console di browser: `http://localhost:9001`
2. Login dengan:
   - Username: `minioadmin`
   - Password: `minioadmin`
3. Buat bucket baru dengan nama sesuai `AWS_BUCKET` di `.env`
4. Set bucket policy menjadi public atau sesuai kebutuhan

### 6. Verifikasi Instalasi

1. Akses aplikasi di browser: `http://localhost:8000`
2. Cek status container:
   ```bash
   docker-compose ps
   ```
3. Cek log jika ada masalah:
   ```bash
   docker-compose logs app
   docker-compose logs nginx
   ```

## Konfigurasi Database

Aplikasi ini mendukung **3 database sekaligus**:
- **1 MySQL** (default connection)
- **2 SQL Server** (sqlsrv dan sqlsrv2)

### Setup Multiple Database

1. **Konfigurasi Environment Variables**
   
   Pastikan file `.env` sudah dikonfigurasi dengan semua database (lihat contoh di bagian Setup File Konfigurasi).

2. **Setup Database Configuration**
   
   Setelah Laravel terinstall, edit file `config/database.php` dan tambahkan konfigurasi dari file `database-sqlsrv-config.php` yang sudah disediakan.

   File `database-sqlsrv-config.php` berisi konfigurasi lengkap untuk:
   - MySQL (default)
   - SQL Server 1 (sqlsrv)
   - SQL Server 2 (sqlsrv2)

3. **Penggunaan di Code**

   ```php
   // Menggunakan MySQL (default)
   DB::table('users')->get();
   
   // Menggunakan SQL Server 1
   DB::connection('sqlsrv')->table('orders')->get();
   
   // Menggunakan SQL Server 2
   DB::connection('sqlsrv2')->table('products')->get();
   ```

**Lihat file `MULTIPLE-DATABASE.md` untuk dokumentasi lengkap penggunaan multiple database.**

## Konfigurasi MinIO (Storage)

### Setup di Laravel

File `config/filesystems.php` sudah dikonfigurasi untuk S3/MinIO. Pastikan `.env` sudah benar:

```env
FILESYSTEM_DRIVER=s3
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your_bucket_name
AWS_ENDPOINT=https://your_minio_endpoint
AWS_USE_PATH_STYLE_ENDPOINT=true
```

### Penggunaan di Code

```php
// Upload file
Storage::disk('s3')->put('path/to/file.jpg', $fileContents);

// Get URL
$url = Storage::disk('s3')->url('path/to/file.jpg');

// Download file
$contents = Storage::disk('s3')->get('path/to/file.jpg');
```

## API Configuration

Aplikasi ini dikonfigurasi sebagai API. Nginx sudah dikonfigurasi dengan CORS headers untuk mendukung frontend React.

### CORS Configuration

Jika perlu konfigurasi CORS lebih detail, edit file `config/cors.php`:

```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_methods' => ['*'],
'allowed_origins' => ['http://localhost:3000'], // URL frontend React
'allowed_origins_patterns' => [],
'allowed_headers' => ['*'],
'exposed_headers' => [],
'max_age' => 0,
'supports_credentials' => false,
```

## Perintah Docker yang Berguna

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

# Rebuild container setelah perubahan Dockerfile
docker-compose up -d --build

# Hapus semua container dan volume
docker-compose down -v
```

## Troubleshooting

### Port sudah digunakan
Jika port 8000 sudah digunakan, edit `docker-compose.yml`:
```yaml
ports:
  - "8001:80"  # Ubah 8000 menjadi port lain
```

### Permission denied di storage
```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www:www storage bootstrap/cache
```

### SQL Server connection error
Pastikan:
1. Extension sqlsrv sudah terinstall: `docker-compose exec app php -m | grep sqlsrv`
2. Kredensial database di `.env` sudah benar untuk semua database (MySQL, SQL Server 1, SQL Server 2)
3. Network/firewall mengizinkan koneksi ke database staging
4. Untuk test koneksi: `docker-compose exec app php artisan tinker` lalu test setiap connection

### MinIO connection error
Pastikan:
1. Endpoint MinIO di `.env` sudah benar
2. Bucket sudah dibuat di MinIO
3. Kredensial sudah benar
4. Untuk local development, gunakan `http://minio:9000` (nama service di docker-compose)

## Staging Deployment

### Persiapan Server Linux

1. Pastikan Docker dan Docker Compose sudah terinstall
2. Clone repository ke server
3. Copy `.env.example` menjadi `.env` dan isi dengan konfigurasi staging
4. Pastikan tidak menjalankan MinIO di Docker (gunakan external MinIO)
5. Build dan jalankan:
   ```bash
   docker-compose up -d --build
   ```

### Environment Variables untuk Staging

Pastikan `.env` di staging sudah dikonfigurasi dengan:
- Database MySQL staging (default)
- Database SQL Server 1 staging
- Database SQL Server 2 staging
- MinIO endpoint staging
- `APP_ENV=production`
- `APP_DEBUG=false`

## Struktur Folder

```
super-app/
├── docker/
│   ├── nginx/
│   │   └── conf.d/
│   │       └── app.conf
│   └── php/
│       └── local.ini
├── Dockerfile
├── docker-compose.yml
├── .env.example
├── .dockerignore
└── README.md
```

## Support

Jika ada masalah, cek:
1. Log container: `docker-compose logs`
2. Status container: `docker-compose ps`
3. Konfigurasi `.env`
4. Dokumentasi Laravel: https://laravel.com/docs/12.x

 │       └── app.conf
│   └── php/
│       └── local.ini
├── Dockerfile
├── docker-compose.yml
├── .env.example
├── .dockerignore
└── README.md
```

## Support

Jika ada masalah, cek:
1. Log container: `docker-compose logs`
2. Status container: `docker-compose ps`
3. Konfigurasi `.env`
4. Dokumentasi Laravel: https://laravel.com/docs/12.x

