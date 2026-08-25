<?php

namespace App\Models\Hrms;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $table = 'job_applications';
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'qualifications' => 'array',
            'previous_jobs' => 'array',
            'recent_experience' => 'array',
            'written_test_marks' => 'float',
            'written_test_total' => 'float',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
