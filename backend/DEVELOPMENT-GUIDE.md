# 🚀 Development Guide

**Panduan lengkap untuk memulai development aplikasi.**

---

## 📋 **Struktur Project**

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/          # API Controllers
│   │   └── Middleware/       # Custom Middleware
│   ├── Models/               # Eloquent Models
│   └── Console/
│       └── Commands/         # Artisan Commands
├── routes/
│   ├── api.php              # API Routes
│   └── web.php              # Web Routes
├── resources/
│   ├── views/               # Blade Templates
│   ├── js/                  # Frontend JavaScript
│   └── css/                 # Frontend CSS
├── database/
│   ├── migrations/          # Database Migrations
│   └── seeders/             # Database Seeders
└── config/                  # Configuration Files
```

---

## 🛠️ **Workflow Development**

### **1. Membuat API Endpoint Baru**

#### **Step 1: Buat Controller**

```bash
docker-compose exec app php artisan make:controller Api/YourController
```

#### **Step 2: Buat Route**

Edit `routes/api.php`:

```php
use App\Http\Controllers\Api\YourController;

Route::prefix('v1')->group(function () {
    Route::get('/your-endpoint', [YourController::class, 'index']);
    Route::post('/your-endpoint', [YourController::class, 'store']);
});
```

#### **Step 3: Implementasi Controller**

Edit `app/Http/Controllers/Api/YourController.php`:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class YourController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        // Your logic here
        return $this->success([
            'data' => 'Your data here'
        ], 'Operation successful');
    }

    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'field' => 'required|string',
        ]);

        // Your logic here
        return $this->success([
            'id' => 1,
            'field' => $validated['field']
        ], 'Created successfully', 201);
    }
}
```

#### **Step 4: Test API**

```bash
# Test dengan curl
curl http://localhost:8000/api/v1/your-endpoint

# Atau buka di browser
# http://localhost:8000/api/v1/your-endpoint
```

---

### **2. Membuat Database Migration**

#### **Step 1: Buat Migration**

```bash
docker-compose exec app php artisan make:migration create_your_table
```

#### **Step 2: Edit Migration**

Edit file di `database/migrations/YYYY_MM_DD_create_your_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('your_table', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('your_table');
    }
};
```

#### **Step 3: Run Migration**

```bash
docker-compose exec app php artisan migrate
```

---

### **3. Membuat Model**

#### **Step 1: Buat Model**

```bash
docker-compose exec app php artisan make:model YourModel
```

#### **Step 2: Edit Model**

Edit `app/Models/YourModel.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YourModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
```

---

### **4. Frontend Development**

#### **Edit JavaScript**

Edit `resources/js/app.js`:

```javascript
import './bootstrap';

// Your JavaScript code here
console.log('App loaded');

// Example: API call
window.axios.get('/api/v1/examples/success')
    .then(response => {
        console.log('Success:', response.data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
```

#### **Edit CSS**

Edit `resources/css/app.css`:

```css
/* Your custom styles */
.custom-class {
    color: blue;
}
```

#### **Rebuild Frontend**

Setelah edit JavaScript/CSS:

```bash
docker-compose exec app npm run build
```

Atau untuk development dengan hot reload:

```bash
# Di terminal terpisah
docker-compose exec app npm run dev
# Akses: http://localhost:5173
```

---

### **5. Edit Views (Blade Templates)**

Edit `resources/views/welcome.blade.php` atau buat view baru:

```blade
<!DOCTYPE html>
<html>
<head>
    <title>Your Page</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <h1>Your Content</h1>
    
    <script>
        // Your JavaScript
    </script>
</body>
</html>
```

---

## 🧪 **Testing**

### **Test API Endpoints**

```bash
# Success endpoint
curl http://localhost:8000/api/v1/examples/success

# Error endpoint
curl http://localhost:8000/api/v1/examples/error

# With authentication
curl -H "Authorization: Bearer YOUR_TOKEN" \
     http://localhost:8000/api/v1/auth/me
```

### **Test Database Connection**

```bash
docker-compose exec app php artisan db:test
```

### **Run Migrations**

```bash
docker-compose exec app php artisan migrate
```

### **Rollback Migration**

```bash
docker-compose exec app php artisan migrate:rollback
```

---

## 📝 **Best Practices**

### **1. API Response Format**

Gunakan trait `ApiResponse` untuk konsistensi:

```php
use App\Traits\ApiResponse;

class YourController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return $this->success($data, 'Message');
    }

    public function error()
    {
        return $this->error('Error message', 400);
    }
}
```

### **2. Validation**

Selalu validasi input:

```php
$validated = $request->validate([
    'email' => 'required|email',
    'password' => 'required|min:8',
]);
```

### **3. Database Connections**

Gunakan connection yang sesuai:

```php
// MySQL (default)
DB::connection('mysql')->table('users')->get();

// SQL Server 1
DB::connection('sqlsrv')->table('users')->get();

// SQL Server 2
DB::connection('sqlsrv2')->table('users')->get();
```

### **4. Error Handling**

Gunakan try-catch untuk error handling:

```php
try {
    // Your code
} catch (\Exception $e) {
    return $this->error($e->getMessage(), 500);
}
```

---

## 🔧 **Useful Commands**

### **Laravel Artisan**

```bash
# List all commands
docker-compose exec app php artisan list

# Create controller
docker-compose exec app php artisan make:controller ControllerName

# Create model
docker-compose exec app php artisan make:model ModelName

# Create migration
docker-compose exec app php artisan make:migration migration_name

# Run migrations
docker-compose exec app php artisan migrate

# Rollback migration
docker-compose exec app php artisan migrate:rollback

# Clear cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# Test database
docker-compose exec app php artisan db:test
```

### **Composer**

```bash
# Install dependencies
docker-compose exec app composer install

# Update dependencies
docker-compose exec app composer update

# Add package
docker-compose exec app composer require package/name
```

### **NPM**

```bash
# Install dependencies
docker-compose exec app npm install

# Build assets
docker-compose exec app npm run build

# Dev server (hot reload)
docker-compose exec app npm run dev
```

---

## 🐛 **Debugging**

### **View Logs**

```bash
# Laravel logs
docker-compose exec app tail -f storage/logs/laravel.log

# Or view last 50 lines
docker-compose exec app tail -n 50 storage/logs/laravel.log
```

### **Tinker (Interactive Shell)**

```bash
docker-compose exec app php artisan tinker
```

Contoh di Tinker:

```php
// Test database
DB::connection('mysql')->table('users')->count();

// Test model
$user = App\Models\User::first();
$user->name;
```

---

## 📚 **Dokumentasi Terkait**

- **API Documentation**: `API-DOCUMENTATION.md`
- **Frontend-Backend Integration**: `FRONTEND-BACKEND-INTEGRATION.md`
- **Database Setup**: `SETUP-DATABASE-LOCAL.md`
- **Troubleshooting**: `TROUBLESHOOTING-FRONTEND.md`

---

## 🎯 **Quick Start Checklist**

- [ ] Pahami struktur project
- [ ] Setup development environment
- [ ] Test API endpoints
- [ ] Test database connections
- [ ] Buat endpoint pertama
- [ ] Buat migration pertama
- [ ] Test frontend-backend integration

---

**Selamat Development! 🎉**

