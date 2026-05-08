<?php

use App\Models\DataEmployee;
use App\Models\RdpMasterRumah;
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
        Schema::create('rdp_karyawan_masuks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(DataEmployee::class)
                ->constrained();
            $table->foreignIdFor(RdpMasterRumah::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('nomor_sk_mutasi');
            $table->date('tanggal_sk_mutasi');
            $table->date('tanggal_mulai');
            $table->string('file_sk_mutasi');
            $table->text('catatan_revisi_berkas')->nullable();
            $table->enum('status', [
                'Diajukan',
                'Berkas Disetujui SPV, menuggu Pimpinan',
                'Berkas Ditolak SPV, cek catatan',
                'Pengajuan Revisi',
                'Berkas Disetujui Pimpinan, menuggu pendataan aset',
                'Pengajuan Pendataan Aset',
                'Pendataan Disetujui SPV, menuggu Pimpinan',
                'Penempatan Selesai',
                'Penempatan Dibatalkan',
            ])->default('Diajukan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rdp_karyawan_masuks');
    }
};
