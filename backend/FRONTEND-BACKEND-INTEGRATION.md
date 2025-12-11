# 🔗 Integrasi Frontend & Backend

**Panduan lengkap untuk integrasi frontend dengan backend API.**

---

## 📋 **Status Integrasi**

### ✅ **Yang Sudah Dikonfigurasi**

1. **API Base URL Configuration**
   - File: `frontend/resources/js/bootstrap.js`
   - Base URL: `http://localhost:8000` (default)
   - Dapat diubah via environment variable: `VITE_API_URL`

2. **CORS Configuration**
   - File: `backend/docker/nginx/conf.d/app.conf`
   - Allow-Origin: `*` (untuk development)
   - Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS
   - Headers: Authorization, Content-Type, Accept

3. **Axios Configuration**
   - Base URL: `http://localhost:8000`
   - Default Headers:
     - `X-Requested-With: XMLHttpRequest`
     - `Accept: application/json`
     - `Content-Type: application/json`

---

## 🚀 **Cara Menggunakan**

### **1. Setup Environment Variable (Opsional)**

Jika ingin menggunakan API URL yang berbeda, tambahkan di `frontend/.env`:

```env
VITE_API_URL=http://localhost:8000
```

### **2. Menggunakan Axios di Frontend**

```javascript
// Di file JavaScript/TypeScript frontend
// Axios sudah tersedia secara global via window.axios

// GET Request
window.axios.get('/api/v1/examples/success')
  .then(response => {
    console.log(response.data);
  })
  .catch(error => {
    console.error(error);
  });

// POST Request
window.axios.post('/api/v1/auth/login', {
  email: 'user@example.com',
  password: 'password'
})
  .then(response => {
    console.log(response.data);
    // Simpan token jika login berhasil
    if (response.data.data?.token) {
      localStorage.setItem('token', response.data.data.token);
    }
  })
  .catch(error => {
    console.error(error);
  });

// Request dengan Authentication Token
window.axios.get('/api/v1/auth/me', {
  headers: {
    'Authorization': `Bearer ${localStorage.getItem('token')}`
  }
})
  .then(response => {
    console.log(response.data);
  })
  .catch(error => {
    console.error(error);
  });
```

### **3. Helper Function untuk API Calls**

Buat file `frontend/resources/js/api.js`:

```javascript
// API Helper Functions
export const api = {
  // Set token untuk semua request
  setToken(token) {
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
  },

  // Remove token
  removeToken() {
    delete window.axios.defaults.headers.common['Authorization'];
  },

  // Login
  async login(email, password) {
    try {
      const response = await window.axios.post('/api/v1/auth/login-mysql', {
        email,
        password
      });
      
      if (response.data.success && response.data.data?.token) {
        this.setToken(response.data.data.token);
        localStorage.setItem('token', response.data.data.token);
      }
      
      return response.data;
    } catch (error) {
      throw error.response?.data || error;
    }
  },

  // Logout
  async logout() {
    try {
      await window.axios.post('/api/v1/auth/logout');
      this.removeToken();
      localStorage.removeItem('token');
    } catch (error) {
      console.error('Logout error:', error);
    }
  },

  // Get current user
  async getCurrentUser() {
    try {
      const response = await window.axios.get('/api/v1/auth/me');
      return response.data;
    } catch (error) {
      throw error.response?.data || error;
    }
  },

  // Upload file
  async uploadFile(file) {
    try {
      const formData = new FormData();
      formData.append('file', file);
      
      const response = await window.axios.post('/api/v1/upload', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });
      
      return response.data;
    } catch (error) {
      throw error.response?.data || error;
    }
  }
};

// Auto-set token dari localStorage saat page load
if (typeof window !== 'undefined') {
  const token = localStorage.getItem('token');
  if (token) {
    api.setToken(token);
  }
}
```

Kemudian import di `app.js`:

```javascript
import './bootstrap';
import { api } from './api';

// Export untuk digunakan di komponen lain
window.api = api;
```

---

## 🧪 **Testing Integrasi**

### **1. Test Backend API**

```powershell
# Test API endpoint
curl http://localhost:8000/api/v1/examples/success

# Expected response:
# {"success":true,"message":"Operation successful","data":null}
```

### **2. Test Frontend-Backend Connection**

Buka browser console di `http://localhost:5173` dan jalankan:

```javascript
// Test API call dari frontend
window.axios.get('/api/v1/examples/success')
  .then(response => {
    console.log('✅ API Call Success:', response.data);
  })
  .catch(error => {
    console.error('❌ API Call Failed:', error);
  });
```

### **3. Test dengan Authentication**

```javascript
// 1. Login
window.api.login('user@example.com', 'password')
  .then(data => {
    console.log('Login success:', data);
    
    // 2. Get current user
    return window.api.getCurrentUser();
  })
  .then(data => {
    console.log('Current user:', data);
  })
  .catch(error => {
    console.error('Error:', error);
  });
```

---

## 📊 **API Endpoints yang Tersedia**

### **Authentication**
- `POST /api/v1/auth/login` - Login dari SQL Server (seleksi)
- `POST /api/v1/auth/login-mysql` - Login dari MySQL
- `POST /api/v1/auth/logout` - Logout (requires auth)
- `POST /api/v1/auth/refresh` - Refresh token (requires auth)
- `GET /api/v1/auth/me` - Get current user (requires auth)

### **File Upload**
- `POST /api/v1/upload` - Upload file (requires auth)
- `DELETE /api/v1/upload` - Delete file (requires auth)
- `POST /api/v1/upload/signed-url` - Get signed URL (requires auth)
- `POST /api/v1/upload/get-base64` - Get file as base64 (requires auth)
- `GET /api/v1/upload/get` - Get file directly (requires auth)

### **Examples**
- `GET /api/v1/examples/success` - Test success response
- `GET /api/v1/examples/error` - Test error response

---

## 🔧 **Troubleshooting**

### **Error: CORS Policy**

Jika mendapat error CORS, pastikan:
1. CORS sudah dikonfigurasi di `backend/docker/nginx/conf.d/app.conf`
2. Backend container sudah di-restart setelah perubahan config

### **Error: Network Error**

Jika mendapat network error:
1. Pastikan backend running di `http://localhost:8000`
2. Pastikan frontend dev server running di `http://localhost:5173`
3. Cek firewall/antivirus yang mungkin memblokir koneksi

### **Error: 401 Unauthorized**

Jika mendapat 401 error:
1. Pastikan token sudah diset di headers
2. Pastikan token masih valid (belum expired)
3. Cek apakah endpoint memerlukan authentication

### **Error: 404 Not Found**

Jika mendapat 404 error:
1. Pastikan endpoint URL benar
2. Pastikan route sudah terdaftar di `backend/routes/api.php`
3. Pastikan menggunakan prefix `/api/v1/`

---

## 📚 **Dokumentasi Terkait**

- **Backend API Documentation**: `backend/API-DOCUMENTATION.md`
- **Frontend Setup**: `frontend/README.md`
- **Running Guide**: `backend/RUN-FRONTEND-BACKEND.md`

---

**Selamat Development! 🎉**

