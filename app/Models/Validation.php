<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Validation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'society_id',
        'job_category_id',
        'work_experience',
        'job_position',
        'reason_accepted',
        'status',
        'validator_id',
        'validator_notes'
    ];

    // Relasi: Dimiliki oleh satu Society
    public function society()
    {
        return $this->belongsTo(Society::class);
    }

    // Relasi: Termasuk dalam satu JobCategory
    public function jobCategory()
    {
        return $this->belongsTo(JobCategory::class);
    }

    // Relasi: Ditangani oleh satu Validator
    public function validator()
    {
        return $this->belongsTo(Validator::class);
    }
}
