<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailablePosition extends Model
{
    use HasFactory;

    // Definisikan nama tabel secara eksplisit
    protected $table = 'available_positions';

    // Tabel ini tidak memiliki kolom timestamps
    public $timestamps = false;

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'job_vacancy_id',
        'position',
        'capacity',
        'apply_capacity',
    ];

    // Relasi: Posisi ini milik satu lowongan pekerjaan
    public function jobVacancy()
    {
        return $this->belongsTo(JobVacancy::class);
    }
}
