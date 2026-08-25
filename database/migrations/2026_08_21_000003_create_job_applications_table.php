<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('job_applications')) return;
        Schema::create('job_applications', function (Blueprint $table): void {
            $table->id();
            $table->string('full_name');
            $table->string('father_husband_name');
            $table->string('cnic', 15);
            $table->string('position_applied');
            $table->string('min_salary_acceptable');
            $table->string('nationality')->default('Pakistani');
            $table->string('religion');
            $table->string('gender');
            $table->decimal('height_ft', 8, 2)->nullable();
            $table->decimal('height_inches', 8, 2)->nullable();
            $table->decimal('weight_kg', 8, 2)->nullable();
            $table->string('marital_status');
            $table->date('date_of_birth');
            $table->string('place_of_birth');
            $table->text('mailing_address');
            $table->string('contact_numbers');
            $table->string('photograph')->nullable();
            $table->json('qualifications')->nullable();
            $table->json('previous_jobs')->nullable();
            $table->json('recent_experience')->nullable();
            $table->string('status')->default('Pending');
            $table->timestamps();
            $table->index(['position_applied', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
