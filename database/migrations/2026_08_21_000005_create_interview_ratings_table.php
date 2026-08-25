<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('interview_ratings')) return;
        Schema::create('interview_ratings', function (Blueprint $table): void {
            $table->id();
            $table->string('candidate_name');
            $table->date('interview_date');
            $table->string('position_applied');
            $table->string('salary_expectation')->nullable();
            foreach (['appearance_rating','communication_rating','reasoning_rating','education_rating','job_knowledge_rating','work_experience_rating','general_knowledge_rating','iq_level_rating','pose_maturity_rating','personality_rating'] as $field) $table->unsignedTinyInteger($field)->default(0);
            $table->unsignedSmallInteger('total_points')->default(0);
            $table->string('final_decision')->default('pending');
            $table->timestamps();
            $table->index(['candidate_name', 'final_decision']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_ratings');
    }
};
