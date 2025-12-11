<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UploadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// API Version 1
Route::prefix('v1')->group(function () {
    // Public routes
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']); // Login dari SQL Server (seleksi)
        Route::post('/login-mysql', [AuthController::class, 'loginMysql']); // Login dari MySQL
    });

    // Protected routes (require JWT authentication)
    Route::middleware(['auth:api'])->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/refresh', [AuthController::class, 'refresh']);
            Route::get('/me', [AuthController::class, 'me']);
        });

        // File upload routes
        Route::prefix('upload')->group(function () {
            Route::post('/', [UploadController::class, 'upload']);
            Route::delete('/', [UploadController::class, 'delete']);
            Route::post('/signed-url', [UploadController::class, 'getSignedUrl']); // Generate signed URL jika diperlukan
            Route::post('/get-base64', [UploadController::class, 'getFileBase64']); // Get file sebagai base64 (JSON response)
            Route::get('/get', [UploadController::class, 'getFile']); // Get file langsung sebagai image (untuk <img src="...">)
        });

        // Add your protected API routes here
        // Route::get('/users', [UserController::class, 'index']);
    });

    // Example routes (for testing API response format)
    Route::prefix('examples')->group(function () {
        Route::get('/success', [\App\Http\Controllers\Api\ExampleController::class, 'success']);
        Route::get('/not-found', [\App\Http\Controllers\Api\ExampleController::class, 'notFound']);
    });
});

// Future API versions can be added here:
// Route::prefix('v2')->group(function () {
//     // v2 routes
// });

