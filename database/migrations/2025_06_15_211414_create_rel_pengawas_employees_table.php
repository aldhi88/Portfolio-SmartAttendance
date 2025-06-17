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
        Schema::create('rel_pengawas_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengawas_id')->constrained('data_employees')->onDelete('cascade');
            $table->foreignId('anggota_id')->constrained('data_employees')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rel_pengawas_employees');
    }
};
