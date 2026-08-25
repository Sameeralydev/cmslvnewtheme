<?php

namespace App\Models\Hrms;

use Illuminate\Database\Eloquent\Model;

class StaffRecruitmentOrder extends Model
{
    protected $table = 'staff_recruitment_orders';
    protected $guarded = ['id'];
    protected function casts(): array
    {
        return ['order_date' => 'date'];
    }
}
