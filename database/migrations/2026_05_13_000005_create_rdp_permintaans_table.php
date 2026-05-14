<?php

use App\Models\RdpKaryawanMasuk;
use App\Models\RdpPermintaan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rdp_permintaans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(RdpKaryawanMasuk::class)->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['Diajukan', 'Selesai'])->default('Diajukan');
            $table->text('catatan_admin')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();
        });

        Schema::create('rdp_permintaan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(RdpPermintaan::class)->constrained()->cascadeOnDelete();
            $table->string('nama_item');
            $table->text('deskripsi_item')->nullable();
            $table->integer('jumlah')->nullable();
            $table->string('satuan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rdp_permintaan_items');
        Schema::dropIfExists('rdp_permintaans');
    }
};
