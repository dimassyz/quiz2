<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    public $timestamps = false; // Tabel users di dump Anda tidak punya timestamps

    protected $fillable = [
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    // Relasi: Satu User (login) memiliki satu data Validator (role, nama)
    public function validator()
    {
        return $this->hasOne(Validator::class);
    }
}
