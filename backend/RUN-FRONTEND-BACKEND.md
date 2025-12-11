# 🚀 Menjalankan Frontend dan Backend untuk Testing

**Panduan untuk menjalankan frontend dan backend secara bersamaan untuk testing.**

---

## 📋 **Status Saat Ini**

### ✅ **Backend**
- Container running: `super-app-php`, `super-app-nginx`
- Port: `8000` (http://localhost:8000)
- API endpoints: `/api/v1/*`

### ⚠️ **Kendala yang Ditemukan**

1. **Database credentials masih placeholder**
   - DB_HOST masih `your_mysql_host`
   - Perlu setup database lokal atau isi credentials

2. **Frontend container name conflict**
   - Frontend dan backend menggunakan nama container yang sama
   - Solusi: Gunakan backend yang sudah ada untuk frontend

---

## 🎯 **Solusi: Frontend dan Backend di Container yang Sama**

Karena frontend juga Laravel application, kita bisa menjalankan keduanya di container backend yang sudah ada.

### **Arsitektur:**
- **Backend API**: Port 8000 (Nginx)
- **Frontend Dev Server (Vite)**: Port 5173 (Vite HMR)
- **Frontend Production**: Port 8000 (via Nginx, setelah build)

---

## 🚀 **Langkah-langkah**

### **Step 1: Setup Database (Jika Belum)**

**Opsi A: Database Lokal dengan Docker**

```powershell
cd backend
.\setup-database-local.ps1
```

**Opsi B: Database Eksternal**

Edit `backend/.env` dan isi dengan kredensial database eksternal.

### **Step 2: Test Database Connection**

```powershell
cd backend
docker-compose exec app php artisan db:test
```

**Expected**: Semua koneksi berhasil ✅

### **Step 3: Run Migrations (Jika Perlu)**

```powershell
docker-compose exec app php artisan migrate
```

### **Step 4: Jalankan Backend API**

Backend sudah running di port 8000. Test:

```powershell
# Test API
curl http://localhost:8000/api/v1/examples/success

# Atau buka di browser
Start-Process "http://localhost:8000"
```

### **Step 5: Jalankan Frontend Dev Server (Vite)**

**Di terminal baru** (biarkan backend tetap running):

```powershell
cd backend
docker-compose exec app npm run dev
```

**Output yang diharapkan**:
```
VITE v7.2.7  ready in 500 ms

➜  Local:   http://localhost:5173/
➜  Network: use --host to expose
```

Frontend akan accessible di: **http://localhost:5173**

### **Step 6: Testing Frontend dan Backend**

**Backend API:**
- URL: http://localhost:8000
- API: http://localhost:8000/api/v1/*

**Frontend:**
- URL: http://localhost:5173
- Hot reload: Aktif (perubahan langsung terlihat)

---

## 🔧 **Alternatif: Frontend di Port Terpisah**

Jika ingin frontend di port terpisah (misalnya 3000), edit `vite.config.js`:

```javascript
export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 3000,
    },
    // ... rest of config
});
```

Lalu jalankan:
```powershell
docker-compose exec app npm run dev
```

Frontend akan di: http://localhost:3000

---

## 📊 **Testing Checklist**

### **Backend Testing**
- [ ] Backend accessible: http://localhost:8000
- [ ] API endpoint working: http://localhost:8000/api/v1/examples/success
- [ ] Database connection successful
- [ ] Migrations run (jika perlu)

### **Frontend Testing**
- [ ] Frontend dev server running: http://localhost:5173
- [ ] Vite HMR working (hot reload)
- [ ] Frontend bisa akses backend API
- [ ] CORS configured (jika perlu)

### **Integration Testing**
- [ ] Frontend bisa call backend API
- [ ] Authentication flow working
- [ ] File upload working (jika ada)

---

## 🐛 **Troubleshooting**

### **Error: Port 5173 already in use**

```powershell
# Cek process yang menggunakan port
netstat -ano | findstr :5173

# Atau ubah port di vite.config.js
```

### **Error: Frontend tidak bisa akses backend API**

**Cek CORS configuration** di `backend/config/cors.php` atau middleware.

**Test dari browser console**:
```javascript
fetch('http://localhost:8000/api/v1/examples/success')
  .then(r => r.json())
  .then(console.log)
```

### **Error: Vite tidak terdeteksi perubahan**

Pastikan Vite dev server running dan file watch aktif.

---

## 🎯 **Quick Start Commands**

```powershell
# 1. Setup database (jika belum)
cd backend
.\setup-database-local.ps1

# 2. Test database
docker-compose exec app php artisan db:test

# 3. Run migrations
docker-compose exec app php artisan migrate

# 4. Test backend
curl http://localhost:8000/api/v1/examples/success

# 5. Jalankan frontend (di terminal baru)
cd backend
docker-compose exec app npm run dev

# 6. Buka browser
Start-Process "http://localhost:8000"  # Backend
Start-Process "http://localhost:5173"  # Frontend
```

---

## 📚 **Dokumentasi Terkait**

- **Backend API**: `backend/API-DOCUMENTATION.md`
- **Database Setup**: `backend/SETUP-DATABASE-LOCAL.md`
- **Quick Start**: `QUICK-START.md`

---

**Selamat Testing! 🎉**

