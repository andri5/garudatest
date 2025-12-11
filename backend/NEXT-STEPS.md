# 🚀 Langkah Selanjutnya Setelah Instalasi

Instalasi dasar sudah selesai! Berikut langkah-langkah yang perlu dilakukan selanjutnya:

## ✅ Yang Sudah Selesai

- [x] Environment files (.env) sudah dibuat
- [x] Konfigurasi database (sqlsrv dan sqlsrv2) sudah ditambahkan
- [x] Docker containers sudah running
- [x] Composer dependencies terinstall
- [x] Application key sudah di-generate
- [x] Storage link sudah dibuat
- [x] NPM dependencies terinstall
- [x] Frontend assets sudah di-build
- [x] Aplikasi bisa diakses di http://localhost:8000

## 📋 Langkah Selanjutnya

### 1. Konfigurasi Database Credentials

Edit file `backend/.env` dan `frontend/.env`, ganti placeholder dengan kredensial database yang sebenarnya:

```env
# Database MySQL (Default)
DB_CONNECTION=mysql
DB_HOST=your_actual_mysql_host
DB_PORT=3306
DB_DATABASE=your_actual_database_name
DB_USERNAME=your_actual_username
DB_PASSWORD=your_actual_password

# Database SQL Server 1
SQLSRV_CONNECTION=sqlsrv
SQLSRV_HOST=your_actual_sqlserver_host
SQLSRV_PORT=1433
SQLSRV_DATABASE=your_actual_database_name
SQLSRV_USERNAME=your_actual_username
SQLSRV_PASSWORD=your_actual_password

# Database SQL Server 2
SQLSRV2_CONNECTION=sqlsrv2
SQLSRV2_HOST=your_actual_sqlserver2_host
SQLSRV2_PORT=1433
SQLSRV2_DATABASE=your_actual_database2_name
SQLSRV2_USERNAME=your_actual_username2
SQLSRV2_PASSWORD=your_actual_password2
```

### 2. Test Database Connections

Setelah mengisi kredensial, test koneksi database:

```powershell
# Masuk ke container
docker-compose exec app bash

# Atau langsung test dari PowerShell
docker-compose exec app php artisan tinker
```

Di dalam tinker, jalankan:

```php
// Test MySQL connection
DB::connection('mysql')->select('SELECT 1 as test');

// Test SQL Server 1 connection
DB::connection('sqlsrv')->select('SELECT 1 as test');

// Test SQL Server 2 connection
DB::connection('sqlsrv2')->select('SELECT 1 as test');
```

Jika semua berhasil, akan muncul hasil `[{"test": 1}]`.

### 3. Run Database Migrations

Setelah koneksi database berhasil, jalankan migrations:

```powershell
# Cek status migrations
docker-compose exec app php artisan migrate:status

# Run migrations
docker-compose exec app php artisan migrate

# Atau dengan force (jika di production)
docker-compose exec app php artisan migrate --force
```

### 4. Konfigurasi MinIO (Jika Belum)

Pastikan konfigurasi MinIO di `.env` sudah benar:

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

Test koneksi MinIO:

```powershell
docker-compose exec app php artisan tinker
```

Di dalam tinker:

```php
Storage::disk('s3')->exists('test.txt');
```

### 5. Production Optimization (Optional)

Untuk production, cache config:

```powershell
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

### 6. Development Mode

Untuk development dengan hot reload:

```powershell
# Start Vite dev server
docker-compose exec app npm run dev

# Di terminal lain, start Laravel server (jika diperlukan)
docker-compose exec app php artisan serve
```

## 🔍 Troubleshooting

### Database Connection Error

Jika ada error koneksi database:

1. **Cek kredensial di .env** - Pastikan semua nilai sudah benar
2. **Cek network/firewall** - Pastikan server database bisa diakses dari Docker container
3. **Test koneksi manual** - Gunakan `php artisan tinker` untuk test

### SQL Server Connection Error

Jika SQL Server tidak bisa connect:

1. **Cek extension terinstall**: 
   ```powershell
   docker-compose exec app php -m | grep sqlsrv
   ```

2. **Cek trust_server_certificate**: Pastikan di `.env`:
   ```env
   DB_TRUST_SERVER_CERTIFICATE=true
   ```

3. **Rebuild container** jika perlu:
   ```powershell
   docker-compose down
   docker-compose up -d --build
   ```

## 📚 Useful Commands

```powershell
# Restart container
docker-compose restart

# Stop container
docker-compose down

# View logs
docker-compose logs -f app
docker-compose logs -f nginx

# Masuk ke container
docker-compose exec app bash

# Run artisan command
docker-compose exec app php artisan [command]

# Run composer command
docker-compose exec app composer [command]

# Run npm command
docker-compose exec app npm [command]
```

## 🎯 Checklist Final

- [ ] Kredensial database sudah diisi di `.env`
- [ ] Test koneksi MySQL berhasil
- [ ] Test koneksi SQL Server 1 berhasil
- [ ] Test koneksi SQL Server 2 berhasil
- [ ] Migrations sudah dijalankan
- [ ] MinIO configuration sudah benar (jika digunakan)
- [ ] Aplikasi bisa diakses di http://localhost:8000
- [ ] API routes berfungsi dengan baik

## 🎉 Selamat!

Setelah semua langkah selesai, aplikasi Garuda Test siap digunakan!

**Akses Aplikasi:**
- Backend API: http://localhost:8000
- API Routes: http://localhost:8000/api/v1/...

**API Endpoints yang tersedia:**
- `POST /api/v1/auth/login` - Login
- `POST /api/v1/auth/login-mysql` - Login dengan MySQL
- `POST /api/v1/auth/logout` - Logout
- `GET /api/v1/auth/me` - Get current user
- `POST /api/v1/auth/refresh` - Refresh token
- `POST /api/v1/upload` - Upload file

