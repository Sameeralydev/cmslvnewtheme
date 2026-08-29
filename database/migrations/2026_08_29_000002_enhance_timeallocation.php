<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('timeallocation')) return;
        Schema::table('timeallocation', function (Blueprint $table): void {
            if (! Schema::hasColumn('timeallocation', 'slot_type')) $table->string('slot_type', 40)->default('regular')->after('slot');
            if (! Schema::hasColumn('timeallocation', 'wing_shift')) $table->string('wing_shift', 60)->nullable()->after('slot_type');
            if (! Schema::hasColumn('timeallocation', 'applies_days')) $table->string('applies_days', 100)->nullable()->after('wing_shift');
            if (! Schema::hasColumn('timeallocation', 'special_schedule')) $table->string('special_schedule', 40)->nullable()->after('applies_days');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('timeallocation')) return;
        Schema::table('timeallocation', function (Blueprint $table): void {
            foreach (['slot_type', 'wing_shift', 'applies_days', 'special_schedule'] as $column) {
                if (Schema::hasColumn('timeallocation', $column)) $table->dropColumn($column);
            }
        });
    }
};
