<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('staff_recruitment_orders')) return;
        Schema::create('staff_recruitment_orders', function (Blueprint $table): void {
            $table->id();
            $table->string('employee_name');
            $table->string('position');
            $table->date('order_date');
            $table->string('department');
            $table->string('designation');
            $table->string('employee_cnic')->nullable();
            $table->string('personal_phone')->nullable();
            $table->timestamps();
            $table->index(['employee_name', 'order_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_recruitment_orders');
    }
};
