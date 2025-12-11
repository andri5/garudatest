# 🗄️ Setup Database Lokal dengan Docker

**Panduan untuk setup database lokal (MySQL dan SQL Server) menggunakan Docker.**

---

## 🎯 **Opsi Setup Database**

### **Opsi 1: Database Lokal dengan Docker (Recommended untuk Development)**

Setup database lokal di Docker container - mudah dan cepat.

### **Opsi 2: Database Eksternal/Staging**

Gunakan database yang sudah ada di server eksternal/staging.

---

## 🚀 **Opsi 1: Setup Database Lokal dengan Docker**

### **Step 1: Update docker-compose.yml**

Tambahkan service database ke `docker-compose.yml`:

```yaml
services:
  # ... existing services (app, nginx) ...

  # MySQL Database
  mysql:
    image: mysql:8.0
    container_name: super-app-mysql
    restart: unless-stopped
    environment:
      MYSQL_ROOT_PASSWORD: rootpassword
      MYSQL_DATABASE: garudatest_db
      MYSQL_USER: garuda_user
      MYSQL_PASSWORD: garuda_password
    ports:
      - "3307:3306"  # Port 3307 di host, 3306 di container
    volumes:
      - mysql-data:/var/lib/mysql
    networks:
      - super-app-network
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
      interval: 10s
      timeout: 5s
      retries: 5

  # SQL Server 1
  sqlserver1:
    image: mcr.microsoft.com/mssql/server:2022-latest
    container_name: super-app-sqlserver1
    restart: unless-stopped
    environment:
      ACCEPT_EULA: "Y"
      SA_PASSWORD: "YourStrong@Passw0rd"
      MSSQL_PID: "Developer"
    ports:
      - "1434:1433"  # Port 1434 di host, 1433 di container
    volumes:
      - sqlserver1-data:/var/opt/mssql
    networks:
      - super-app-network
    healthcheck:
      test: /opt/mssql-tools/bin/sqlcmd -S localhost -U sa -P "YourStrong@Passw0rd" -Q "SELECT 1" || exit 1
      interval: 10s
      timeout: 5s
      retries: 5

  # SQL Server 2
  sqlserver2:
    image: mcr.microsoft.com/mssql/server:2022-latest
    container_name: super-app-sqlserver2
    restart: unless-stopped
    environment:
      ACCEPT_EULA: "Y"
      SA_PASSWORD: "YourStrong@Passw0rd2"
      MSSQL_PID: "Developer"
    ports:
      - "1435:1433"  # Port 1435 di host, 1433 di container
    volumes:
      - sqlserver2-data:/var/opt/mssql
    networks:
      - super-app-network
    healthcheck:
      test: /opt/mssql-tools/bin/sqlcmd -S localhost -U sa -P "YourStrong@Passw0rd2" -Q "SELECT 1" || exit 1
      interval: 10s
      timeout: 5s
      retries: 5

# ... existing networks ...

volumes:
  mysql-data:
    driver: local
  sqlserver1-data:
    driver: local
  sqlserver2-data:
    driver: local
```

### **Step 2: Start Database Containers**

```powershell
cd backend
docker-compose up -d mysql sqlserver1 sqlserver2
```

### **Step 3: Tunggu Database Ready**

Tunggu beberapa detik hingga database siap:

```powershell
# Cek status
docker-compose ps

# Cek logs
docker-compose logs mysql
docker-compose logs sqlserver1
docker-compose logs sqlserver2
```

### **Step 4: Create Databases (Jika Perlu)**

**MySQL** - Database sudah dibuat otomatis dari environment variable.

**SQL Server** - Buat database manual:

```powershell
# Masuk ke SQL Server 1 container
docker-compose exec sqlserver1 /opt/mssql-tools/bin/sqlcmd -S localhost -U sa -P "YourStrong@Passw0rd" -Q "CREATE DATABASE garudatest_sqlsrv1"

# Masuk ke SQL Server 2 container
docker-compose exec sqlserver2 /opt/mssql-tools/bin/sqlcmd -S localhost -U sa -P "YourStrong@Passw0rd2" -Q "CREATE DATABASE garudatest_sqlsrv2"
```

### **Step 5: Update .env File**

Edit `backend/.env` dengan konfigurasi database lokal:

```env
# MySQL (Lokal)
DB_CONNECTION=mysql
DB_HOST=mysql  # Nama service di docker-compose
DB_PORT=3306
DB_DATABASE=garudatest_db
DB_USERNAME=garuda_user
DB_PASSWORD=garuda_password

# SQL Server 1 (Lokal)
SQLSRV_CONNECTION=sqlsrv
SQLSRV_HOST=sqlserver1  # Nama service di docker-compose
SQLSRV_PORT=1433
SQLSRV_DATABASE=garudatest_sqlsrv1
SQLSRV_USERNAME=sa
SQLSRV_PASSWORD=YourStrong@Passw0rd

# SQL Server 2 (Lokal)
SQLSRV2_CONNECTION=sqlsrv2
SQLSRV2_HOST=sqlserver2  # Nama service di docker-compose
SQLSRV2_PORT=1433
SQLSRV2_DATABASE=garudatest_sqlsrv2
SQLSRV2_USERNAME=sa
SQLSRV2_PASSWORD=YourStrong@Passw0rd2
```

