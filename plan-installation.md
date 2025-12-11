# 📋 Plan Installation - Garuda Test Application

**Repository**: https://github.com/andri5/garudatest.git

---

## 🎯 Tujuan / Objective

Dokumen ini berisi panduan lengkap instalasi aplikasi **Garuda Test** yang terdiri dari:
- **Backend**: Laravel 12 dengan PHP 8.4
- **Frontend**: Laravel dengan Vite + Tailwind CSS
- **Database**: MySQL (default) + 2 SQL Server
- **Storage**: MinIO (S3-compatible)

---

## 📦 Prerequisites / Persyaratan Sistem

### **1. Software yang Harus Di-Install**

#### **A. Docker Desktop (Wajib)**
- **Windows**: Download dari https://www.docker.com/products/docker-desktop/
- **Linux**: Install Docker Engine + Docker Compose
- **Minimum Version**: Docker 20.10+, Docker Compose 2.0+

#### **B. WSL2 (Hanya untuk Windows)**
- WSL2 diperlukan untuk Docker Desktop di Windows
- Install via PowerShell:
  ```powershell
  wsl --install
  ```
- Restart komputer setelah install

#### **C. Git (Opsional, untuk clone repository)**
- Download dari: https://git-scm.com/downloads
- Atau gunakan GitHub Desktop

---

### **2. System Requirements**

#### **Minimum Requirements:**
- **RAM**: 4 GB (8 GB recommended)
- **Disk Space**: 10 GB free space
- **OS**: 
  - Windows 10/11 (64-bit)
  - Linux (Ubuntu 20.04+, Debian 11+, CentOS 8+)
  - macOS 10.15+

#### **Network Requirements:**
- Koneksi internet untuk download Docker images
- Akses ke database staging (MySQL + 2 SQL Server)
- Akses ke MinIO endpoint (staging)

---

### **3. Database Requirements**

Aplikasi ini memerlukan **3 database**:

#### **A. MySQL Database (Default)**
- **Host**: External staging server
- **Port**: 3306 (default)
- **Database**: Nama database yang sudah dibuat
- **User**: Username dengan akses ke database
- **Password**: Password untuk user tersebut

#### **B. SQL Server 1**
- **Host**: External staging server
- **Port**: 1433 (default)
- **Database**: Nama database yang sudah dibuat
- **User**: Username dengan akses ke database
- **Password**: Password untuk user tersebut

#### **C. SQL Server 2**
- **Host**: External staging server (bisa berbeda dari SQL Server 1)
- **Port**: 1433 (default)
- **Database**: Nama database yang sudah dibuat
- **User**: Username dengan akses ke database
- **Password**: Password untuk user tersebut

**Catatan**: Pastikan semua database sudah dibuat dan user memiliki akses yang diperlukan.

---

### **4. MinIO / S3 Storage Requirements**

- **Endpoint**: URL MinIO staging (contoh: `https://minio-dev.kemenkeu.go.id`)
- **Access Key**: Access key untuk MinIO
- **Secret Key**: Secret key untuk MinIO
- **Bucket**: Nama bucket yang sudah dibuat (contoh: `lpdp-beasiswa-dev`)

---

## 🔧 Environment Setup

### **1. Clone Repository (Jika Belum)**

```bash
# Clone repository
git clone https://github.com/andri5/garudatest.git

# Masuk ke folder project
cd garudatest
```

---

### **2. Setup Environment File**

#### **A. Backend Environment**

```bash
# Masuk ke folder backend
cd backend

# Copy env.example ke .env
cp env.example .env
# atau di Windows PowerShell:
# Copy-Item env.example .env
```

#### **B. Frontend Environment**

```bash
# Masuk ke folder frontend
cd ../frontend

# Copy env.example ke .env
cp env.example .env
# atau di Windows PowerShell:
# Copy-Item env.example .env
```

---

### **3. Konfigurasi Environment Variables**

#### **A. Backend `.env` Configuration**

Edit file `backend/.env` dan isi dengan konfigurasi berikut:

