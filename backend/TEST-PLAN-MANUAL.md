# 📋 Test Plan - Manual Testing (Frontend & Backend)

**Test Plan untuk End-to-End Testing Manual dari Frontend Web UI**

---

## 📊 **Overview**

### **Tujuan Testing**
- Memverifikasi integrasi frontend dan backend berfungsi dengan baik
- Memastikan semua fitur API dapat diakses dari frontend
- Validasi user experience dan UI/UX
- Memastikan error handling berfungsi dengan benar

### **Scope Testing**
- ✅ Frontend Web UI (http://localhost:8000)
- ✅ Backend API Integration
- ✅ Authentication Flow (jika ada)
- ✅ Error Handling
- ✅ Responsive Design
- ✅ Browser Compatibility

### **Out of Scope**
- ❌ Automated Testing (akan dibuat terpisah)
- ❌ Performance Testing
- ❌ Security Testing (penetration testing)
- ❌ Load Testing

---

## 🎯 **Test Environment**

### **URLs**
- **Frontend Web UI**: http://localhost:8000
- **Backend API**: http://localhost:8000/api/v1/*
- **API Documentation**: `backend/API-DOCUMENTATION.md`

### **Browser Requirements**
- ✅ Chrome/Edge (Latest)
- ✅ Firefox (Latest)
- ✅ Safari (Latest) - jika tersedia

### **Prerequisites**
- ✅ Backend running di port 8000
- ✅ Database connected (MySQL + 2 SQL Server)
- ✅ Frontend assets built
- ✅ Browser Developer Tools enabled (F12)

---

## 📝 **Test Cases**

### **TC-001: Home Page Load**

**Objective**: Memverifikasi halaman home dapat di-load dengan benar

**Steps**:
1. Buka browser
2. Navigate ke http://localhost:8000
3. Tunggu halaman load

**Expected Results**:
- ✅ Halaman load tanpa error
- ✅ Laravel welcome page muncul
- ✅ Logo Laravel terlihat
- ✅ "Let's get started" message muncul
- ✅ Links ke Documentation dan Laracasts terlihat
- ✅ Tidak ada error di browser console (F12)

**Actual Results**: 
- [ ] Pass
- [ ] Fail
- [ ] Notes: _______________________

---

### **TC-002: Frontend Assets Load**

**Objective**: Memverifikasi CSS dan JavaScript assets ter-load dengan benar

**Steps**:
1. Buka http://localhost:8000
2. Buka Developer Tools (F12)
3. Cek tab Network
4. Refresh halaman (Ctrl+R atau F5)
5. Filter by CSS dan JS

**Expected Results**:
- ✅ CSS files ter-load (app.css atau build assets)
- ✅ JavaScript files ter-load (app.js atau build assets)
- ✅ Tidak ada 404 errors untuk assets
- ✅ Assets load dalam waktu wajar (< 3 detik)

**Actual Results**: 
- [ ] Pass
- [ ] Fail
- [ ] Notes: _______________________

---

### **TC-003: API Integration Test - Success Endpoint**

**Objective**: Memverifikasi frontend dapat memanggil backend API dengan benar

**Steps**:
1. Buka http://localhost:8000
2. Buka Developer Tools (F12) → Console tab
3. Jalankan command berikut di console:
   ```javascript
   window.axios.get('/api/v1/examples/success')
     .then(response => {
       console.log('Success:', response.data);
       alert('API Call Success! Check console for details.');
     })
     .catch(error => {
       console.error('Error:', error);
       alert('API Call Failed! Check console for details.');
     });
   ```

**Expected Results**:
- ✅ API call berhasil (status 200)
- ✅ Response format sesuai:
   ```json
   {
     "success": true,
     "message": "Operation successful",
     "data": null
   }
   ```
- ✅ Tidak ada CORS error
- ✅ Alert muncul dengan pesan success

**Actual Results**: 
- [ ] Pass
- [ ] Fail
- [ ] Notes: _______________________

---

### **TC-004: API Integration Test - Error Endpoint**

**Objective**: Memverifikasi error handling dari API berfungsi dengan benar

**Steps**:
1. Buka http://localhost:8000
2. Buka Developer Tools (F12) → Console tab
3. Jalankan command berikut di console:
   ```javascript
   window.axios.get('/api/v1/examples/error')
     .then(response => {
       console.log('Response:', response.data);
     })
     .catch(error => {
       console.log('Error Response:', error.response?.data);
       alert('Error handled correctly! Check console for details.');
     });
   ```

**Expected Results**:
- ✅ API call mengembalikan error response
- ✅ Error response format sesuai:
   ```json
   {
     "success": false,
     "message": "Error message",
     "data": null
   }
   ```
- ✅ Error ditangani dengan baik (tidak crash)
- ✅ Alert muncul dengan pesan error handled

**Actual Results**: 
- [ ] Pass
- [ ] Fail
- [ ] Notes: _______________________

---

### **TC-005: API Integration Test - Not Found Endpoint**

**Objective**: Memverifikasi handling untuk endpoint yang tidak ada

**Steps**:
1. Buka http://localhost:8000
2. Buka Developer Tools (F12) → Console tab
3. Jalankan command berikut di console:
   ```javascript
   window.axios.get('/api/v1/examples/not-found')
     .then(response => {
       console.log('Response:', response.data);
     })
     .catch(error => {
       console.log('Error:', error.response?.status, error.response?.data);
       alert('404 handled correctly! Check console for details.');
     });
   ```

**Expected Results**:
- ✅ API call mengembalikan 404 status
- ✅ Error response format sesuai:
   ```json
   {
     "success": false,
     "message": "Route not found",
     "data": null
   }
   ```
- ✅ Error ditangani dengan baik

**Actual Results**: 
- [ ] Pass
- [ ] Fail
- [ ] Notes: _______________________

---

### **TC-006: Authentication Test - Login (jika tersedia)**

**Objective**: Memverifikasi flow authentication dari frontend

**Steps**:
1. Buka http://localhost:8000
2. Buka Developer Tools (F12) → Console tab
3. Jalankan command berikut di console:
   ```javascript
   window.axios.post('/api/v1/auth/login-mysql', {
     email: 'test@example.com',
     password: 'password'
   })
   .then(response => {
     console.log('Login Success:', response.data);
     if (response.data.data?.token) {
       localStorage.setItem('token', response.data.data.token);
       alert('Login successful! Token saved.');
     }
   })
   .catch(error => {
     console.log('Login Error:', error.response?.data);
     alert('Login failed! Check console for details.');
   });
   ```

**Expected Results**:
- ✅ Login request berhasil dikirim
- ✅ Response format sesuai (success atau error)
- ✅ Jika berhasil, token disimpan di localStorage
- ✅ Jika gagal, error message jelas

**Actual Results**: 
- [ ] Pass
- [ ] Fail
- [ ] Notes: _______________________

---

### **TC-007: Authentication Test - Get Current User**

**Objective**: Memverifikasi authenticated request dengan token

**Steps**:
1. Pastikan sudah login (TC-006) dan token ada di localStorage
2. Buka Developer Tools (F12) → Console tab
3. Jalankan command berikut di console:
   ```javascript
   const token = localStorage.getItem('token');
   if (!token) {
     alert('Please login first (TC-006)');
     return;
   }
   
   window.axios.get('/api/v1/auth/me', {
     headers: {
       'Authorization': `Bearer ${token}`
     }
   })
   .then(response => {
     console.log('Current User:', response.data);
     alert('User data retrieved! Check console.');
   })
   .catch(error => {
     console.log('Error:', error.response?.data);
     alert('Failed to get user data! Check console.');
   });
   ```

**Expected Results**:
- ✅ Request dengan token berhasil
- ✅ User data dikembalikan dengan benar
- ✅ Jika token invalid, error 401 dikembalikan

**Actual Results**: 
- [ ] Pass
- [ ] Fail
- [ ] Notes: _______________________

---

### **TC-008: File Upload Test (jika tersedia)**

**Objective**: Memverifikasi file upload functionality

**Steps**:
1. Buka http://localhost:8000
2. Buka Developer Tools (F12) → Console tab
3. Buat file test (atau gunakan file yang ada)
4. Jalankan command berikut di console:
   ```javascript
   const fileInput = document.createElement('input');
   fileInput.type = 'file';
   fileInput.onchange = async (e) => {
     const file = e.target.files[0];
     if (!file) return;
     
     const formData = new FormData();
     formData.append('file', file);
     
     const token = localStorage.getItem('token');
     if (!token) {
       alert('Please login first');
       return;
     }
     
     try {
       const response = await window.axios.post('/api/v1/upload', formData, {
         headers: {
           'Authorization': `Bearer ${token}`,
           'Content-Type': 'multipart/form-data'
         }
       });
       console.log('Upload Success:', response.data);
       alert('File uploaded! Check console.');
     } catch (error) {
       console.log('Upload Error:', error.response?.data);
       alert('Upload failed! Check console.');
     }
   };
   fileInput.click();
   ```

**Expected Results**:
- ✅ File picker muncul
- ✅ File dapat di-upload
- ✅ Response format sesuai
- ✅ File tersimpan dengan benar

**Actual Results**: 
- [ ] Pass
- [ ] Fail
- [ ] Notes: _______________________

---

### **TC-009: Responsive Design Test**

**Objective**: Memverifikasi UI responsive di berbagai ukuran layar

**Steps**:
1. Buka http://localhost:8000
2. Buka Developer Tools (F12)
3. Klik icon responsive design (Ctrl+Shift+M)
4. Test berbagai ukuran:
   - Mobile (375px)
   - Tablet (768px)
   - Desktop (1920px)

**Expected Results**:
- ✅ Layout menyesuaikan dengan ukuran layar
- ✅ Tidak ada overflow horizontal
- ✅ Text readable di semua ukuran
- ✅ Buttons dan links dapat diklik
- ✅ Images tidak terpotong

**Actual Results**: 
- [ ] Pass
- [ ] Fail
- [ ] Notes: _______________________

---

### **TC-010: Dark Mode Test**

**Objective**: Memverifikasi dark mode berfungsi dengan benar

**Steps**:
1. Buka http://localhost:8000
2. Ubah system preference ke dark mode
3. Refresh halaman
4. Atau gunakan browser extension untuk dark mode

**Expected Results**:
- ✅ Dark mode styles ter-apply
- ✅ Text readable (kontras cukup)
- ✅ Colors sesuai dengan dark theme
- ✅ Tidak ada elemen yang hilang

**Actual Results**: 
- [ ] Pass
- [ ] Fail
- [ ] Notes: _______________________

---

### **TC-011: Browser Console Error Check**

**Objective**: Memverifikasi tidak ada JavaScript errors

**Steps**:
1. Buka http://localhost:8000
2. Buka Developer Tools (F12) → Console tab
3. Refresh halaman
4. Interact dengan halaman (scroll, click, dll)
5. Cek untuk errors atau warnings

**Expected Results**:
- ✅ Tidak ada JavaScript errors (red)
- ✅ Minimal warnings (yellow)
- ✅ Semua resources load dengan benar

**Actual Results**: 
- [ ] Pass
- [ ] Fail
- [ ] Notes: _______________________

---

### **TC-012: Network Performance Test**

**Objective**: Memverifikasi performa loading halaman

**Steps**:
1. Buka http://localhost:8000
2. Buka Developer Tools (F12) → Network tab
3. Refresh halaman (Ctrl+R)
4. Cek timing untuk setiap resource

**Expected Results**:
- ✅ Page load time < 3 detik
- ✅ CSS load time < 1 detik
- ✅ JavaScript load time < 1 detik
- ✅ Tidak ada resource yang timeout

**Actual Results**: 
- [ ] Pass
- [ ] Fail
- [ ] Notes: _______________________

---

### **TC-013: CORS Configuration Test**

**Objective**: Memverifikasi CORS sudah dikonfigurasi dengan benar

**Steps**:
1. Buka http://localhost:8000
2. Buka Developer Tools (F12) → Network tab
3. Jalankan API call dari console:
   ```javascript
   window.axios.get('/api/v1/examples/success');
   ```
4. Cek response headers di Network tab

**Expected Results**:
- ✅ Response headers mengandung:
   - `Access-Control-Allow-Origin: *`
   - `Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS`
   - `Access-Control-Allow-Headers: Authorization, Content-Type, Accept`
- ✅ Tidak ada CORS error di console

**Actual Results**: 
- [ ] Pass
- [ ] Fail
- [ ] Notes: _______________________

---

### **TC-014: Error Handling - Network Error**

**Objective**: Memverifikasi handling untuk network error

**Steps**:
1. Buka http://localhost:8000
2. Buka Developer Tools (F12) → Network tab
3. Set network throttling ke "Offline"
4. Jalankan API call dari console:
   ```javascript
   window.axios.get('/api/v1/examples/success')
     .catch(error => {
       console.log('Network Error:', error.message);
       alert('Network error handled!');
     });
   ```

**Expected Results**:
- ✅ Error ditangani dengan baik
- ✅ User-friendly error message
- ✅ Tidak ada crash atau unhandled error

**Actual Results**: 
- [ ] Pass
- [ ] Fail
- [ ] Notes: _______________________

---

### **TC-015: Multiple Browser Test**

**Objective**: Memverifikasi kompatibilitas dengan berbagai browser

**Steps**:
1. Test di Chrome/Edge
2. Test di Firefox
3. Test di Safari (jika tersedia)
4. Verifikasi semua fitur berfungsi sama

**Expected Results**:
- ✅ Semua browser menampilkan UI dengan benar
- ✅ API calls berfungsi di semua browser
- ✅ Tidak ada browser-specific errors

**Actual Results**: 
- [ ] Pass
- [ ] Fail
- [ ] Notes: _______________________

---

## 📊 **Test Summary**

### **Test Execution Log**

| Test Case ID | Test Case Name | Status | Tester | Date | Notes |
|--------------|----------------|--------|--------|------|-------|
| TC-001 | Home Page Load | ⬜ | | | |
| TC-002 | Frontend Assets Load | ⬜ | | | |
| TC-003 | API Integration - Success | ⬜ | | | |
| TC-004 | API Integration - Error | ⬜ | | | |
| TC-005 | API Integration - Not Found | ⬜ | | | |
| TC-006 | Authentication - Login | ⬜ | | | |
| TC-007 | Authentication - Get User | ⬜ | | | |
| TC-008 | File Upload | ⬜ | | | |
| TC-009 | Responsive Design | ⬜ | | | |
| TC-010 | Dark Mode | ⬜ | | | |
| TC-011 | Console Error Check | ⬜ | | | |
| TC-012 | Network Performance | ⬜ | | | |
| TC-013 | CORS Configuration | ⬜ | | | |
| TC-014 | Network Error Handling | ⬜ | | | |
| TC-015 | Multiple Browser Test | ⬜ | | | |

**Legend**:
- ✅ Pass
- ❌ Fail
- ⬜ Not Tested
- ⚠️ Blocked

---

## 🐛 **Bug Report Template**

### **Bug #001**

**Title**: [Short description]

**Severity**: 
- [ ] Critical
- [ ] High
- [ ] Medium
- [ ] Low

**Priority**:
- [ ] P0 (Urgent)
- [ ] P1 (High)
- [ ] P2 (Medium)
- [ ] P3 (Low)

**Test Case**: TC-XXX

**Steps to Reproduce**:
1. 
2. 
3. 

**Expected Result**: 

**Actual Result**: 

**Screenshots**: [Attach if available]

**Browser**: 
**OS**: 
**Date**: 

---

## 📝 **Test Notes**

### **General Notes**
- 

### **Issues Found**
- 

### **Suggestions**
- 

---

## ✅ **Sign-off**

**Tester Name**: _______________________

**Date**: _______________________

**Status**: 
- [ ] All Tests Passed
- [ ] Tests Passed with Minor Issues
- [ ] Tests Failed - Needs Fix

**Approved By**: _______________________

**Date**: _______________________

---

## 📚 **References**

- **API Documentation**: `backend/API-DOCUMENTATION.md`
- **Frontend-Backend Integration**: `backend/FRONTEND-BACKEND-INTEGRATION.md`
- **Development Guide**: `backend/DEVELOPMENT-GUIDE.md`

---

**Happy Testing! 🧪**

