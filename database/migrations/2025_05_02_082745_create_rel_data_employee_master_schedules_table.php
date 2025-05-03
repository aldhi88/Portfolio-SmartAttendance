<?php

use App\Models\DataEmployee;
use App\Models\MasterSchedule;
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
        Schema::create('rel_data_employee_master_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MasterSchedule::class)->constrained();
            $table->foreignIdFor(DataEmployee::class)->constrained();
            $table->dateTime('effective_at');
            $table->dateTime('expired_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rel_data_employee_master_schedules');
    }
};
