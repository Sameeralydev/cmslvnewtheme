<?php

namespace App\Models\Academics;

class WeekSetting extends AcademicModel
{
    protected $table = 'week';

    public function term()
    {
        return $this->belongsTo(TermSetting::class, 'term_id');
    }

    protected function casts(): array
    {
        return ['term_id' => 'integer', 'start_date' => 'date', 'end_date' => 'date', 'is_holiday' => 'boolean', 'is_exam' => 'boolean', 'is_active' => 'boolean'];
    }
}
