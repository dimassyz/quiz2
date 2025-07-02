<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Validator extends Model
{
    use HasFactory;

    protected $table = 'validators';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'role',
        'name',
    ];

    // Relasi: Data validator ini milik satu User (untuk login)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