```env
# ============================================
# APPLICATION CONFIGURATION
# ============================================
APP_NAME=GarudaTest
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost:8000

# ============================================
# LOGGING CONFIGURATION
# ============================================
LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# ============================================
# DATABASE CONFIGURATION - MySQL (Default)
# ============================================
DB_CONNECTION=mysql
DB_HOST=your_mysql_host
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

# ============================================
# DATABASE CONFIGURATION - SQL Server 1
# ============================================
SQLSRV_CONNECTION=sqlsrv
SQLSRV_HOST=your_sqlserver_host
SQLSRV_PORT=1433
SQLSRV_DATABASE=your_database_name
SQLSRV_USERNAME=your_username
SQLSRV_PASSWORD=your_password

# ============================================
# DATABASE CONFIGURATION - SQL Server 2
# ============================================
SQLSRV2_CONNECTION=sqlsrv2
SQLSRV2_HOST=your_sqlserver2_host
SQLSRV2_PORT=1433
SQLSRV2_DATABASE=your_database2_name
SQLSRV2_USERNAME=your_username2
SQLSRV2_PASSWORD=your_password2

# ============================================
# MINIO / S3 STORAGE CONFIGURATION
# ============================================
FILESYSTEM_DRIVER=s3
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your_bucket_name
AWS_ENDPOINT=https://your_minio_endpoint
AWS_USE_PATH_STYLE_ENDPOINT=true
AWS_URL=https://your_minio_endpoint/your_bucket_name

# ============================================
# CACHE & SESSION CONFIGURATION
# ============================================
BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# ============================================
# REDIS CONFIGURATION (Optional)
# ============================================
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# ============================================
# MAIL CONFIGURATION
# ============================================
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Catatan Penting**:
- Ganti semua `your_*` dengan nilai yang sesuai
- Pastikan kredensial database benar dan memiliki akses
- Pastikan MinIO endpoint dan bucket sudah dibuat

#### **B. Frontend `.env` Configuration**

Frontend menggunakan konfigurasi yang sama dengan backend. Copy konfigurasi dari `backend/.env` ke `frontend/.env` atau sesuaikan dengan kebutuhan.

---

## 🗄️ Database Configuration

### **1. Setup Multiple Database Connection**

Aplikasi ini menggunakan **3 database sekaligus**:
- **1 MySQL** (default connection)
- **2 SQL Server** (sqlsrv dan sqlsrv2)

#### **Step 1: Edit `config/database.php`**

Buka file `backend/config/database.php` dan tambahkan konfigurasi SQL Server di dalam array `'connections'`:

```php
<?php

use Illuminate\Support\Str;

return [
    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [
        // ... konfigurasi yang sudah ada ...

        // MySQL - Default Connection (sudah ada)
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],

        // SQL Server 1 - Tambahkan ini
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

        // SQL Server 2 - Tambahkan ini
        'sqlsrv2' => [
            'driver' => 'sqlsrv',
            'host' => env('SQLSRV2_HOST', 'localhost'),
            'port' => env('SQLSRV2_PORT', '1433'),
            'database' => env('SQLSRV2_DATABASE', 'forge'),
            'username' => env('SQLSRV2_USERNAME', 'forge'),
            'password' => env('SQLSRV2_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'encrypt' => env('DB_ENCRYPT', 'yes'),
            'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
        ],
    ],
];
```

**Alternatif**: Copy konfigurasi dari file `backend/database-sqlsrv-config.php` dan paste ke `config/database.php`.

#### **Step 2: Verifikasi Konfigurasi**

Pastikan semua environment variables sudah di-set di file `.env`:
- ✅ `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` (MySQL)
- ✅ `SQLSRV_HOST`, `SQLSRV_DATABASE`, `SQLSRV_USERNAME`, `SQLSRV_PASSWORD` (SQL Server 1)
- ✅ `SQLSRV2_HOST`, `SQLSRV2_DATABASE`, `SQLSRV2_USERNAME`, `SQLSRV2_PASSWORD` (SQL Server 2)

---

## 🚀 Step-by-Step Installation Guide

### **Installation untuk Windows**

#### **Step 1: Install Docker Desktop**

1. Download Docker Desktop dari: https://www.docker.com/products/docker-desktop/
2. Install Docker Desktop
3. Pastikan WSL2 sudah terinstall:
   ```powershell
   wsl --install
   ```
4. **Restart komputer** setelah install WSL2
5. Buka Docker Desktop dan pastikan status "Running"

#### **Step 2: Clone dan Setup Project**

```powershell
# Clone repository (jika belum)
git clone https://github.com/andri5/garudatest.git
cd garudatest

