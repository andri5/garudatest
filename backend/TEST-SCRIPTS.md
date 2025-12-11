# 🧪 Test Scripts - Quick Reference

**Script JavaScript untuk testing manual di browser console**

---

## 🚀 **Quick Test Scripts**

### **1. Test API Success Endpoint**

```javascript
window.axios.get('/api/v1/examples/success')
  .then(response => {
    console.log('✅ Success:', response.data);
    alert('API Call Success! Check console.');
  })
  .catch(error => {
    console.error('❌ Error:', error);
    alert('API Call Failed!');
  });
```

---

### **2. Test API Error Endpoint**

```javascript
window.axios.get('/api/v1/examples/error')
  .then(response => {
    console.log('Response:', response.data);
  })
  .catch(error => {
    console.log('Error Response:', error.response?.data);
    alert('Error handled correctly!');
  });
```

---

### **3. Test API Not Found**

```javascript
window.axios.get('/api/v1/examples/not-found')
  .then(response => {
    console.log('Response:', response.data);
  })
  .catch(error => {
    console.log('404 Error:', error.response?.status, error.response?.data);
    alert('404 handled correctly!');
  });
```

---

### **4. Test Login (MySQL)**

```javascript
window.axios.post('/api/v1/auth/login-mysql', {
  email: 'test@example.com',
  password: 'password'
})
.then(response => {
  console.log('✅ Login Success:', response.data);
  if (response.data.data?.token) {
    localStorage.setItem('token', response.data.data.token);
    alert('Login successful! Token saved.');
  }
})
.catch(error => {
  console.log('❌ Login Error:', error.response?.data);
  alert('Login failed!');
});
```

---

### **5. Test Get Current User**

```javascript
const token = localStorage.getItem('token');
if (!token) {
  alert('Please login first!');
} else {
  window.axios.get('/api/v1/auth/me', {
    headers: {
      'Authorization': `Bearer ${token}`
    }
  })
  .then(response => {
    console.log('✅ User Data:', response.data);
    alert('User data retrieved!');
  })
  .catch(error => {
    console.log('❌ Error:', error.response?.data);
    alert('Failed to get user data!');
  });
}
```

---

### **6. Test Logout**

```javascript
const token = localStorage.getItem('token');
if (!token) {
  alert('Please login first!');
} else {
  window.axios.post('/api/v1/auth/logout', {}, {
    headers: {
      'Authorization': `Bearer ${token}`
    }
  })
  .then(response => {
    console.log('✅ Logout Success:', response.data);
    localStorage.removeItem('token');
    alert('Logout successful!');
  })
  .catch(error => {
    console.log('❌ Logout Error:', error.response?.data);
    alert('Logout failed!');
  });
}
```

---

### **7. Test File Upload**

```javascript
// Create file input
const fileInput = document.createElement('input');
fileInput.type = 'file';
fileInput.accept = 'image/*';

fileInput.onchange = async (e) => {
  const file = e.target.files[0];
  if (!file) return;
  
  const formData = new FormData();
  formData.append('file', file);
  
  const token = localStorage.getItem('token');
  if (!token) {
    alert('Please login first!');
    return;
  }
  
  try {
    const response = await window.axios.post('/api/v1/upload', formData, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'multipart/form-data'
      }
    });
    console.log('✅ Upload Success:', response.data);
    alert('File uploaded!');
  } catch (error) {
    console.log('❌ Upload Error:', error.response?.data);
    alert('Upload failed!');
  }
};

fileInput.click();
```

---

### **8. Test Multiple API Calls**

```javascript
// Test multiple endpoints at once
Promise.all([
  window.axios.get('/api/v1/examples/success'),
  window.axios.get('/api/v1/examples/error'),
])
.then(responses => {
  console.log('✅ All calls successful:', responses);
  alert('All API calls successful!');
})
.catch(error => {
  console.log('❌ Some calls failed:', error);
  alert('Some API calls failed!');
});
```

---

### **9. Check CORS Headers**

```javascript
window.axios.get('/api/v1/examples/success')
  .then(response => {
    console.log('Response Headers:', response.headers);
    console.log('CORS Headers:', {
      'Access-Control-Allow-Origin': response.headers['access-control-allow-origin'],
      'Access-Control-Allow-Methods': response.headers['access-control-allow-methods'],
      'Access-Control-Allow-Headers': response.headers['access-control-allow-headers']
    });
  });
```

---

### **10. Test Error Handling**

```javascript
// Test various error scenarios
const tests = [
  { url: '/api/v1/examples/success', name: 'Success' },
  { url: '/api/v1/examples/error', name: 'Error' },
  { url: '/api/v1/examples/not-found', name: 'Not Found' },
  { url: '/api/v1/invalid-endpoint', name: 'Invalid' }
];

tests.forEach(async (test) => {
  try {
    const response = await window.axios.get(test.url);
    console.log(`✅ ${test.name}:`, response.data);
  } catch (error) {
    console.log(`❌ ${test.name}:`, error.response?.status, error.response?.data);
  }
});
```

---

### **11. Clear All Data**

```javascript
// Clear localStorage
localStorage.clear();
console.log('✅ LocalStorage cleared!');
alert('All data cleared!');
```

---

### **12. Check Token Status**

```javascript
const token = localStorage.getItem('token');
if (token) {
  console.log('✅ Token exists:', token.substring(0, 20) + '...');
  
  // Test if token is valid
  window.axios.get('/api/v1/auth/me', {
    headers: { 'Authorization': `Bearer ${token}` }
  })
  .then(() => console.log('✅ Token is valid'))
  .catch(() => console.log('❌ Token is invalid'));
} else {
  console.log('❌ No token found');
}
```

---

## 📋 **Testing Checklist**

Copy-paste script ini di console untuk quick check:

```javascript
// Quick Test Suite
console.log('🧪 Starting Test Suite...');

// Test 1: API Success
window.axios.get('/api/v1/examples/success')
  .then(r => console.log('✅ TC-003: API Success - PASS'))
  .catch(e => console.log('❌ TC-003: API Success - FAIL', e));

// Test 2: API Error
window.axios.get('/api/v1/examples/error')
  .then(r => console.log('✅ TC-004: API Error - PASS'))
  .catch(e => console.log('✅ TC-004: API Error - PASS (handled)'));

// Test 3: API Not Found
window.axios.get('/api/v1/examples/not-found')
  .then(r => console.log('✅ TC-005: API Not Found - PASS'))
  .catch(e => console.log('✅ TC-005: API Not Found - PASS (404 handled)'));

// Test 4: CORS
window.axios.get('/api/v1/examples/success')
  .then(r => {
    const cors = r.headers['access-control-allow-origin'];
    if (cors) {
      console.log('✅ TC-013: CORS - PASS');
    } else {
      console.log('❌ TC-013: CORS - FAIL (no header)');
    }
  });

console.log('🧪 Test Suite Complete! Check results above.');
```

---

## 💡 **Tips**

1. **Buka Console**: Tekan F12 → Console tab
2. **Copy-Paste**: Copy script dan paste di console
3. **Check Results**: Lihat output di console
4. **Clear Console**: Ctrl+L untuk clear console
5. **Network Tab**: F12 → Network tab untuk melihat API calls

---

**Happy Testing! 🎉**

