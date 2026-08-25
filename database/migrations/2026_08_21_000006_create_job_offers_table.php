<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('job_offers')) return;
        Schema::create('job_offers', function (Blueprint $table): void {
            $table->id();
            $table->string('reference_no')->nullable()->unique();
            $table->string('candidate_name');
            $table->string('position');
            $table->string('department');
            $table->date('offer_date');
            $table->date('joining_date');
            $table->decimal('basic_salary', 12, 2);
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->index(['candidate_name', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_offers');
    }
};
