<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('job_applications')) return;
        Schema::table('job_applications', function (Blueprint $table): void {
            if (!Schema::hasColumn('job_applications', 'written_test_marks')) $table->decimal('written_test_marks', 8, 2)->default(0);
            if (!Schema::hasColumn('job_applications', 'written_test_total')) $table->decimal('written_test_total', 8, 2)->default(100);
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('job_applications')) return;
        Schema::table('job_applications', function (Blueprint $table): void {
            if (Schema::hasColumn('job_applications', 'written_test_marks')) $table->dropColumn('written_test_marks');
            if (Schema::hasColumn('job_applications', 'written_test_total')) $table->dropColumn('written_test_total');
        });
    }
};
