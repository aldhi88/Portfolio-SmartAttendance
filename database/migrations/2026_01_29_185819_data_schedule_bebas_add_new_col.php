<?php

use App\Models\DataEmployee;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('data_schedules_bebas', function (Blueprint $table) {
            $table->foreignId('data_employee_id')
                ->nullable()
                ->after('master_schedule_id')
                ->constrained('data_employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_schedules_bebas', function (Blueprint $table) {
            // $table->dropColumn(['phone', 'is_active']);
        });
    }
};
