<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    use HasFactory;

    protected $table = 'job_categories';
    public $timestamps = false;

    protected $fillable = [
        'job_category',
    ];

    // Relasi: Satu kategori bisa memiliki banyak lowongan
    public function jobVacancies()
    {
        return $this->hasMany(JobVacancy::class);
    }
}
