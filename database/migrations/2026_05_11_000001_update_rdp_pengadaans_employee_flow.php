<?php

use App\Models\RdpKaryawanMasuk;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rdp_pengadaans', function (Blueprint $table) {
            if (!Schema::hasColumn('rdp_pengadaans', 'rdp_karyawan_masuk_id')) {
                $table->foreignIdFor(RdpKaryawanMasuk::class)->nullable()->after('id')->constrained()->nullOnDelete();
            }

            if (!Schema::hasColumn('rdp_pengadaans', 'catatan_revisi')) {
                $table->text('catatan_revisi')->nullable()->after('file_proposal');
            }
        });

        DB::statement("ALTER TABLE rdp_pengadaans MODIFY status ENUM(
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
            'Pengadaan Dibatalkan'
        ) NOT NULL DEFAULT 'Diajukan'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE rdp_pengadaans MODIFY status ENUM(
            'Vendor Ditugaskan, menunggu proposal vendor',
            'Proposal Vendor Diajukan, menunggu persetujuan Admin/SPV',
            'Proposal Disetujui SPV, menunggu Pimpinan',
            'Proposal Ditolak Pimpinan',
            'Proposal Disetujui Pimpinan, Penerbitan SPK',
            'SPK Terbit, Pekerjaan Pengadaan Berjalan',
            'Pengadaan Selesai oleh Vendor, menunggu verifikasi Admin/SPV',
            'Pengadaan Disetujui SPV, menunggu Pimpinan',
            'Pengadaan Selesai',
            'Pengadaan Dibatalkan'
        ) NOT NULL DEFAULT 'Vendor Ditugaskan, menunggu proposal vendor'");

        Schema::table('rdp_pengadaans', function (Blueprint $table) {
            if (Schema::hasColumn('rdp_pengadaans', 'rdp_karyawan_masuk_id')) {
                $table->dropConstrainedForeignId('rdp_karyawan_masuk_id');
            }

            if (Schema::hasColumn('rdp_pengadaans', 'catatan_revisi')) {
                $table->dropColumn('catatan_revisi');
            }
        });
    }
};
