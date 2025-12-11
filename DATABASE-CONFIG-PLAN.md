# 📋 Database Configuration Plan

**Status**: ⏳ **PENDING** - Menunggu kredensial database dari user  
**Tanggal**: 11 Desember 2025

---

## 🔍 **Status Saat Ini**

### ✅ **Yang Sudah Selesai**

1. **Database Configuration Structure** ✅
   - File `config/database.php` sudah dikonfigurasi
   - Konfigurasi `sqlsrv` dan `sqlsrv2` sudah ditambahkan
   - Environment variables sudah didefinisikan di `env.example`

2. **Database Test Command** ✅
   - Command `php artisan db:test` sudah dibuat
   - Bisa test semua koneksi atau satu per satu
   - Menampilkan informasi koneksi dan error message

3. **Setup Script & Documentation** ✅
   - Script PowerShell `setup-database.ps1` sudah dibuat
   - Panduan lengkap `DATABASE-CONFIG-GUIDE.md` sudah dibuat
   - Template dan contoh sudah disediakan

---

## ⚠️ **Yang Belum / Masih Pending**

### **1. Database Credentials di .env** 🔴 **PRIORITAS TINGGI**

**Status**: ⏳ **BELUM** - Masih menggunakan placeholder

**File yang perlu di-edit**: `backend/.env`

**Placeholder yang masih ada**:
```env
# MySQL
DB_HOST=your_mysql_host          ❌ BELUM DIGANTI
DB_DATABASE=your_database_name   ❌ BELUM DIGANTI
DB_USERNAME=your_username        ❌ BELUM DIGANTI
DB_PASSWORD=your_password        ❌ BELUM DIGANTI

# SQL Server 1
SQLSRV_HOST=your_sqlserver_host      ❌ BELUM DIGANTI
SQLSRV_DATABASE=your_database_name   ❌ BELUM DIGANTI
SQLSRV_USERNAME=your_username        ❌ BELUM DIGANTI
SQLSRV_PASSWORD=your_password        ❌ BELUM DIGANTI

# SQL Server 2
SQLSRV2_HOST=your_sqlserver2_host    ❌ BELUM DIGANTI
SQLSRV2_DATABASE=your_database2_name ❌ BELUM DIGANTI
SQLSRV2_USERNAME=your_username2      ❌ BELUM DIGANTI
SQLSRV2_PASSWORD=your_password2      ❌ BELUM DIGANTI
```

**Informasi yang Diperlukan**:
- ✅ MySQL: Host, Port (3306), Database Name, Username, Password
- ✅ SQL Server 1: Host, Port (1433), Database Name, Username, Password
- ✅ SQL Server 2: Host, Port (1433), Database Name, Username, Password

**Cara Mengisi**:
1. **Menggunakan Script** (Recommended):
   ```powershell
   cd backend
   .\setup-database.ps1
   ```

2. **Manual Edit**:
   ```powershell
   notepad backend\.env
   # Edit dan ganti semua placeholder
   ```

---

### **2. Test Database Connections** 🔴 **PRIORITAS TINGGI**

**Status**: ⏳ **BELUM** - Menunggu kredensial diisi

**Setelah kredensial diisi**, test koneksi:

```powershell
cd backend
docker-compose exec app php artisan db:test
```

**Output yang Diharapkan**:
```
✅ mysql - Connection successful
✅ sqlsrv - Connection successful
✅ sqlsrv2 - Connection successful
```

**Jika Error**:
- Cek kredensial sudah benar
- Cek server database bisa diakses dari Docker container
- Cek firewall/network settings

---

### **3. Run Database Migrations** 🔴 **PRIORITAS TINGGI**

**Status**: ⏳ **BELUM** - Menunggu koneksi database berhasil

**Setelah koneksi berhasil**, jalankan migrations:

```powershell
cd backend
docker-compose exec app php artisan migrate:status
docker-compose exec app php artisan migrate
```

**Migrations yang Tersedia**:
- `0001_01_01_000000_create_users_table.php`
- `0001_01_01_000001_create_cache_table.php`
- `0001_01_01_000002_create_jobs_table.php`

---

## 📋 **Plan Lengkap Database Configuration**

### **Step 1: Siapkan Informasi Database** 📝

**Sebelum memulai, pastikan Anda memiliki**:

1. **MySQL Database**:
   - [ ] Host address (contoh: `db.example.com` atau IP)
   - [ ] Port (default: `3306`)
   - [ ] Database name
   - [ ] Username
   - [ ] Password
   - [ ] Database sudah dibuat di server
   - [ ] User memiliki permission yang diperlukan

2. **SQL Server 1**:
   - [ ] Host address (contoh: `sqlserver1.example.com` atau IP)
   - [ ] Port (default: `1433`)
   - [ ] Database name
   - [ ] Username
   - [ ] Password
   - [ ] Database sudah dibuat di server
   - [ ] User memiliki permission yang diperlukan
   - [ ] SQL Server mengizinkan remote connection

3. **SQL Server 2**:
   - [ ] Host address (contoh: `sqlserver2.example.com` atau IP)
   - [ ] Port (default: `1433`)
   - [ ] Database name
   - [ ] Username
   - [ ] Password
   - [ ] Database sudah dibuat di server
   - [ ] User memiliki permission yang diperlukan
   - [ ] SQL Server mengizinkan remote connection

