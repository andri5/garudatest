# 📚 API Documentation - Garuda Test Application

**Base URL**: `http://localhost:8000/api/v1`

## 🔐 Authentication

API ini menggunakan **JWT (JSON Web Token)** untuk authentication. Setelah login berhasil, gunakan token di header untuk request yang memerlukan authentication.

### Header untuk Authenticated Requests

```
Authorization: Bearer {token}
Content-Type: application/json
```

---

## 📋 API Endpoints

### 🔓 Public Endpoints (Tidak Perlu Authentication)

#### 1. Login (SQL Server)

Login user dari database SQL Server (seleksi).

**Endpoint**: `POST /api/v1/auth/login`

**Request Body**:
```json
{
  "username": "your_username",
  "password": "your_password"
}
```

**Response Success (200)**:
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "bearer",
    "expires_in": 3600,
    "user": {
      "id": 1,
      "username": "your_username",
      "name": "User Name",
      "email": "user@example.com"
    }
  }
}
```

**Response Error (401)**:
```json
{
  "success": false,
  "message": "Invalid credentials"
}
```

---

#### 2. Login (MySQL)

Login user dari database MySQL (default connection).

**Endpoint**: `POST /api/v1/auth/login-mysql`

**Request Body**:
```json
{
  "email": "user@example.com",
  "password": "your_password"
}
```

**Response Success (200)**:
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "bearer",
    "expires_in": 3600,
    "user": {
      "id": 1,
      "name": "User Name",
      "email": "user@example.com"
    }
  }
}
```

**Response Error (401)**:
```json
{
  "success": false,
  "message": "Invalid credentials"
}
```

---

### 🔒 Protected Endpoints (Perlu Authentication)

Semua endpoint di bawah ini memerlukan JWT token di header.

#### 3. Get Current User

Mendapatkan informasi user yang sedang login.

**Endpoint**: `GET /api/v1/auth/me`

**Headers**:
```
Authorization: Bearer {token}
```

**Response Success (200)**:
```json
{
  "success": true,
  "message": "User retrieved successfully",
  "data": {
    "id": 1,
    "username": "your_username",
    "name": "User Name",
    "email": "user@example.com"
  }
}
```

**Response Error (401)**:
```json
{
  "success": false,
  "message": "Unauthenticated"
}
```

---

#### 4. Logout

Logout user dan invalidate token.

**Endpoint**: `POST /api/v1/auth/logout`

**Headers**:
```
Authorization: Bearer {token}
```

**Response Success (200)**:
```json
{
  "success": true,
  "message": "Successfully logged out"
}
```

---

#### 5. Refresh Token

Refresh JWT token untuk memperpanjang masa aktif.

**Endpoint**: `POST /api/v1/auth/refresh`

**Headers**:
```
Authorization: Bearer {token}
```

**Response Success (200)**:
```json
{
  "success": true,
  "message": "Token refreshed successfully",
  "data": {
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "bearer",
    "expires_in": 3600
  }
}
```

---

### 📁 File Upload Endpoints

#### 6. Upload File

Upload file ke storage (MinIO/S3).

**Endpoint**: `POST /api/v1/upload`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body** (Form Data):
```
file: [binary file]
path: (optional) custom/path/to/file.jpg
```

**Response Success (200)**:
```json
{
  "success": true,
  "message": "File uploaded successfully",
  "data": {
    "url": "https://minio-dev.kemenkeu.go.id/lpdp-beasiswa-dev/path/to/file.jpg",
    "path": "path/to/file.jpg",
    "filename": "file.jpg",
    "size": 1024,
    "mime_type": "image/jpeg"
  }
}
```

---

#### 7. Delete File

Hapus file dari storage.

**Endpoint**: `DELETE /api/v1/upload`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
  "path": "path/to/file.jpg"
}
```

**Response Success (200)**:
```json
{
  "success": true,
  "message": "File deleted successfully"
}
```

---

#### 8. Get Signed URL

Generate signed URL untuk akses file sementara.

**Endpoint**: `POST /api/v1/upload/signed-url`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
  "path": "path/to/file.jpg",
  "expires_in": 3600
}
```

**Response Success (200)**:
```json
{
  "success": true,
  "message": "Signed URL generated successfully",
  "data": {
    "url": "https://minio-dev.kemenkeu.go.id/lpdp-beasiswa-dev/path/to/file.jpg?X-Amz-Algorithm=...",
    "expires_at": "2025-12-11 15:00:00"
  }
}
```

---

#### 9. Get File as Base64

Mendapatkan file sebagai base64 string (untuk JSON response).

**Endpoint**: `POST /api/v1/upload/get-base64`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
  "path": "path/to/file.jpg"
}
```

**Response Success (200)**:
```json
{
  "success": true,
  "message": "File retrieved successfully",
  "data": {
    "base64": "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD...",
    "mime_type": "image/jpeg",
    "filename": "file.jpg"
  }
}
```

---

#### 10. Get File Direct

Mendapatkan file langsung sebagai image (untuk `<img src="...">`).

**Endpoint**: `GET /api/v1/upload/get?path=path/to/file.jpg`

**Headers**:
```
Authorization: Bearer {token}
```

**Response**: Binary file content dengan Content-Type sesuai file type.

---

### 🧪 Example Endpoints (Testing)

#### 11. Success Example

Contoh response success.

**Endpoint**: `GET /api/v1/examples/success`

**Response (200)**:
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {
    "message": "This is a success response example"
  }
}
```

---

#### 12. Not Found Example

Contoh response 404.

**Endpoint**: `GET /api/v1/examples/not-found`

**Response (404)**:
```json
{
  "success": false,
  "message": "Resource not found"
}
```

---

## 📝 Response Format

Semua API response mengikuti format standar:

### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {
    // Response data
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    // Validation errors (jika ada)
  }
}
```

## 🔢 HTTP Status Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

## 🧪 Testing API

### Menggunakan cURL

**Login**:
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"your_username","password":"your_password"}'
```

**Get Current User**:
```bash
curl -X GET http://localhost:8000/api/v1/auth/me \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Menggunakan Postman

1. Import collection dari file `postman_collection.json` (jika ada)
2. Set base URL: `http://localhost:8000/api/v1`
3. Untuk authenticated requests, set header:
   - Key: `Authorization`
   - Value: `Bearer {token}`

## 🔗 Related Documentation

- [NEXT-STEPS.md](./NEXT-STEPS.md) - Langkah selanjutnya setelah instalasi
- [plan-installation.md](../plan-installation.md) - Dokumentasi instalasi lengkap

