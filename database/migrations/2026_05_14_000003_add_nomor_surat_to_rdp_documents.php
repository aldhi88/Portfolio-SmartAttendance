<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rdp_karyawan_masuks', function (Blueprint $table) {
            if (!Schema::hasColumn('rdp_karyawan_masuks', 'nomor_sip_surat')) {
                $table->string('nomor_sip_surat')->nullable()->after('file_sk_mutasi')->unique();
            }

            if (!Schema::hasColumn('rdp_karyawan_masuks', 'tanggal_sip_surat')) {
                $table->date('tanggal_sip_surat')->nullable()->after('nomor_sip_surat');
            }
        });

        Schema::table('rdp_perbaikans', function (Blueprint $table) {
            if (!Schema::hasColumn('rdp_perbaikans', 'nomor_spk_surat')) {
                $table->string('nomor_spk_surat')->nullable()->after('file_proposal')->unique();
            }

            if (!Schema::hasColumn('rdp_perbaikans', 'tanggal_spk_surat')) {
                $table->date('tanggal_spk_surat')->nullable()->after('nomor_spk_surat');
            }
        });

        Schema::table('rdp_pengadaans', function (Blueprint $table) {
            if (!Schema::hasColumn('rdp_pengadaans', 'nomor_spk_surat')) {
                $table->string('nomor_spk_surat')->nullable()->after('file_proposal')->unique();
            }

            if (!Schema::hasColumn('rdp_pengadaans', 'tanggal_spk_surat')) {
                $table->date('tanggal_spk_surat')->nullable()->after('nomor_spk_surat');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rdp_pengadaans', function (Blueprint $table) {
            if (Schema::hasColumn('rdp_pengadaans', 'nomor_spk_surat')) {
                $table->dropUnique(['nomor_spk_surat']);
                $table->dropColumn('nomor_spk_surat');
            }

            if (Schema::hasColumn('rdp_pengadaans', 'tanggal_spk_surat')) {
                $table->dropColumn('tanggal_spk_surat');
            }
        });

        Schema::table('rdp_perbaikans', function (Blueprint $table) {
            if (Schema::hasColumn('rdp_perbaikans', 'nomor_spk_surat')) {
                $table->dropUnique(['nomor_spk_surat']);
                $table->dropColumn('nomor_spk_surat');
            }

            if (Schema::hasColumn('rdp_perbaikans', 'tanggal_spk_surat')) {
                $table->dropColumn('tanggal_spk_surat');
            }
        });

        Schema::table('rdp_karyawan_masuks', function (Blueprint $table) {
            if (Schema::hasColumn('rdp_karyawan_masuks', 'nomor_sip_surat')) {
                $table->dropUnique(['nomor_sip_surat']);
                $table->dropColumn('nomor_sip_surat');
            }

            if (Schema::hasColumn('rdp_karyawan_masuks', 'tanggal_sip_surat')) {
                $table->dropColumn('tanggal_sip_surat');
            }
        });
    }
};
