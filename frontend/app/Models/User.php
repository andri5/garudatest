<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The connection name for the model.
     * Bisa diubah sesuai database yang digunakan (mysql, sqlsrv, dll)
     *
     * @var string|null
     */
    protected $connection = null; // null = default connection

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users'; // Default Laravel table, bisa diubah sesuai kebutuhan

    /**
     * Connection name untuk JWT (disimpan di custom claim)
     * 
     * @var string|null
     */
    public $jwtConnection = null;

    /**
     * Table name untuk JWT (disimpan di custom claim)
     * 
     * @var string|null
     */
    public $jwtTable = null;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id'; // Default 'id', bisa diubah ke 'id_user' jika perlu

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return string
     */
    public function getJWTIdentifier()
    {
        $key = $this->getKey();
        // Pastikan selalu return string (JWT membutuhkan string)
        return $key !== null ? (string) $key : '';
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'conn' => $this->jwtConnection ?? $this->connection ?? 'mysql', // Connection name
            'tbl' => $this->jwtTable ?? $this->table ?? 'users', // Table name
        ];
    }
}
