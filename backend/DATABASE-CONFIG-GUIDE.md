# 🔌 Database Configuration Guide

**Panduan lengkap untuk mengkonfigurasi kredensial database di aplikasi Garuda Test.**

---

## 📋 Informasi yang Diperlukan

Sebelum memulai, pastikan Anda memiliki informasi berikut:

### **MySQL Database**
- ✅ Host (contoh: `db.example.com` atau `192.168.1.100`)
- ✅ Port (default: `3306`)
- ✅ Database Name
- ✅ Username
- ✅ Password

### **SQL Server 1**
- ✅ Host (contoh: `sqlserver1.example.com` atau `192.168.1.101`)
- ✅ Port (default: `1433`)
- ✅ Database Name
- ✅ Username
- ✅ Password

### **SQL Server 2**
- ✅ Host (contoh: `sqlserver2.example.com` atau `192.168.1.102`)
- ✅ Port (default: `1433`)
- ✅ Database Name
- ✅ Username
- ✅ Password

---

## 🚀 Metode 1: Menggunakan Script PowerShell (Recommended)

Script PowerShell akan memandu Anda mengisi kredensial secara interaktif.

### **Langkah-langkah:**

1. **Buka PowerShell di folder backend**
   ```powershell
   cd backend
   ```

2. **Jalankan script**
   ```powershell
   .\setup-database.ps1
   ```

3. **Ikuti instruksi di layar**
   - Script akan meminta input untuk setiap database
   - Password akan disembunyikan saat diketik
   - Anda akan melihat summary sebelum update

4. **Konfirmasi dan update**
   - Review summary yang ditampilkan
   - Ketik `Y` untuk konfirmasi
   - File `.env` akan otomatis di-update

### **Keuntungan:**
- ✅ Password disembunyikan saat input
- ✅ Validasi otomatis
- ✅ Summary sebelum update
- ✅ Update otomatis file `.env`

---

## ✏️ Metode 2: Manual Edit (Alternatif)

Jika lebih suka edit manual, ikuti langkah berikut:

### **Langkah-langkah:**

1. **Buka file `.env`**
   ```powershell
   # Di Windows
   notepad backend\.env
   
   # Atau di VS Code
   code backend\.env
   ```

2. **Cari dan ganti placeholder berikut:**

   #### **MySQL Configuration**
   ```env
   # Ganti ini:
   DB_HOST=your_mysql_host
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   
   # Menjadi (contoh):
   DB_HOST=db.example.com
   DB_DATABASE=garudatest_db
   DB_USERNAME=garuda_user
   DB_PASSWORD=SecurePassword123!
   ```

   #### **SQL Server 1 Configuration**
   ```env
   # Ganti ini:
   SQLSRV_HOST=your_sqlserver_host
   SQLSRV_DATABASE=your_database_name
   SQLSRV_USERNAME=your_username
   SQLSRV_PASSWORD=your_password
   
   # Menjadi (contoh):
   SQLSRV_HOST=sqlserver1.example.com
   SQLSRV_DATABASE=garudatest_sqlsrv1
   SQLSRV_USERNAME=garuda_sqlsrv1_user
   SQLSRV_PASSWORD=SecurePassword123!
   ```

   #### **SQL Server 2 Configuration**
   ```env
   # Ganti ini:
   SQLSRV2_HOST=your_sqlserver2_host
   SQLSRV2_DATABASE=your_database2_name
   SQLSRV2_USERNAME=your_username2
   SQLSRV2_PASSWORD=your_password2
   
   # Menjadi (contoh):
   SQLSRV2_HOST=sqlserver2.example.com
   SQLSRV2_DATABASE=garudatest_sqlsrv2
   SQLSRV2_USERNAME=garuda_sqlsrv2_user
   SQLSRV2_PASSWORD=SecurePassword123!
   ```

3. **Simpan file**

---

## ✅ Verifikasi Konfigurasi

Setelah mengisi kredensial, verifikasi dengan:

### **1. Cek File .env**
```powershell
# Cek apakah placeholder masih ada
Select-String -Path backend\.env -Pattern "your_.*"
```

Jika masih ada output, berarti masih ada placeholder yang belum diganti.

### **2. Test Koneksi Database**
```powershell
cd backend
docker-compose exec app php artisan db:test
```

**Output yang diharapkan**:
```
✅ mysql - Connection successful
✅ sqlsrv - Connection successful
✅ sqlsrv2 - Connection successful
```

### **3. Test Koneksi Satu per Satu**
```powershell
# Test MySQL
docker-compose exec app php artisan db:test --connection=mysql

# Test SQL Server 1
docker-compose exec app php artisan db:test --connection=sqlsrv

# Test SQL Server 2
docker-compose exec app php artisan db:test --connection=sqlsrv2
```

---

## 🔍 Troubleshooting

### **Error: Connection Failed**

**Kemungkinan penyebab:**
1. Host tidak bisa diakses dari Docker container
2. Port salah atau firewall memblokir
3. Kredensial salah
4. Database belum dibuat

**Solusi:**
```powershell
# 1. Test koneksi dari container
docker-compose exec app ping your_database_host

# 2. Cek kredensial di .env
docker-compose exec app php artisan tinker
# Di dalam tinker:
DB::connection('mysql')->select('SELECT 1');
```

### **Error: SQL Server Login Timeout**

**Kemungkinan penyebab:**
1. SQL Server tidak bisa diakses dari Docker container
2. Firewall memblokir port 1433
3. SQL Server tidak mengizinkan remote connection

**Solusi:**
1. Pastikan SQL Server mengizinkan remote connection
2. Cek firewall rules
3. Test koneksi dari host machine terlebih dahulu

### **Error: Access Denied**

**Kemungkinan penyebab:**
1. Username atau password salah
2. User tidak memiliki permission

**Solusi:**
1. Verifikasi kredensial dengan database administrator
2. Pastikan user memiliki permission yang diperlukan

---

## 📝 Template .env untuk Reference

Berikut template lengkap untuk referensi:

```env
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
```

---

## 🔐 Security Best Practices

1. **Jangan commit file `.env` ke Git**
   - File `.env` sudah ada di `.gitignore`
   - Pastikan tidak ada kredensial yang ter-commit

2. **Gunakan password yang kuat**
   - Minimal 12 karakter
   - Kombinasi huruf, angka, dan simbol

3. **Backup file `.env`**
   - Simpan backup di tempat aman
   - Jangan simpan di repository

4. **Gunakan environment variables berbeda untuk production**
   - Jangan gunakan kredensial production di development

---

## 📚 Langkah Selanjutnya

Setelah konfigurasi database berhasil:

1. ✅ **Test koneksi**: `docker-compose exec app php artisan db:test`
2. ✅ **Run migrations**: `docker-compose exec app php artisan migrate`
3. ✅ **Test aplikasi**: Akses http://localhost:8000

---

## 💡 Tips

- Gunakan script PowerShell untuk kemudahan dan keamanan
- Simpan backup file `.env` sebelum mengubah
- Test koneksi setelah setiap perubahan
- Dokumentasikan perubahan yang dilakukan

---

**Selamat! Database configuration selesai! 🎉**

