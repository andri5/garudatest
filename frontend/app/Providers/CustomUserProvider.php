<?php

namespace App\Providers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomUserProvider extends EloquentUserProvider
{
    /**
     * Create a new database user provider.
     *
     * @param  \Illuminate\Contracts\Hashing\Hasher  $hasher
     * @param  string  $model
     * @return void
     */
    public function __construct($hasher, $model)
    {
        parent::__construct($hasher, $model);
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        try {
            // Ambil custom claims dari JWT token untuk mengetahui connection dan table
            $connection = Controller::$sqlsrv_seleksi; // Default: sqlsrv_seleksi
            $table = 'user'; // Default: user
            
            try {
                // Coba ambil payload dari JWT yang sudah di-parse
                $request = \Illuminate\Support\Facades\Request::instance();
                $token = $request->bearerToken() ?? $request->input('token');
                
                if ($token) {
                    $jwt = app('tymon.jwt.auth');
                    $payload = $jwt->setToken($token)->getPayload();
                    $connection = $payload->get('conn', $connection);
                    $table = $payload->get('tbl', $table);
                    
                    Log::info('CustomUserProvider: Using custom claims', [
                        'connection' => $connection,
                        'table' => $table,
                        'id' => $identifier,
                    ]);
                }
            } catch (\Exception $e) {
                Log::warning('CustomUserProvider: Cannot get payload, using default', [
                    'error' => $e->getMessage(),
                    'id' => $identifier,
                ]);
            }

            // Query user dari connection yang sesuai
            // Untuk SQL Server (seleksi), gunakan id_user saja
            // Untuk MySQL, coba id_user dulu, lalu id
            $query = DB::connection($connection)->table($table);
            
            if ($connection === Controller::$sqlsrv_seleksi) {
                // SQL Server: hanya cari id_user
                $user = $query->where('id_user', $identifier)->first();
            } else {
                // MySQL atau connection lain: coba id_user dulu, lalu id
                $user = $query->where(function($q) use ($identifier) {
                    $q->where('id_user', $identifier)
                      ->orWhere('id', $identifier);
                })->first();
            }

            if (!$user) {
                return null;
            }

            // Create User model instance
            $userId = (string) ($user->id_user ?? $user->id);
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
        } catch (\Exception $e) {
            Log::error('CustomUserProvider Error', [
                'error' => $e->getMessage(),
                'id' => $identifier,
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }
}