**Catatan Penting**: 
- Gunakan **nama service** (mysql, sqlserver1, sqlserver2) sebagai HOST, bukan localhost
- Karena semua container dalam network yang sama, mereka bisa saling akses via service name

### **Step 6: Test Connection**

```powershell
docker-compose exec app php artisan db:test
```

---

## 🌐 **Opsi 2: Database Eksternal/Staging**

Jika Anda memiliki database eksternal/staging yang sudah ada:

### **Step 1: Dapatkan Informasi Database**

Hubungi database administrator untuk mendapatkan:
- Host address (IP atau domain)
- Port
- Database name
- Username
- Password

### **Step 2: Pastikan Database Bisa Diakses**

Test koneksi dari host machine:

```powershell
# Test MySQL (jika ada MySQL client)
mysql -h your_mysql_host -u username -p

# Test SQL Server (jika ada sqlcmd)
sqlcmd -S your_sqlserver_host -U username -P password
```

### **Step 3: Update .env File**

Edit `backend/.env` dengan kredensial database eksternal:

```env
# MySQL (Eksternal)
DB_CONNECTION=mysql
DB_HOST=your_mysql_host  # IP atau domain
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

# SQL Server 1 (Eksternal)
SQLSRV_CONNECTION=sqlsrv
SQLSRV_HOST=your_sqlserver_host  # IP atau domain
SQLSRV_PORT=1433
SQLSRV_DATABASE=your_database_name
SQLSRV_USERNAME=your_username
SQLSRV_PASSWORD=your_password

# SQL Server 2 (Eksternal)
SQLSRV2_CONNECTION=sqlsrv2
SQLSRV2_HOST=your_sqlserver2_host  # IP atau domain
SQLSRV2_PORT=1433
SQLSRV2_DATABASE=your_database2_name
SQLSRV2_USERNAME=your_username2
SQLSRV2_PASSWORD=your_password2
```

**Catatan**: 
- Pastikan server database bisa diakses dari Docker container
- Cek firewall/network settings
- Untuk database eksternal, gunakan IP/domain, bukan service name

---

## 🔧 **Opsi 3: Setup Database Minimal (Hanya MySQL)**

Jika hanya perlu MySQL untuk development:

### **Step 1: Tambahkan MySQL ke docker-compose.yml**

```yaml
services:
  # ... existing services ...

  mysql:
    image: mysql:8.0
    container_name: super-app-mysql
    restart: unless-stopped
    environment:
      MYSQL_ROOT_PASSWORD: rootpassword
      MYSQL_DATABASE: garudatest_db
      MYSQL_USER: garuda_user
      MYSQL_PASSWORD: garuda_password
    ports:
      - "3307:3306"
    volumes:
      - mysql-data:/var/lib/mysql
    networks:
      - super-app-network

volumes:
  mysql-data:
    driver: local
```

### **Step 2: Start MySQL**

```powershell
docker-compose up -d mysql
```

### **Step 3: Update .env**

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=garudatest_db
DB_USERNAME=garuda_user
DB_PASSWORD=garuda_password
```

### **Step 4: Test Connection**

```powershell
docker-compose exec app php artisan db:test --connection=mysql
```

---

## 📋 **Quick Setup Script**

Saya bisa membuat script untuk setup otomatis. Pilih opsi:

1. **Setup semua database (MySQL + 2 SQL Server)** - Lengkap
2. **Setup hanya MySQL** - Minimal untuk development
3. **Setup manual** - Ikuti panduan di atas

---

## ✅ **Verifikasi Setup**

Setelah setup, test koneksi:

```powershell
cd backend
docker-compose exec app php artisan db:test
```

**Expected Output**:
```
✅ mysql - Connection successful
✅ sqlsrv - Connection successful
✅ sqlsrv2 - Connection successful
```

---

## 🐛 **Troubleshooting**

### **Error: Container tidak start**

```powershell
# Cek logs
docker-compose logs mysql
docker-compose logs sqlserver1

# Restart container
docker-compose restart mysql
```

### **Error: Connection refused**

- Pastikan container running: `docker-compose ps`
- Pastikan menggunakan service name sebagai HOST (untuk lokal)
- Pastikan network sama: `docker network ls`

### **Error: SQL Server password tidak kuat**

SQL Server memerlukan password yang kuat:
- Minimal 8 karakter
- Harus mengandung: huruf besar, huruf kecil, angka, simbol
- Contoh: `YourStrong@Passw0rd`

---

## 📚 **Referensi**

- **Docker Compose**: https://docs.docker.com/compose/
- **MySQL Docker**: https://hub.docker.com/_/mysql
- **SQL Server Docker**: https://hub.docker.com/_/microsoft-mssql-server

---

**Pilih opsi yang sesuai dengan kebutuhan Anda!** 🚀

