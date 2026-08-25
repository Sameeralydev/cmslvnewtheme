<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        foreach ([
            'school_performance_report', 'monthly_appraisal', 'monthly_appraisal_management',
            'annual_confidential_report', 'annual_confidential_report_management',
            'non_conference_notice_reply', 'clearance_forms', 'exit_interviews',
            'final_settlement_form', 'show_cause_notices', 'inquiry_processes',
        ] as $tableName) {
            if (Schema::hasTable($tableName)) continue;
            Schema::create($tableName, function (Blueprint $table): void {
                $table->id();
                $table->string('person_name')->nullable();
                $table->string('reference_no')->nullable();
                $table->date('form_date')->nullable();
                $table->unsignedInteger('total_score')->default(0);
                $table->string('grade')->nullable();
                $table->json('payload')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        foreach ([
            'inquiry_processes', 'show_cause_notices', 'final_settlement_form', 'exit_interviews',
            'clearance_forms', 'non_conference_notice_reply', 'annual_confidential_report_management',
            'annual_confidential_report', 'monthly_appraisal_management', 'monthly_appraisal',
            'school_performance_report',
        ] as $tableName) Schema::dropIfExists($tableName);
    }
};
