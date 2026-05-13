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
            ALTER TABLE rdp_karyawan_masuks
            MODIFY status ENUM(
                'Diajukan',
                'Berkas Disetujui SPV, menuggu Pimpinan',
                'Berkas Ditolak SPV, cek catatan',
                'Pengajuan Revisi',
                'Berkas Disetujui Pimpinan, menuggu pendataan aset',
                'Pengajuan Pendataan Aset',
                'Pendataan Disetujui SPV, menuggu Pimpinan',
                'Disetujui Pimpinan, menunggu Manager HC Region',
                'Ditolak Manager HC Region',
                'Penempatan Selesai',
                'Penempatan Dibatalkan'
            ) NOT NULL DEFAULT 'Diajukan'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('rdp_karyawan_masuks')
            ->whereIn('status', [
                'Disetujui Pimpinan, menunggu Manager HC Region',
                'Ditolak Manager HC Region',
            ])
            ->update(['status' => 'Penempatan Dibatalkan']);

        DB::statement("
            ALTER TABLE rdp_karyawan_masuks
            MODIFY status ENUM(
                'Diajukan',
                'Berkas Disetujui SPV, menuggu Pimpinan',
                'Berkas Ditolak SPV, cek catatan',
                'Pengajuan Revisi',
                'Berkas Disetujui Pimpinan, menuggu pendataan aset',
                'Pengajuan Pendataan Aset',
                'Pendataan Disetujui SPV, menuggu Pimpinan',
                'Penempatan Selesai',
                'Penempatan Dibatalkan'
            ) NOT NULL DEFAULT 'Diajukan'
        ");
    }
};
