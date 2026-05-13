<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE rdp_perbaikans
            MODIFY status ENUM(
                'Diajukan',
                'Pengajuan Ditolak SPV, cek catatan',
                'Pengajuan Revisi',
                'Vendor Ditugaskan, menunggu proposal vendor',
                'Proposal Vendor Diajukan, menunggu persetujuan Admin/SPV',
                'Proposal Disetujui SPV, menunggu Pimpinan',
                'Proposal Ditolak Pimpinan',
                'Proposal Disetujui Pimpinan, menunggu Manager Aset Region',
                'Proposal Ditolak Manager Aset Region',
                'Proposal Disetujui Pimpinan, Penerbitan SPK',
                'SPK Terbit, Pekerjaan Perbaikan Berjalan',
                'Perbaikan Selesai oleh Vendor, menunggu verifikasi Admin/SPV',
                'Perbaikan Disetujui SPV, menunggu Pimpinan',
                'Perbaikan Selesai',
                'Perbaikan Dibatalkan'
            ) NOT NULL DEFAULT 'Diajukan'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('rdp_perbaikans')
            ->whereIn('status', [
                'Proposal Disetujui Pimpinan, menunggu Manager Aset Region',
                'Proposal Ditolak Manager Aset Region',
            ])
            ->update(['status' => 'Perbaikan Dibatalkan']);

        DB::statement("
            ALTER TABLE rdp_perbaikans
            MODIFY status ENUM(
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
                'Perbaikan Dibatalkan'
            ) NOT NULL DEFAULT 'Diajukan'
        ");
    }
};
