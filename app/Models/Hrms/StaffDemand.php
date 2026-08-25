<?php

namespace App\Models\Hrms;

use Illuminate\Database\Eloquent\Model;

class StaffDemand extends Model
{
    protected $table = 'staff_demand_forms';

    // The legacy CodeIgniter table may not have Laravel timestamp columns.
    public $timestamps = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'demand_date' => 'date',
            'visiting_part_time' => 'boolean',
            'visiting_full_time' => 'boolean',
            'temporary' => 'boolean',
            'permanent' => 'boolean',
        ];
    }

    public function natureOfJob(): string
    {
        return match (true) {
            $this->visiting_part_time => 'visiting_part_time',
            $this->visiting_full_time => 'visiting_full_time',
            $this->temporary => 'temporary',
            $this->permanent => 'permanent',
            default => '',
        };
    }
}
