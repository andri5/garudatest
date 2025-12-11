# 🔌 Database Connection Setup - Garuda Test Application

**Status Instalasi**: ✅ Dasar instalasi selesai  
**Tanggal**: 11 Desember 2025  
**File**: `db-connection.md`

---

## ✅ Yang Sudah Selesai

- [x] Environment files (.env) sudah dibuat untuk backend dan frontend
- [x] Konfigurasi database (sqlsrv dan sqlsrv2) sudah ditambahkan ke `config/database.php`
- [x] Docker containers sudah running (super-app-php, super-app-nginx)
- [x] Composer dependencies terinstall
- [x] Application key sudah di-generate
- [x] Storage link sudah dibuat
- [x] NPM dependencies terinstall
- [x] Frontend assets sudah di-build
- [x] Command artisan `db:test` sudah dibuat
- [x] Dokumentasi API sudah dibuat
- [x] Aplikasi bisa diakses di http://localhost:8000
- [x] Git commits dan push ke GitHub sudah dilakukan

---

## 📋 Langkah Selanjutnya (Prioritas)

### 🔴 **PRIORITAS TINGGI - Wajib Dilakukan**

#### 1. Konfigurasi Database Credentials ⚠️

**Status**: ⏳ **BELUM DILAKUKAN** - Kredensial masih menggunakan placeholder

**📄 Panduan Lengkap**: Lihat `backend/DATABASE-CONFIG-GUIDE.md`

**Metode 1: Menggunakan Script PowerShell (Recommended)**
```powershell
cd backend
.\setup-database.ps1
```
Script akan memandu Anda mengisi kredensial secara interaktif dengan password yang disembunyikan.

**Metode 2: Manual Edit**
1. Edit file `backend/.env` dan `frontend/.env`
2. Ganti semua placeholder dengan kredensial database yang sebenarnya:

```env
# Database MySQL (Default)
DB_CONNECTION=mysql
DB_HOST=your_actual_mysql_host          # ← GANTI INI
DB_PORT=3306
DB_DATABASE=your_actual_database_name    # ← GANTI INI
DB_USERNAME=your_actual_username        # ← GANTI INI
DB_PASSWORD=your_actual_password        # ← GANTI INI

# Database SQL Server 1
SQLSRV_HOST=your_actual_sqlserver_host  # ← GANTI INI
SQLSRV_PORT=1433
SQLSRV_DATABASE=your_actual_database_name # ← GANTI INI
SQLSRV_USERNAME=your_actual_username    # ← GANTI INI
SQLSRV_PASSWORD=your_actual_password    # ← GANTI INI

# Database SQL Server 2
SQLSRV2_HOST=your_actual_sqlserver2_host # ← GANTI INI
SQLSRV2_PORT=1433
SQLSRV2_DATABASE=your_actual_database2_name # ← GANTI INI
SQLSRV2_USERNAME=your_actual_username2   # ← GANTI INI
SQLSRV2_PASSWORD=your_actual_password2  # ← GANTI INI
```

**Catatan**: 
- Pastikan semua database sudah dibuat di server
- Pastikan user memiliki akses yang diperlukan
- Pastikan server database bisa diakses dari Docker container

---

#### 2. Test Database Connections ✅

**Setelah kredensial diisi**, test koneksi database:

```powershell
# Test semua koneksi
docker-compose exec app php artisan db:test

# Atau test satu per satu
docker-compose exec app php artisan db:test --connection=mysql
docker-compose exec app php artisan db:test --connection=sqlsrv
docker-compose exec app php artisan db:test --connection=sqlsrv2
```

**Output yang diharapkan**:
```
✅ mysql - Connection successful
✅ sqlsrv - Connection successful
✅ sqlsrv2 - Connection successful
```

---

#### 3. Run Database Migrations 📊

**Setelah koneksi database berhasil**, jalankan migrations:

```powershell
# Cek status migrations
docker-compose exec app php artisan migrate:status

# Run migrations
docker-compose exec app php artisan migrate

# Atau dengan force (jika di production)
docker-compose exec app php artisan migrate --force
```

**Migrations yang tersedia**:
- `0001_01_01_000000_create_users_table.php`
- `0001_01_01_000001_create_cache_table.php`
- `0001_01_01_000002_create_jobs_table.php`

---

### 🟡 **PRIORITAS SEDANG - Disarankan**

#### 4. Konfigurasi MinIO / S3 Storage 📁

**Jika menggunakan MinIO**, pastikan konfigurasi di `.env` sudah benar:

```env
FILESYSTEM_DRIVER=s3
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your_bucket_name
AWS_ENDPOINT=https://your_minio_endpoint
AWS_USE_PATH_STYLE_ENDPOINT=true
AWS_URL=https://your_minio_endpoint/your_bucket_name
```

**Test koneksi MinIO**:
```powershell
docker-compose exec app php artisan tinker
```

Di dalam tinker:
```php
Storage::disk('s3')->exists('test.txt');
```

---

#### 5. Setup Frontend (Jika Diperlukan) 🎨

**Jika frontend perlu setup terpisah**:

```powershell
cd ../frontend

# Install dependencies (jika belum)
docker-compose exec app npm install

# Build assets
docker-compose exec app npm run build

# Atau untuk development dengan hot reload
docker-compose exec app npm run dev
```

