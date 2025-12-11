# 🔐 Cara Mengisi Database Credentials

**Panduan step-by-step untuk mengisi kredensial database di aplikasi Garuda Test.**

---

## 🎯 **Metode 1: Menggunakan Script PowerShell (DISARANKAN)**

### **Keuntungan:**
- ✅ Password disembunyikan saat diketik
- ✅ Validasi otomatis
- ✅ Summary sebelum update
- ✅ Update otomatis file `.env`

### **Langkah-langkah:**

1. **Buka PowerShell di folder backend**
   ```powershell
   cd backend
   ```

2. **Jalankan script**
   ```powershell
   .\setup-database.ps1
   ```

3. **Ikuti instruksi di layar:**
   - Script akan meminta input untuk setiap database
   - Ketik informasi yang diminta
   - Password akan disembunyikan (tidak terlihat saat diketik)
   - Review summary sebelum update

4. **Konfirmasi**
   - Script akan menampilkan summary
   - Ketik `Y` untuk konfirmasi
   - File `.env` akan otomatis di-update

---

## ✏️ **Metode 2: Manual Edit**

### **Langkah-langkah:**

1. **Buka file `.env`**
   ```powershell
   # Di Windows
   notepad backend\.env
   
   # Atau di VS Code
   code backend\.env
   ```

2. **Cari dan ganti placeholder berikut:**

   #### **MySQL Configuration** (Baris 14-18)
   ```env
   # GANTI INI:
   DB_HOST=your_mysql_host
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   
   # MENJADI (contoh):
   DB_HOST=db.example.com
   DB_DATABASE=garudatest_db
   DB_USERNAME=garuda_user
   DB_PASSWORD=MySecurePassword123!
   ```

   #### **SQL Server 1 Configuration** (Baris 22-26)
   ```env
   # GANTI INI:
   SQLSRV_HOST=your_sqlserver_host
   SQLSRV_DATABASE=your_database_name
   SQLSRV_USERNAME=your_username
   SQLSRV_PASSWORD=your_password
   
   # MENJADI (contoh):
   SQLSRV_HOST=sqlserver1.example.com
   SQLSRV_DATABASE=garudatest_sqlsrv1
   SQLSRV_USERNAME=garuda_sqlsrv1_user
   SQLSRV_PASSWORD=MySecurePassword123!
   ```

   #### **SQL Server 2 Configuration** (Baris 30-34)
   ```env
   # GANTI INI:
   SQLSRV2_HOST=your_sqlserver2_host
   SQLSRV2_DATABASE=your_database2_name
   SQLSRV2_USERNAME=your_username2
   SQLSRV2_PASSWORD=your_password2
   
   # MENJADI (contoh):
   SQLSRV2_HOST=sqlserver2.example.com
   SQLSRV2_DATABASE=garudatest_sqlsrv2
   SQLSRV2_USERNAME=garuda_sqlsrv2_user
   SQLSRV2_PASSWORD=MySecurePassword123!
   ```

3. **Simpan file** (Ctrl+S)

---

## 📋 **Informasi yang Diperlukan**

Sebelum mengisi, pastikan Anda memiliki informasi berikut:

### **MySQL Database:**
- ✅ **Host**: Alamat server MySQL (contoh: `db.example.com` atau `192.168.1.100`)
- ✅ **Port**: Port MySQL (default: `3306`)
- ✅ **Database Name**: Nama database yang sudah dibuat
- ✅ **Username**: Username untuk akses database
- ✅ **Password**: Password untuk username tersebut

### **SQL Server 1:**
- ✅ **Host**: Alamat server SQL Server 1 (contoh: `sqlserver1.example.com` atau `192.168.1.101`)
- ✅ **Port**: Port SQL Server (default: `1433`)
- ✅ **Database Name**: Nama database yang sudah dibuat
- ✅ **Username**: Username untuk akses database
- ✅ **Password**: Password untuk username tersebut

### **SQL Server 2:**
- ✅ **Host**: Alamat server SQL Server 2 (contoh: `sqlserver2.example.com` atau `192.168.1.102`)
- ✅ **Port**: Port SQL Server (default: `1433`)
- ✅ **Database Name**: Nama database yang sudah dibuat
- ✅ **Username**: Username untuk akses database
- ✅ **Password**: Password untuk username tersebut

---

## ✅ **Verifikasi Setelah Mengisi**

### **1. Cek apakah placeholder masih ada:**
```powershell
cd backend
Select-String -Path .env -Pattern "your_.*"
```

**Jika tidak ada output**, berarti semua placeholder sudah diganti ✅

**Jika masih ada output**, berarti masih ada placeholder yang belum diganti ⚠️

### **2. Test koneksi database:**
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

---

## 🔍 **Contoh Lengkap**

Berikut contoh lengkap file `.env` setelah diisi:

```env
# Database MySQL (External - Staging)
DB_CONNECTION=mysql
DB_HOST=db.example.com
DB_PORT=3306
DB_DATABASE=garudatest_db
DB_USERNAME=garuda_user
DB_PASSWORD=MySecurePassword123!

# Database SQL Server 1 (External - Staging)
SQLSRV_CONNECTION=sqlsrv
SQLSRV_HOST=sqlserver1.example.com
SQLSRV_PORT=1433
SQLSRV_DATABASE=garudatest_sqlsrv1
SQLSRV_USERNAME=garuda_sqlsrv1_user
SQLSRV_PASSWORD=MySecurePassword123!

# Database SQL Server 2 (External - Staging)
SQLSRV2_CONNECTION=sqlsrv2
SQLSRV2_HOST=sqlserver2.example.com
SQLSRV2_PORT=1433
SQLSRV2_DATABASE=garudatest_sqlsrv2
SQLSRV2_USERNAME=garuda_sqlsrv2_user
SQLSRV2_PASSWORD=MySecurePassword123!
```

**Catatan**: Ganti contoh di atas dengan kredensial database Anda yang sebenarnya!

---

## 🆘 **Butuh Bantuan?**

Jika Anda tidak memiliki kredensial database:
1. Hubungi database administrator
2. Minta informasi:
   - Host address
   - Database name
   - Username
   - Password
3. Pastikan database sudah dibuat di server
4. Pastikan user memiliki permission yang diperlukan

---

## 📚 **Dokumentasi Terkait**

- **Database Configuration Guide**: `backend/DATABASE-CONFIG-GUIDE.md`
- **Database Configuration Plan**: `DATABASE-CONFIG-PLAN.md`
- **Quick Start**: `QUICK-START.md`

---

**Selamat! Setelah mengisi credentials, test koneksi dengan `php artisan db:test`** 🎉

