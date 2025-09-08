<?php

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
        Schema::create('data_schedules_bebas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MasterSchedule::class)->constrained();
            $table->date('tanggal');
            $table->time('checkin_time');
            $table->time('work_time');
            $table->time('checkin_deadline_time');
            $table->time('checkout_time');
            $table->time('checkout_deadline_time');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_schedules_bebas');
    }
};
