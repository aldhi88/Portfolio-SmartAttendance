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
        Schema::create('data_rekaps', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(DataEmployee::class)->constrained();
            $table->foreignIdFor(MasterSchedule::class)->constrained();
            $table->json('absensi');
            $table->date('tgl_rekap');
            $table->string('checkin_time');
            $table->string('checkout_time');
            $table->string('label');
            $table->string('shift')->nullable();
            $table->unsignedInteger('dtg_cepat');
            $table->unsignedInteger('dtg_lama');
            $table->unsignedInteger('plg_cepat');
            $table->unsignedInteger('plg_lama');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_rekaps');
    }
};
