# ⚡ Quick Start Guide - Garuda Test Application

**Panduan cepat untuk memulai development dengan aplikasi Garuda Test.**

---

## 🚀 **Quick Start (5 Menit)**

### **1. Prerequisites Check** ✅
```powershell
# Cek Docker
docker --version
docker-compose --version

# Cek container status
cd backend
docker-compose ps
```

**Expected**: Containers should be running

---

### **2. Configure Database** 🔌

**Option A: Using Script (Recommended)**
```powershell
cd backend
.\setup-database.ps1
```

**Option B: Manual Edit**
```powershell
notepad backend\.env
# Edit database credentials
```

**Required Info**:
- MySQL: Host, Database, Username, Password
- SQL Server 1: Host, Database, Username, Password
- SQL Server 2: Host, Database, Username, Password

---

### **3. Test Database Connections** ✅
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

### **4. Run Migrations** 📊
```powershell
docker-compose exec app php artisan migrate
```

---

### **5. Test Application** 🧪
```powershell
# Test web
Start-Process "http://localhost:8000"

# Test API
curl http://localhost:8000/api/v1/examples/success
```

---

## 📋 **Common Commands**

### **Docker Commands**
```powershell
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# View logs
docker-compose logs -f app

# Restart
docker-compose restart
```

### **Laravel Commands**
```powershell
# Test database
docker-compose exec app php artisan db:test

# Run migrations
docker-compose exec app php artisan migrate

# Clear cache
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear

# View routes
docker-compose exec app php artisan route:list
```

### **Development Commands**
```powershell
# Install dependencies
docker-compose exec app composer install
docker-compose exec app npm install

# Build frontend
docker-compose exec app npm run build

# Development mode
docker-compose exec app npm run dev
```

---

## 🔗 **Quick Links**

- **Web**: http://localhost:8000
- **API**: http://localhost:8000/api/v1
- **API Docs**: `backend/API-DOCUMENTATION.md`
- **Database Guide**: `backend/DATABASE-CONFIG-GUIDE.md`

---

## 🐛 **Troubleshooting**

### **Container Not Running**
```powershell
docker-compose up -d --build
```

### **Database Connection Failed**
1. Check credentials in `.env`
2. Test connection: `php artisan db:test`
3. Check network/firewall

### **Port Already in Use**
Edit `docker-compose.yml`, change port:
```yaml
ports:
  - "8001:80"  # Change from 8000
```

---

## 📚 **Full Documentation**

- **Installation**: `plan-installation.md`
- **Database Setup**: `backend/DATABASE-CONFIG-GUIDE.md`
- **API Documentation**: `backend/API-DOCUMENTATION.md`
- **Progress**: `PROGRESS-CHECKLIST.md`

---

**Ready to code! 🚀**

