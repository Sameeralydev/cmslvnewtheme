<?php

namespace App\Models\Hrms;

use Illuminate\Database\Eloquent\Model;

class JobOffer extends Model
{
    protected $table = 'job_offers';
    protected $guarded = ['id'];
    protected function casts(): array
    {
        return ['offer_date' => 'date', 'joining_date' => 'date', 'basic_salary' => 'decimal:2'];
    }
}
