<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('term')) {
            Schema::table('term', function (Blueprint $table): void {
                if (! Schema::hasColumn('term', 'start_date')) $table->date('start_date')->nullable();
                if (! Schema::hasColumn('term', 'end_date')) $table->date('end_date')->nullable();
            });
        }
        if (Schema::hasTable('week')) {
            Schema::table('week', function (Blueprint $table): void {
                if (! Schema::hasColumn('week', 'is_holiday')) $table->boolean('is_holiday')->default(false);
                if (! Schema::hasColumn('week', 'is_exam')) $table->boolean('is_exam')->default(false);
            });
        }
        if (Schema::hasTable('day')) {
            Schema::table('day', function (Blueprint $table): void {
                if (! Schema::hasColumn('day', 'period')) $table->string('period', 50)->nullable();
                if (! Schema::hasColumn('day', 'is_working_day')) $table->boolean('is_working_day')->default(true);
            });
        }
    }

    public function down(): void
    {
        foreach (['term' => ['start_date', 'end_date'], 'week' => ['is_holiday', 'is_exam'], 'day' => ['period', 'is_working_day']] as $table => $columns) {
            if (! Schema::hasTable($table)) continue;
            Schema::table($table, function (Blueprint $blueprint) use ($table, $columns): void {
                foreach ($columns as $column) if (Schema::hasColumn($table, $column)) $blueprint->dropColumn($column);
            });
        }
    }
};
