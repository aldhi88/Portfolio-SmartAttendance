<?php

use App\Models\DataLembur;
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
        Schema::table('data_lemburs', function (Blueprint $table) {
            $table->string('nomor', 64)->nullable()->after('id');

            $table->enum('status_pengawas1', ['Proses', 'Disetujui', 'Ditolak'])
                ->default('Proses')
                ->after('pengawas1');

            $table->enum('status_pengawas2', ['Proses', 'Disetujui', 'Ditolak'])
                ->default('Proses')
                ->after('pengawas2');
        });

        $records = DB::table('data_lemburs')
            ->join('data_employees', 'data_employees.id', '=', 'data_lemburs.data_employee_id')
            ->whereNull('data_lemburs.deleted_at')
            ->select(
                'data_lemburs.id',
                'data_lemburs.tanggal',
                'data_lemburs.status',
                'data_employees.master_organization_id'
            )
            ->orderBy('data_lemburs.tanggal')
            ->orderBy('data_lemburs.id')
            ->get();

        $counter = []; // [year][tipe] => running number

        foreach ($records as $row) {
            $year = date('Y', strtotime($row->tanggal));
            $format = DataLembur::formatOrg($row->master_organization_id);

            if (is_null($format)) {
                $format = 'others';
            }

            $counter[$year] ??= [];
            $counter[$year][$format] ??= 1;

            $seqYear = $year .'-'. str_pad($counter[$year][$format], 4, '0', STR_PAD_LEFT);
            $seq = str_pad($counter[$year][$format], 4, '0', STR_PAD_LEFT);
            $counter[$year][$format]++;

            // format akhir sesuai tipe
            if ($format === 'format_ptc') {
                $nomor = "{$seq}/PND448000/{$year}-S8";
            } elseif ($format === 'format_patra_niaga') {
                $nomor = "{$seq}/PND448000/IV/{$year}-SO";
            } elseif ($format === 'format_ptc_security') {
                $nomor = "{$seq}/PND448000/{$year}-S8";
            } else {
                $nomor = $seqYear; // fallback aman
            }

            DB::table('data_lemburs')
                ->where('id', $row->id)
                ->update([
                    'nomor' => $nomor,
                    'status_pengawas1' => $row->status,
                    'status_pengawas2' => $row->status,
                ]);
        }

        DB::table('data_lemburs')
            ->whereNotNull('deleted_at')
            ->delete();

        Schema::table('data_lemburs', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_lemburs', function (Blueprint $table) {
            $table->enum('status', ['Proses', 'Disetujui', 'Ditolak'])
                ->default('Proses')
                ->after('checkout_deadline_time_lembur');

            $table->unsignedBigInteger('approved_by')->nullable()->after('data_employee_id');
        });

        /**
         * 2. Restore data status
         */
        DB::table('data_lemburs')->update([
            'status' => DB::raw('status_pengawas1'),
        ]);

        /**
         * 3. Drop kolom baru
         *    (unique index ikut terhapus otomatis)
         */
        Schema::table('data_lemburs', function (Blueprint $table) {
            $table->dropColumn([
                'nomor',
                'status_pengawas1',
                'status_pengawas2',
            ]);
        });
    }
};
