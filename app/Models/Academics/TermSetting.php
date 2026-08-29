<?php

namespace App\Models\Academics;

class TermSetting extends AcademicModel
{
    protected $table = 'term';

    public $timestamps = false;

    protected function casts(): array
    {
        return ['start_date' => 'date', 'end_date' => 'date'];
    }

    public function getIsActiveAttribute($value): bool
    {
        return in_array(strtolower((string) $value), ['1', 'yes', 'active'], true);
    }
}
