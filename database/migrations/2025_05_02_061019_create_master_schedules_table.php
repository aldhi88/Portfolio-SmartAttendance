<?php

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
        Schema::create('master_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 16);
            $table->string('name', 128);
            $table->time('checkin_time');
            $table->time('work_time');
            $table->time('checkin_deadline_time');
            $table->time('checkout_time');
            $table->enum('type', ['Tetap','Rotasi','Bebas']);
            $table->json('day_work')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_schedules');
    }
};
