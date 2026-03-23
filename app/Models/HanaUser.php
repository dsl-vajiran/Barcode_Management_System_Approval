<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class HanaUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'hana';
    protected $table = 'BARCODE_USERS';
    protected $primaryKey = 'ID';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'remember_token',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function getAuthIdentifier()
    {
        // HANA + PDO::CASE_LOWER returns columns as lowercase keys (e.g. "id"),
        // while the physical column name is typically uppercase (e.g. ID).
        return $this->getAttribute('id') ?? $this->getAttribute($this->getAuthIdentifierName());
    }
}

