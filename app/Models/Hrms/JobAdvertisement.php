<?php

namespace App\Models\Hrms;

use Illuminate\Database\Eloquent\Model;

class JobAdvertisement extends Model
{
    protected $table = 'job_advertisements';
    protected $guarded = ['id'];
    protected function casts(): array
    {
        return ['deadline' => 'date', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
    }
}
