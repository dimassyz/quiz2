<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// PASTIKAN MENGGUNAKAN INI
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

// PASTIKAN EXTENDS Authenticatable
class Society extends Authenticatable
{
    use HasFactory, HasApiTokens;

    public $timestamps = false;

    protected $fillable = [
        'id_card_number',
        'password',
        'name',
        'born_date',
        'gender',
        'address',
        'regional_id',
    ];

    protected $hidden = [
        'password',
        'login_tokens',
    ];

    // Relasi ke Regional
    public function regional()
    {
        return $this->belongsTo(Regional::class);
    }

    // Relasi ke Validation
    public function validation()
    {
        return $this->hasOne(Validation::class);
    }

    // Relasi ke JobApplySociety (Lamaran)
    public function applications()
    {
        return $this->hasMany(JobApplySociety::class);
    }
}
