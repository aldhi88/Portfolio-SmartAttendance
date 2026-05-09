<?php

use App\Models\RdpKaryawanMasuk;
use App\Models\RdpMasterVendor;
use App\Models\RdpPerbaikan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rdp_perbaikans', function (Blueprint $table) {
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
                'SPK Terbit, Pekerjaan Perbaikan Berjalan',
                'Perbaikan Selesai oleh Vendor, menunggu verifikasi Admin/SPV',
                'Perbaikan Disetujui SPV, menunggu Pimpinan',
                'Perbaikan Selesai',
                'Perbaikan Dibatalkan',
            ])->default('Diajukan');
            $table->timestamps();
        });

        Schema::create('rdp_perbaikan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(RdpPerbaikan::class)->constrained()->cascadeOnDelete();
            $table->string('nama_item');
            $table->text('deskripsi_kerusakan')->nullable();
            $table->string('foto_kerusakan')->nullable();
            $table->text('narasi_hasil_perbaikan')->nullable();
            $table->string('foto_hasil_perbaikan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rdp_perbaikan_items');
        Schema::dropIfExists('rdp_perbaikans');
    }
};