# Masuk ke folder backend
cd backend
```

#### **Step 3: Setup Environment File**

```powershell
# Copy env.example ke .env
Copy-Item env.example .env

# Edit .env dengan text editor (Notepad, VS Code, dll)
# Isi dengan konfigurasi database dan MinIO
```

#### **Step 4: Build dan Jalankan Docker Container**

```powershell
# Build dan jalankan container
docker-compose up -d --build

# Tunggu hingga semua container running
# Cek status:
docker-compose ps
```

**Output yang diharapkan**:
```
NAME              STATUS
super-app-php    Up
super-app-nginx  Up
```

#### **Step 5: Install Laravel Dependencies**

```powershell
# Install Composer dependencies
docker-compose exec app composer install

# Generate application key
docker-compose exec app php artisan key:generate

# Setup storage link
docker-compose exec app php artisan storage:link

# Set permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

#### **Step 6: Setup Database Configuration**

1. Edit `backend/config/database.php`
2. Tambahkan konfigurasi SQL Server (lihat section "Database Configuration" di atas)

#### **Step 7: Install Frontend Dependencies**

```powershell
# Install npm dependencies
docker-compose exec app npm install

# Build frontend assets
docker-compose exec app npm run build
```

#### **Step 8: Test Database Connection**

```powershell
# Test MySQL connection
docker-compose exec app php artisan tinker
# Di dalam tinker:
DB::connection('mysql')->select('SELECT 1');

# Test SQL Server 1 connection
DB::connection('sqlsrv')->select('SELECT 1');

# Test SQL Server 2 connection
DB::connection('sqlsrv2')->select('SELECT 1');
```

#### **Step 9: Run Database Migrations (Jika Ada)**

```powershell
# Run migrations
docker-compose exec app php artisan migrate

# Atau dengan force (jika di production)
docker-compose exec app php artisan migrate --force
```

#### **Step 10: Akses Aplikasi**

- **Backend API**: http://localhost:8000
- **MinIO Console** (jika lokal): http://localhost:9001

---

### **Installation untuk Linux**

#### **Step 1: Install Docker**

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

#### **Step 2: Clone dan Setup Project**

```bash
# Clone repository (jika belum)
git clone https://github.com/andri5/garudatest.git
cd garudatest/backend
```

#### **Step 3: Setup Environment File**

```bash
# Copy env.example ke .env
cp env.example .env

# Edit .env dengan text editor
nano .env
# atau
vim .env
```

#### **Step 4: Build dan Jalankan Docker Container**

```bash
# Build dan jalankan container
docker-compose up -d --build

# Cek status
docker-compose ps
```

#### **Step 5: Install Laravel Dependencies**

```bash
# Install Composer dependencies
docker-compose exec app composer install

# Generate application key
docker-compose exec app php artisan key:generate

# Setup storage link
docker-compose exec app php artisan storage:link

# Set permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www:www storage bootstrap/cache
```

#### **Step 6: Setup Database Configuration**

1. Edit `backend/config/database.php`
2. Tambahkan konfigurasi SQL Server

#### **Step 7: Install Frontend Dependencies**

```bash
# Install npm dependencies
docker-compose exec app npm install

# Build frontend assets
docker-compose exec app npm run build
```

#### **Step 8: Test Database Connection**

```bash
# Test connections
docker-compose exec app php artisan tinker
# Di dalam tinker:
DB::connection('mysql')->select('SELECT 1');
DB::connection('sqlsrv')->select('SELECT 1');
DB::connection('sqlsrv2')->select('SELECT 1');
```

