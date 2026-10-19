<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // <-- Asegura que esta línea esté presente

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // <-- Verifica que incluya HasApiTokens

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // <-- Nueva columna añadida
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