---

#### 6. Testing API Endpoints 🧪

**Test API endpoints yang tersedia**:

```powershell
# Test login endpoint
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"test","password":"test"}'

# Test example endpoints
curl http://localhost:8000/api/v1/examples/success
curl http://localhost:8000/api/v1/examples/not-found
```

**Atau gunakan Postman/Insomnia** untuk testing yang lebih mudah.

**Dokumentasi API lengkap**: Lihat `backend/API-DOCUMENTATION.md`

---

### 🟢 **PRIORITAS RENDAH - Opsional**

#### 7. Production Optimization 🚀

**Jika untuk production**, lakukan optimasi:

```powershell
# Cache config
docker-compose exec app php artisan config:cache

# Cache routes
docker-compose exec app php artisan route:cache

# Cache views
docker-compose exec app php artisan view:cache

# Optimize autoloader
docker-compose exec app composer install --optimize-autoloader --no-dev
```

**Juga update `.env`**:
```env
APP_ENV=production
APP_DEBUG=false
```

---

#### 8. Setup Development Tools 🛠️

**Untuk development yang lebih nyaman**:

```powershell
# Install development dependencies
docker-compose exec app composer require --dev laravel/pint
docker-compose exec app composer require --dev phpunit/phpunit

# Setup code formatting
docker-compose exec app ./vendor/bin/pint
```

---

#### 9. Setup CI/CD (Opsional) 🔄

**Jika ingin setup CI/CD**:
- GitHub Actions
- GitLab CI
- Jenkins

**Contoh GitHub Actions** (buat file `.github/workflows/laravel.yml`):
```yaml
name: Laravel CI

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.4'
      - name: Install Dependencies
        run: composer install
      - name: Run Tests
        run: php artisan test
```

---

## 📊 Checklist Progress

### Instalasi Dasar
- [x] Environment files dibuat
- [x] Database configuration setup
- [x] Docker containers running
- [x] Dependencies terinstall
- [x] Application key generated
- [x] Storage link dibuat
- [x] Frontend assets built

### Konfigurasi Database
- [ ] Database credentials diisi di `.env`
- [ ] Test koneksi MySQL berhasil
- [ ] Test koneksi SQL Server 1 berhasil
- [ ] Test koneksi SQL Server 2 berhasil
- [ ] Migrations dijalankan

### Testing & Verification
- [ ] API endpoints ditest
- [ ] Database connections verified
- [ ] File upload functionality ditest (jika menggunakan MinIO)
- [ ] Authentication flow ditest

### Production (Jika Diperlukan)
- [ ] Environment variables untuk production di-set
- [ ] Config cached
- [ ] Routes cached
- [ ] Views cached
- [ ] Security hardening dilakukan

---

## 🔍 Troubleshooting

### Database Connection Error

**Masalah**: Tidak bisa connect ke database

**Solusi**:
1. Cek kredensial di `.env` sudah benar
2. Cek server database bisa diakses dari Docker container
3. Cek firewall/network settings
4. Test dengan command: `docker-compose exec app php artisan db:test`

### Migration Error

**Masalah**: Error saat run migrations

**Solusi**:
1. Pastikan koneksi database berhasil
2. Cek database sudah dibuat
3. Cek user memiliki permission untuk create tables
4. Gunakan `--force` flag jika di production

### API Not Working

**Masalah**: API endpoint tidak berfungsi

**Solusi**:
1. Cek container running: `docker-compose ps`
2. Cek logs: `docker-compose logs app`
3. Cek routes: `docker-compose exec app php artisan route:list`
4. Test dengan curl atau Postman

---

## 📚 Dokumentasi Terkait

- **NEXT-STEPS.md** (`backend/NEXT-STEPS.md`) - Panduan detail langkah selanjutnya
- **API-DOCUMENTATION.md** (`backend/API-DOCUMENTATION.md`) - Dokumentasi API lengkap
- **plan-installation.md** - Dokumentasi instalasi lengkap
- **plan-git.md** - Dokumentasi Git workflow

---

## 🎯 Quick Start Commands

```powershell
# 1. Edit .env dengan kredensial database
notepad backend/.env

# 2. Test koneksi database
docker-compose exec app php artisan db:test

# 3. Run migrations
docker-compose exec app php artisan migrate

# 4. Test API
curl http://localhost:8000/api/v1/examples/success

# 5. Cek logs
docker-compose logs -f app
```

---

## 💡 Tips

1. **Backup .env**: Simpan backup file `.env` di tempat aman
2. **Monitor Logs**: Selalu cek logs jika ada masalah
3. **Test Incrementally**: Test satu per satu setelah setiap perubahan
4. **Document Changes**: Dokumentasikan perubahan yang dilakukan
5. **Version Control**: Commit perubahan ke Git secara berkala

---

## 🎉 Status Akhir

**Instalasi Dasar**: ✅ **SELESAI**  
**Konfigurasi Database**: ⏳ **PENDING** (Perlu kredensial dari user)  
**Testing**: ⏳ **PENDING** (Setelah database dikonfigurasi)  
**Production Ready**: ⏳ **PENDING** (Setelah semua testing selesai)

---

**Selamat! Aplikasi sudah siap untuk dikonfigurasi lebih lanjut! 🚀**