#### **Step 9: Run Database Migrations**

```bash
# Run migrations
docker-compose exec app php artisan migrate --force
```

#### **Step 10: Production Optimization (Optional)**

```bash
# Cache config untuk production
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

#### **Step 11: Akses Aplikasi**

- **Backend API**: http://localhost:8000
- **MinIO Console** (jika lokal): http://localhost:9001

---

## ✅ Verification Steps

### **1. Cek Docker Container Status**

```bash
docker-compose ps
```

**Output yang diharapkan**:
```
NAME              STATUS          PORTS
super-app-php    Up (healthy)    
super-app-nginx  Up              0.0.0.0:8000->80/tcp
```

### **2. Cek PHP Extensions**

```bash
# Cek SQL Server extensions
docker-compose exec app php -m | grep sqlsrv
docker-compose exec app php -m | grep pdo_sqlsrv

# Cek MySQL extension
docker-compose exec app php -m | grep pdo_mysql

# Cek semua extensions
docker-compose exec app php -m
```

**Extensions yang harus ada**:
- ✅ `pdo_mysql`
- ✅ `sqlsrv`
- ✅ `pdo_sqlsrv`
- ✅ `imagick`
- ✅ `gd`
- ✅ `zip`
- ✅ `intl`

### **3. Test Database Connections**

```bash
# Test MySQL
docker-compose exec app php artisan tinker
DB::connection('mysql')->select('SELECT 1 as test');

# Test SQL Server 1
DB::connection('sqlsrv')->select('SELECT 1 as test');

# Test SQL Server 2
DB::connection('sqlsrv2')->select('SELECT 1 as test');
```

### **4. Test Application**

```bash
# Test route
curl http://localhost:8000

# Atau buka di browser
# http://localhost:8000
```

### **5. Cek Logs**

```bash
# Cek application logs
docker-compose exec app tail -f storage/logs/laravel.log

# Cek container logs
docker-compose logs -f app
docker-compose logs -f nginx
```

---

## 🔍 Troubleshooting

### **1. Docker Container Tidak Jalan**

**Masalah**: Container tidak start atau langsung stop

**Solusi**:
```bash
# Cek logs
docker-compose logs app

# Rebuild container
docker-compose down
docker-compose up -d --build

# Cek disk space
df -h
```

### **2. Port 8000 Sudah Digunakan**

**Masalah**: Error "port already in use"

**Solusi**:
1. Edit `docker-compose.yml`
2. Ubah port:
   ```yaml
   ports:
     - "8001:80"  # Ubah dari 8000 ke 8001
   ```
3. Restart container:
   ```bash
   docker-compose down
   docker-compose up -d
   ```

### **3. Database Connection Error**

**Masalah**: Tidak bisa connect ke database

**Solusi**:
```bash
# Cek kredensial di .env
cat .env | grep DB_

# Test connection manual
docker-compose exec app php artisan tinker
DB::connection('mysql')->select('SELECT 1');

# Cek network/firewall
# Pastikan server database bisa diakses dari Docker container
```

### **4. SQL Server Extension Tidak Terinstall**

**Masalah**: Error "Driver not found" untuk SQL Server

**Solusi**:
```bash
# Cek extension terinstall
docker-compose exec app php -m | grep sqlsrv

# Jika tidak ada, rebuild container
docker-compose down
docker-compose up -d --build

# Cek Dockerfile sudah include SQL Server driver
```

### **5. Permission Error**

**Masalah**: Error "Permission denied" pada storage atau cache

**Solusi**:
```bash
# Set permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www:www storage bootstrap/cache

# Di Linux, mungkin perlu:
sudo chown -R $USER:$USER storage bootstrap/cache
```

### **6. MinIO Connection Error**

**Masalah**: Tidak bisa connect ke MinIO

**Solusi**:
```bash
# Cek konfigurasi di .env
cat .env | grep AWS_