---

### **Step 2: Konfigurasi .env File** ✏️

**Metode A: Menggunakan Script (Recommended)**

```powershell
cd backend
.\setup-database.ps1
```

**Keuntungan**:
- ✅ Password disembunyikan saat input
- ✅ Validasi otomatis
- ✅ Summary sebelum update
- ✅ Update otomatis file `.env`

**Metode B: Manual Edit**

```powershell
notepad backend\.env
```

**Edit bagian berikut**:

```env
# MySQL Configuration
DB_HOST=your_actual_mysql_host
DB_DATABASE=your_actual_database_name
DB_USERNAME=your_actual_username
DB_PASSWORD=your_actual_password

# SQL Server 1 Configuration
SQLSRV_HOST=your_actual_sqlserver_host
SQLSRV_DATABASE=your_actual_database_name
SQLSRV_USERNAME=your_actual_username
SQLSRV_PASSWORD=your_actual_password

# SQL Server 2 Configuration
SQLSRV2_HOST=your_actual_sqlserver2_host
SQLSRV2_DATABASE=your_actual_database2_name
SQLSRV2_USERNAME=your_actual_username2
SQLSRV2_PASSWORD=your_actual_password2
```

---

### **Step 3: Verifikasi Konfigurasi** ✅

**Cek apakah placeholder masih ada**:

```powershell
cd backend
Select-String -Path .env -Pattern "your_.*"
```

**Jika masih ada output**, berarti masih ada placeholder yang belum diganti.

---

### **Step 4: Test Database Connections** 🧪

**Test semua koneksi**:

```powershell
cd backend
docker-compose exec app php artisan db:test
```

**Test satu per satu**:

```powershell
# Test MySQL
docker-compose exec app php artisan db:test --connection=mysql

# Test SQL Server 1
docker-compose exec app php artisan db:test --connection=sqlsrv

# Test SQL Server 2
docker-compose exec app php artisan db:test --connection=sqlsrv2
```

**Expected Result**: Semua koneksi berhasil ✅

---

### **Step 5: Run Database Migrations** 📊

**Setelah semua koneksi berhasil**:

```powershell
cd backend

# Cek status migrations
docker-compose exec app php artisan migrate:status

# Run migrations
docker-compose exec app php artisan migrate

# Atau dengan force (jika di production)
docker-compose exec app php artisan migrate --force
```

**Expected Result**: 
- Migrations berhasil dijalankan
- Tables berhasil dibuat di database

---

## 🔧 **Troubleshooting**

### **Error: Connection Failed**

**Kemungkinan Penyebab**:
1. Host tidak bisa diakses dari Docker container
2. Port salah atau firewall memblokir
3. Kredensial salah
4. Database belum dibuat

**Solusi**:
```powershell
# Test koneksi dari container
docker-compose exec app ping your_database_host

# Cek kredensial
docker-compose exec app php artisan tinker
# Di dalam tinker:
DB::connection('mysql')->select('SELECT 1');
```

### **Error: SQL Server Login Timeout**

**Kemungkinan Penyebab**:
1. SQL Server tidak bisa diakses dari Docker container
2. Firewall memblokir port 1433
3. SQL Server tidak mengizinkan remote connection

**Solusi**:
1. Pastikan SQL Server mengizinkan remote connection
2. Cek firewall rules
3. Test koneksi dari host machine terlebih dahulu

### **Error: Access Denied**

**Kemungkinan Penyebab**:
1. Username atau password salah
2. User tidak memiliki permission

**Solusi**:
1. Verifikasi kredensial dengan database administrator
2. Pastikan user memiliki permission yang diperlukan

---

## 📊 **Checklist Progress**

### **Pre-Configuration**
- [ ] Informasi database MySQL sudah siap
- [ ] Informasi database SQL Server 1 sudah siap
- [ ] Informasi database SQL Server 2 sudah siap
- [ ] Database sudah dibuat di server
- [ ] User memiliki permission yang diperlukan

### **Configuration**
- [ ] File `.env` sudah di-edit
- [ ] MySQL credentials sudah diisi
- [ ] SQL Server 1 credentials sudah diisi
- [ ] SQL Server 2 credentials sudah diisi
- [ ] Semua placeholder sudah diganti

### **Verification**
- [ ] Test koneksi MySQL berhasil
- [ ] Test koneksi SQL Server 1 berhasil
- [ ] Test koneksi SQL Server 2 berhasil
- [ ] Semua koneksi verified

### **Migrations**
- [ ] Migrations dijalankan
- [ ] Tables berhasil dibuat
- [ ] Database siap digunakan

---

## 🎯 **Summary**

**Yang Sudah Selesai**:
- ✅ Database configuration structure
- ✅ Database test command
- ✅ Setup script & documentation

**Yang Masih Pending**:
- ⏳ **Fill database credentials di .env** (PRIORITAS TINGGI)
- ⏳ **Test database connections** (PRIORITAS TINGGI)
- ⏳ **Run migrations** (PRIORITAS TINGGI)

**Blocker**: 
- ⚠️ Menunggu kredensial database dari user

**Next Action**:
1. User mengisi kredensial database di `.env`
2. Test koneksi dengan `php artisan db:test`
3. Run migrations setelah koneksi berhasil

---

**Last Updated**: 11 Desember 2025

