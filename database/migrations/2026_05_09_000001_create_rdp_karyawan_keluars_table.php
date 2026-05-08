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
        Schema::create('rdp_karyawan_keluars', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(DataEmployee::class)
                ->constrained();
            $table->foreignIdFor(RdpMasterRumah::class)
                ->constrained()
                ->restrictOnDelete();
            $table->string('nomor_sk_keluar');
            $table->date('tanggal_sk_keluar');
            $table->date('tanggal_keluar');
            $table->string('file_sk_keluar');
            $table->text('catatan_revisi_berkas')->nullable();
            $table->enum('status', [
                'Diajukan',
                'Berkas Disetujui SPV, menuggu Pimpinan',
                'Berkas Ditolak SPV, cek catatan',
                'Pengajuan Revisi',
                'Berkas Disetujui Pimpinan, menuggu pendataan aset keluar',
                'Pengajuan Pendataan Aset Keluar',
                'Pendataan Disetujui SPV, menuggu Pimpinan',
                'Keluar RDP Selesai',
                'Keluar RDP Dibatalkan',
            ])->default('Diajukan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rdp_karyawan_keluars');
    }
};
