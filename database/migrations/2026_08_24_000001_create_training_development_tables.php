<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('training_agenda', function (Blueprint $table): void {
            $table->id();
            $table->string('prepared_by');
            $table->json('agenda_items')->nullable();
            $table->timestamps();
        });
        Schema::create('training_need_analysis', function (Blueprint $table): void {
            $table->id(); $table->string('name'); $table->string('designation'); $table->text('relevant_pod')->nullable();
            $table->string('campus')->nullable(); $table->date('tna_date'); $table->text('major_task')->nullable();
            $table->text('target_competencies')->nullable(); $table->string('mode_of_training')->nullable(); $table->text('required_arrangement')->nullable();
            $table->text('school_benefits')->nullable(); $table->text('last_training_program')->nullable(); $table->text('suggest_training')->nullable();
            $table->string('requester_sign')->nullable(); $table->string('hod_hrm_admin_sign')->nullable(); $table->timestamps();
        });
        Schema::create('training_evaluation_form', function (Blueprint $table): void {
            $table->id(); $table->string('participant_name'); $table->string('designation')->nullable(); $table->string('dep_campus')->nullable();
            $table->date('date_of_event')->nullable(); $table->string('venue')->nullable(); $table->string('topic_of_training')->nullable(); $table->string('trainer_company')->nullable();
            for ($i = 1; $i <= 14; $i++) $table->text('q'.$i)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_evaluation_form'); Schema::dropIfExists('training_need_analysis'); Schema::dropIfExists('training_agenda');
    }
};
