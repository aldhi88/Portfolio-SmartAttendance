<?php

use App\Models\RdpMasterVendor;
use App\Models\RdpKaryawanMasuk;
use App\Models\RdpPengadaan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rdp_pengadaans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(RdpKaryawanMasuk::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(RdpMasterVendor::class)->nullable()->constrained()->nullOnDelete();
            $table->string('file_proposal')->nullable();
            $table->text('catatan_revisi')->nullable();
            $table->enum('status', [
                'Diajukan',
                'Pengajuan Ditolak SPV, cek catatan',
                'Pengajuan Revisi',
                'Vendor Ditugaskan, menunggu proposal vendor',
                'Proposal Vendor Diajukan, menunggu persetujuan Admin/SPV',
                'Proposal Disetujui SPV, menunggu Pimpinan',
                'Proposal Ditolak Pimpinan',
                'Proposal Disetujui Pimpinan, Penerbitan SPK',
                'SPK Terbit, Pekerjaan Pengadaan Berjalan',
                'Pengadaan Selesai oleh Vendor, menunggu verifikasi Admin/SPV',
                'Pengadaan Disetujui SPV, menunggu Pimpinan',
                'Pengadaan Selesai',
                'Pengadaan Dibatalkan',
            ])->default('Diajukan');
            $table->timestamps();
        });

        Schema::create('rdp_pengadaan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(RdpPengadaan::class)->constrained()->cascadeOnDelete();
            $table->string('nama_item');
            $table->text('deskripsi_item')->nullable();
            $table->integer('jumlah')->nullable();
            $table->string('satuan')->nullable();
            $table->text('narasi_hasil_pengadaan')->nullable();
            $table->string('foto_hasil_pengadaan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rdp_pengadaan_items');
        Schema::dropIfExists('rdp_pengadaans');
    }
};
