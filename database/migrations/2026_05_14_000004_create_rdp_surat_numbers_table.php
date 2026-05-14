<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rdp_surat_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_surat', 16);
            $table->year('tahun');
            $table->unsignedInteger('nomor_terakhir')->default(0);
            $table->timestamps();
            $table->unique(['jenis_surat', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rdp_surat_numbers');
    }
};
