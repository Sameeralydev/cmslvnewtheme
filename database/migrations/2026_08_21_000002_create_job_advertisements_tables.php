<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('job_categories')) {
            Schema::create('job_categories', function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->unique('name');
            });
        }
        if (!Schema::hasTable('job_advertisements')) {
            Schema::create('job_advertisements', function (Blueprint $table): void {
                $table->id();
                $table->string('job_title');
                $table->string('category');
                $table->string('location');
                $table->string('type');
                $table->string('salary')->nullable();
                $table->longText('description');
                $table->longText('requirements');
                $table->string('contact_email');
                $table->string('contact_phone')->nullable();
                $table->date('deadline');
                $table->string('status')->default('active');
                $table->string('company_name')->nullable();
                $table->string('company_logo')->nullable();
                $table->timestamps();
                $table->index(['status', 'deadline']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('job_advertisements');
        Schema::dropIfExists('job_categories');
    }
};
