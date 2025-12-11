# Upgrade PHP 8.3 ke PHP 8.4 - Step by Step

## ⚠️ PENTING: Backup Data Sebelum Upgrade

Sebelum upgrade, pastikan:
1. ✅ Backup database (jika ada data penting)
2. ✅ Backup file `.env`
3. ✅ Commit semua perubahan ke Git

---

## 📋 Step-by-Step Upgrade PHP 8.3 → 8.4

### **Step 1: Stop Container yang Berjalan**

```bash
cd C:\kerjaan\super-app
docker-compose down
```

### **Step 2: Hapus Image Lama (Optional, untuk fresh build)**

```bash
# Hapus image PHP lama
docker rmi super-app/php

# Atau hapus semua image yang tidak digunakan
docker image prune -a
```

### **Step 3: Rebuild Container dengan PHP 8.4**

```bash
# Rebuild dengan --no-cache untuk memastikan fresh build
docker-compose build --no-cache app

# Atau rebuild semua
docker-compose build --no-cache
```

**Catatan**: Proses ini akan memakan waktu 10-15 menit karena:
- Download PHP 8.4 image
- Install semua dependencies
- Compile PHP extensions
- Install sqlsrv extension

### **Step 4: Start Container**

```bash
docker-compose up -d
```

### **Step 5: Verifikasi PHP Version**

```bash
# Cek versi PHP
docker-compose exec app php -v

# Output yang diharapkan:
# PHP 8.4.x (cli) ...
```

### **Step 6: Verifikasi Extension**

```bash
# Cek extension sqlsrv
docker-compose exec app php -m | Select-String "sqlsrv"

# Output:
# pdo_sqlsrv
# sqlsrv

# Cek semua extension
docker-compose exec app php -m
```

### **Step 7: Update Composer Dependencies (Jika Perlu)**

```bash
# Update composer dependencies untuk PHP 8.4
docker-compose exec app composer update --no-interaction

# Atau reinstall
docker-compose exec app composer install --no-interaction
```

### **Step 8: Clear Laravel Cache**

```bash
# Clear semua cache
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

### **Step 9: Test Aplikasi**

```bash
# Test artisan command
docker-compose exec app php artisan --version

# Test koneksi database (jika sudah dikonfigurasi)
docker-compose exec app php artisan tinker
# DB::connection('mysql')->select('SELECT 1');
```

### **Step 10: Akses Aplikasi di Browser**

- Buka: `http://localhost:8000`
- Pastikan aplikasi berjalan dengan baik

---

## 🔧 Troubleshooting

### **Masalah: Extension sqlsrv tidak ter-load**

**Solusi:**
```bash
# Rebuild container
docker-compose down
docker-compose build --no-cache app
docker-compose up -d

# Cek extension
docker-compose exec app php -m | grep sqlsrv
```

### **Masalah: Composer error**

**Solusi:**
```bash
# Update composer
docker-compose exec app composer self-update

# Clear composer cache
docker-compose exec app composer clear-cache

# Reinstall dependencies
docker-compose exec app composer install --no-interaction
```

### **Masalah: Permission error**

**Solusi:**
```bash
# Fix permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www:www storage bootstrap/cache
```

---

## 📝 Perubahan yang Dilakukan

### **1. Dockerfile**
- ✅ `FROM php:8.3-fpm-bullseye` → `FROM php:8.4-fpm-bookworm`
- ✅ Debian 11 (bullseye) → Debian 12 (bookworm)
- ✅ Microsoft repo: `debian/11` → `debian/12`

### **2. composer.json**
- ✅ `"php": "^8.2"` → `"php": "^8.4"`

---

## ✅ Checklist Setelah Upgrade

- [ ] PHP version: `php -v` menunjukkan 8.4.x
- [ ] Extension sqlsrv ter-load: `php -m | grep sqlsrv`
- [ ] Extension pdo_sqlsrv ter-load: `php -m | grep pdo_sqlsrv`
- [ ] Laravel berjalan: `php artisan --version`
- [ ] Aplikasi bisa diakses: `http://localhost:8000`
- [ ] Tidak ada error di log: `docker-compose logs app`

---

## 🚀 Quick Command (All-in-One)

Jika ingin langsung rebuild tanpa step-by-step:

```bash
cd C:\kerjaan\super-app

# Stop container
docker-compose down

# Rebuild dengan PHP 8.4
docker-compose build --no-cache app

# Start container
docker-compose up -d

# Verifikasi
docker-compose exec app php -v
docker-compose exec app php -m | Select-String "sqlsrv"

# Clear cache
docker-compose exec app php artisan config:clear

# Test
docker-compose exec app php artisan --version
```

---

## 📌 Catatan Penting

1. **Backup dulu**: Pastikan backup data penting sebelum upgrade
2. **Waktu build**: Rebuild memakan waktu 10-15 menit
3. **Internet**: Pastikan koneksi internet stabil untuk download dependencies
4. **Compatibility**: Pastikan semua package Laravel kompatibel dengan PHP 8.4
5. **Testing**: Test semua fitur aplikasi setelah upgrade

---

## 🎯 Hasil yang Diharapkan

Setelah upgrade, Anda akan mendapatkan:
- ✅ PHP 8.4.x (versi terbaru)
- ✅ Debian 12 (bookworm) - OS lebih baru
- ✅ Semua extension tetap berfungsi
- ✅ Performa lebih baik (PHP 8.4 lebih cepat dari 8.3)

