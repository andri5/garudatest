<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Login user from SQL Server (seleksi)
     * Default login method - menggunakan sqlsrv_seleksi connection
     */
    public function login(Request $request): JsonResponse
    {
        return $this->loginFromDatabase(
            $request,
            self::$sqlsrv_seleksi,
            'user'
        );
    }

    /**
     * Login user from MySQL
     * Untuk login dari database MySQL (default connection)
     * 
     * Contoh penggunaan: POST /api/v1/auth/login-mysql
     */
    public function loginMysql(Request $request): JsonResponse
    {
        return $this->loginFromDatabase(
            $request,
            null, // null = MySQL default connection
            'users' // Default Laravel users table
        );
    }

    /**
     * Generic login method untuk berbagai database
     * 
     * @param Request $request
     * @param string|null $connection Connection name (null = MySQL default)
     * @param string $table Table name
     * @return JsonResponse
     */
    private function loginFromDatabase(Request $request, ?string $connection, string $table): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        try {
            // Query user dari connection yang sesuai
            $query = $connection 
                ? DB::connection($connection)->table($table)
                : DB::table($table);
            
            $user = $query->where('email', $request->email)->first();

            if (!$user || !$this->verifyPassword($request->password, $user)) {
                return $this->unauthorizedResponse('Invalid email or password');
            }

            $userId = (string) ($user->id_user ?? $user->id);
            if (!$userId) {
                return $this->serverErrorResponse('User ID not found');
            }

            $token = JWTAuth::fromUser($this->createUserModel($user, $userId, $connection, $table));

            return $this->successResponse([
                'token' => $token,
                'tokenType' => 'Bearer',
                'expiresIn' => config('jwt.ttl', 60) * 60,
                'user' => [
                    'id' => $userId,
                    'email' => $user->email,
                    'name' => $user->nama_lengkap ?? $user->nama_panggilan ?? $user->name ?? 'User',
                ],
            ], 'Login successful');

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return $this->serverErrorResponse('JWT Error: ' . $e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error during login', [
                'connection' => self::$sqlsrv_seleksi,
                'error' => $e->getMessage(),
            ]);
            return $this->serverErrorResponse(
                config('app.debug') 
                    ? 'Database error: ' . $e->getMessage() 
                    : 'Database connection error'
            );
        } catch (\Exception $e) {
            Log::error('Login error', ['error' => $e->getMessage()]);
            return $this->serverErrorResponse('Failed to login');
        }
    }

    /**
     * Get authenticated user
     */
    public function me(): JsonResponse
    {
        try {
            $userId = JWTAuth::parseToken()->getPayload()->get('sub');
            if (!$userId) {
                return $this->unauthorizedResponse('User not found in token');
            }

            // Query user - untuk SQL Server hanya cari id_user
            $user = DB::connection(self::$sqlsrv_seleksi)
                ->table('user')
                ->where('id_user', $userId)
                ->first();

            if (!$user) {
                return $this->unauthorizedResponse('User not found');
            }

            return $this->successResponse([
                'id' => $user->id_user ?? $user->id,
                'email' => $user->email,
                'name' => $user->nama_lengkap ?? $user->nama_panggilan ?? $user->name ?? 'User',
            ], 'User retrieved successfully');
        } catch (\Exception $e) {
            return $this->unauthorizedResponse('Invalid token');
        }
    }

    /**
     * Logout user
     */
    public function logout(): JsonResponse
    {
        try {
            JWTAuth::parseToken()->invalidate();
            return $this->successResponse(null, 'Logout successful');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to logout', 500);
        }
    }

    /**
     * Refresh token
     */
    public function refresh(): JsonResponse
    {
        try {
            $token = JWTAuth::parseToken()->refresh();
            return $this->successResponse([
                'token' => $token,
                'tokenType' => 'Bearer',
                'expiresIn' => config('jwt.ttl', 60) * 60,
            ], 'Token refreshed successfully');
        } catch (\Exception $e) {
            return $this->unauthorizedResponse('Failed to refresh token');
        }
    }

    /**
     * Verify password (supports bcrypt and legacy md5+salt)
     */
    private function verifyPassword(string $password, object $user): bool
    {
        $storedPassword = $user->password;
        $isBcrypt = in_array($user->is_password_hash_changed ?? null, ['1', 1, true], true)
            || preg_match('/^\$2[ay]\$/', $storedPassword);

        if ($isBcrypt) {
            return Hash::check($password, $storedPassword);
        }

        $salt = $user->salt ?? '';
        $hashedPassword = $salt ? md5($salt . $password) : $password;

        return $storedPassword === $hashedPassword
            || $storedPassword === md5($password . $salt)
            || $storedPassword === $password;
    }

    /**
     * Create User model instance for JWT
     * 
     * @param object $user User data from database
     * @param string $userId User ID
     * @param string|null $connection Connection name (default: sqlsrv_seleksi)
     * @param string|null $table Table name (default: user)
     */
    private function createUserModel(object $user, string $userId, ?string $connection = null, ?string $table = null): \App\Models\User
    {
        $connection = $connection ?? self::$sqlsrv_seleksi;
        $table = $table ?? 'user';
        $primaryKeyName = isset($user->id_user) ? 'id_user' : 'id';
        
        $userModel = new \App\Models\User();
        $userModel->setConnection($connection);
        $userModel->setTable($table);
        $userModel->setKeyName($primaryKeyName);
        $userModel->{$primaryKeyName} = $userId;
        $userModel->email = $user->email ?? '';
        $userModel->name = $user->nama_lengkap ?? $user->nama_panggilan ?? $user->name ?? 'User';
        $userModel->exists = true;
        
        // Set JWT custom claims data
        $userModel->jwtConnection = $connection;
        $userModel->jwtTable = $table;

        return $userModel;
    }
}