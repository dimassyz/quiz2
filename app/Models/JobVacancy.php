<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobVacancy extends Model
{
    use HasFactory;

    protected $table = 'job_vacancies';
    public $timestamps = false;

    protected $fillable = [
        'job_category_id',
        'company',
        'address',
        'description',
    ];

    // Relasi: Lowongan ini termasuk dalam satu kategori
    public function jobCategory()
    {
        return $this->belongsTo(JobCategory::class);
    }

    // Relasi: Lowongan ini memiliki banyak posisi yang tersedia
    public function availablePositions()
    {
        return $this->hasMany(AvailablePosition::class);
    }

    // Relasi: Lowongan ini bisa memiliki banyak lamaran
    public function applications()
    {
        return $this->hasMany(JobApplySociety::class);
    }
}
