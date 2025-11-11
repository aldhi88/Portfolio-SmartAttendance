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
        Schema::create('data_lemburs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(DataEmployee::class)->constrained();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->date('tanggal');
            $table->dateTime('checkin_time_lembur');
            $table->dateTime('work_time_lembur');
            $table->dateTime('checkin_deadline_time_lembur');
            $table->dateTime('checkout_time_lembur');
            $table->dateTime('checkout_deadline_time_lembur');
            $table->enum('status', ['Proses','Disetujui','Ditolak']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_lemburs');
    }
};
