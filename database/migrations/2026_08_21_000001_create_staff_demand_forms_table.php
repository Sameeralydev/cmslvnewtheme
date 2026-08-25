<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('staff_demand_forms')) {
            return;
        }

        Schema::create('staff_demand_forms', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('requester_name');
            $table->string('designation')->default('');
            $table->string('department')->default('');
            $table->unsignedInteger('staff_required');
            $table->unsignedBigInteger('campus');
            $table->date('demand_date');
            $table->string('position');
            $table->boolean('visiting_part_time')->default(false);
            $table->boolean('visiting_full_time')->default(false);
            $table->boolean('temporary')->default(false);
            $table->boolean('permanent')->default(false);
            $table->text('academic_qualifications');
            $table->text('professional_qualifications')->nullable();
            $table->text('role');
            $table->text('experience');
            $table->text('expected_skills')->nullable();
            $table->text('expected_attitude')->nullable();
            $table->text('age_range')->nullable();
            $table->text('salary_range')->nullable();
            $table->string('file_code')->default('');
            $table->unsignedInteger('revision')->default(0);
            $table->string('form_department')->default('');
            $table->timestamps();
            $table->index(['campus', 'requester_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_demand_forms');
    }
};
