<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'id_number',
        'is_admin',
        'is_dispatcher',
    ];

    /**
     * IMPORTANT:
     * Tell Laravel to authenticate using id_number instead of email
     */
    public function getAuthIdentifierName()
    {
        return 'id_number';
    }

    /**
     * Auto-generate a unique id_number on user creation
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            do {
                $user->id_number = random_int(10000, 99999); // 5-digit ID
            } while (
                self::where('id_number', $user->id_number)->exists()
            );
        });
    }

    /**
     * Hidden attributes
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'is_dispatcher' => 'boolean',
    ];
}
