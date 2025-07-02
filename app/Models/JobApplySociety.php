<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplySociety extends Model
{
    use HasFactory;

    protected $table = 'job_apply_societies';
    public $timestamps = false;

    protected $fillable = [
        'notes',
        'date',
        'society_id',
        'job_vacancy_id',
    ];

    // Relasi: Lamaran ini dibuat oleh satu Society
    public function society()
    {
        return $this->belongsTo(Society::class);
    }

    // Relasi: Lamaran ini untuk satu JobVacancy
    public function jobVacancy()
    {
        return $this->belongsTo(JobVacancy::class);
    }

    // Relasi: Satu lamaran bisa mencakup beberapa posisi
    public function appliedPositions()
    {
        return $this->hasMany(JobApplyPosition::class, 'job_apply_societies_id');
    }
}