# Test connection
docker-compose exec app php artisan tinker
Storage::disk('s3')->exists('test.txt');

# Untuk local MinIO:
# Pastikan MinIO container running
docker-compose ps | grep minio
```

### **7. Composer Install Error**

**Masalah**: Error saat `composer install`

**Solusi**:
```bash
# Clear cache
docker-compose exec app composer clear-cache

# Install ulang
docker-compose exec app composer install --no-cache

# Cek memory limit
docker-compose exec app php -i | grep memory_limit
```

### **8. NPM Install Error**

**Masalah**: Error saat `npm install`

**Solusi**:
```bash
# Clear npm cache
docker-compose exec app npm cache clean --force

# Install ulang
docker-compose exec app npm install

# Cek Node.js version
docker-compose exec app node --version
```

---

## 📋 Checklist Installation

Gunakan checklist ini untuk memastikan semua langkah sudah dilakukan:

### **Prerequisites**
- [ ] Docker Desktop terinstall dan running
- [ ] WSL2 terinstall (Windows)
- [ ] Git terinstall (opsional)
- [ ] Koneksi internet tersedia

### **Environment Setup**
- [ ] Repository sudah di-clone
- [ ] File `.env` sudah dibuat dari `env.example`
- [ ] Konfigurasi database MySQL sudah diisi
- [ ] Konfigurasi database SQL Server 1 sudah diisi
- [ ] Konfigurasi database SQL Server 2 sudah diisi
- [ ] Konfigurasi MinIO sudah diisi

### **Database Configuration**
- [ ] File `config/database.php` sudah di-edit
- [ ] Konfigurasi SQL Server sudah ditambahkan
- [ ] Semua environment variables sudah benar

### **Docker Setup**
- [ ] Docker container sudah di-build
- [ ] Semua container running (php, nginx)
- [ ] Port 8000 tidak conflict

### **Laravel Setup**
- [ ] Composer dependencies terinstall
- [ ] Application key sudah di-generate
- [ ] Storage link sudah dibuat
- [ ] Permissions sudah di-set

### **Frontend Setup**
- [ ] NPM dependencies terinstall
- [ ] Frontend assets sudah di-build

### **Database Connection**
- [ ] MySQL connection berhasil
- [ ] SQL Server 1 connection berhasil
- [ ] SQL Server 2 connection berhasil

### **Verification**
- [ ] PHP extensions terinstall (sqlsrv, pdo_mysql, dll)
- [ ] Application bisa diakses di http://localhost:8000
- [ ] Logs tidak ada error

---

## 🔄 Post-Installation

### **1. Development Mode**

Untuk development, jalankan:

```bash
# Start development server dengan hot reload
docker-compose exec app composer run dev

# Atau manual:
docker-compose exec app php artisan serve
docker-compose exec app npm run dev
```

### **2. Production Mode**

Untuk production:

```bash
# Cache config
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# Build frontend
docker-compose exec app npm run build

# Set APP_ENV=production di .env
# Set APP_DEBUG=false di .env
```

### **3. Useful Commands**

```bash
# Restart container
docker-compose restart

# Stop container
docker-compose down

# View logs
docker-compose logs -f app

# Masuk ke container
docker-compose exec app bash

# Run artisan command
docker-compose exec app php artisan [command]

# Run composer command
docker-compose exec app composer [command]

# Run npm command
docker-compose exec app npm [command]
```

---

## 📚 Referensi

- [Laravel Documentation](https://laravel.com/docs)
- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [SQL Server PHP Driver](https://github.com/Microsoft/msphpsql)

---

## 💡 Tips

1. **Gunakan script install**: File `install-laravel.sh` atau `install-laravel.bat` bisa memudahkan instalasi
2. **Backup .env**: Simpan backup file `.env` di tempat aman
3. **Monitor logs**: Selalu cek logs jika ada masalah
4. **Update Docker images**: Update Docker images secara berkala untuk security patches

---

**Selamat! Aplikasi sudah siap digunakan! 🎉**

**Congratulations! Application is ready to use! 🎉**


