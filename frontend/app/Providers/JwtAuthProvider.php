<?php

namespace App\Providers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Providers\Auth\Illuminate;

class JwtAuthProvider extends Illuminate
{
    /**
     * Get a user by the given ID.
     *
     * @param  mixed  $id
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function byId($id)
    {
        // Log bahwa byId dipanggil
        \Illuminate\Support\Facades\Log::info('JWT Auth Provider byId called', ['id' => $id]);
        
        try {
            // Ambil custom claims dari JWT token untuk mengetahui connection dan table
            $connection = Controller::$sqlsrv_seleksi; // Default: sqlsrv_seleksi
            $table = 'user'; // Default: user
            
            try {
                // Coba ambil payload dari JWT yang sudah di-parse oleh middleware
                $jwt = app('tymon.jwt.auth');
                
                // Coba ambil token dari request
                $request = \Illuminate\Support\Facades\Request::instance();
                $token = $request->bearerToken() ?? $request->input('token');
                
                if ($token) {
                    try {
                        // Parse token dan ambil payload
                        $payload = $jwt->setToken($token)->getPayload();
                        $connection = $payload->get('conn', $connection);
                        $table = $payload->get('tbl', $table);
                        
                        \Illuminate\Support\Facades\Log::info('JWT Auth Provider: Using custom claims', [
                            'connection' => $connection,
                            'table' => $table,
                            'id' => $id,
                            'all_claims' => $payload->toArray(),
                        ]);
                    } catch (\Exception $parseError) {
                        // Jika parse error, coba decode manual
                        \Illuminate\Support\Facades\Log::warning('JWT Auth Provider: Parse error, trying manual decode', [
                            'error' => $parseError->getMessage(),
                        ]);
                    }
                }
            } catch (\Exception $e) {
                // Jika tidak bisa ambil payload, gunakan default
                \Illuminate\Support\Facades\Log::warning('JWT Auth Provider: Cannot get payload, using default', [
                    'error' => $e->getMessage(),
                    'id' => $id,
                    'default_connection' => $connection,
                    'default_table' => $table,
                ]);
            }

            // Log connection dan table yang akan digunakan
            \Illuminate\Support\Facades\Log::info('JWT Auth Provider: Querying user', [
                'connection' => $connection,
                'table' => $table,
                'id' => $id,
            ]);

            // Cari user dari connection yang sesuai
            // Untuk SQL Server (seleksi), gunakan id_user saja
            // Untuk MySQL, coba id_user dulu, lalu id
            $query = DB::connection($connection)->table($table);
            
            if ($connection === Controller::$sqlsrv_seleksi) {
                // SQL Server: hanya cari id_user
                $user = $query->where('id_user', $id)->first();
            } else {
                // MySQL atau connection lain: coba id_user dulu, lalu id
                $user = $query->where(function($q) use ($id) {
                    $q->where('id_user', $id)
                      ->orWhere('id', $id);
                })->first();
            }

            if (!$user) {
                \Illuminate\Support\Facades\Log::warning('JWT Auth Provider: User not found', [
                    'connection' => $connection,
                    'table' => $table,
                    'id' => $id,
                ]);
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
            \Illuminate\Support\Facades\Log::error('JWT Auth Provider Error', [
                'error' => $e->getMessage(),
                'id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }
}

