<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const SIP_CODE = 'PND4A0000';
    private const SIP_SUFFIX = 'S8';
    private const SPK_CODE = 'PND448000';
    private const SPK_SUFFIX = 'S5';

    public function up(): void
    {
        if (!Schema::hasTable('rdp_surat_numbers')) {
            return;
        }

        DB::transaction(function () {
            $this->backfillSipNumbers();
            $this->backfillSpkNumbers();
        });
    }

    public function down(): void
    {
        // Nomor surat adalah data dokumen; rollback migration tidak menghapus nomor yang sudah terbit.
    }

    private function backfillSipNumbers(): void
    {
        if (
            !Schema::hasColumn('rdp_karyawan_masuks', 'nomor_sip_surat')
            || !Schema::hasColumn('rdp_karyawan_masuks', 'tanggal_sip_surat')
        ) {
            return;
        }

        DB::table('rdp_karyawan_masuks')
            ->where('status', 'Penempatan Selesai')
            ->whereNull('nomor_sip_surat')
            ->orderBy('created_at')
            ->orderBy('id')
            ->select(['id', 'created_at'])
            ->get()
            ->each(function ($item) {
                $date = $this->documentDate($item->created_at);
                DB::table('rdp_karyawan_masuks')
                    ->where('id', $item->id)
                    ->whereNull('nomor_sip_surat')
                    ->update([
                        'nomor_sip_surat' => $this->nextNumber('SIP', $date, self::SIP_CODE, self::SIP_SUFFIX),
                        'tanggal_sip_surat' => $date,
                        'updated_at' => now(),
                    ]);
            });
    }

    private function backfillSpkNumbers(): void
    {
        $records = collect()
            ->merge($this->spkRows('rdp_perbaikans'))
            ->merge($this->spkRows('rdp_pengadaans'))
            ->sortBy([
                ['date', 'asc'],
                ['table', 'asc'],
                ['id', 'asc'],
            ])
            ->values();

        foreach ($records as $record) {
            $date = $this->documentDate($record['date']);
            DB::table($record['table'])
                ->where('id', $record['id'])
                ->whereNull('nomor_spk_surat')
                ->update([
                    'nomor_spk_surat' => $this->nextNumber('SPK', $date, self::SPK_CODE, self::SPK_SUFFIX),
                    'tanggal_spk_surat' => $date,
                    'updated_at' => now(),
                ]);
        }
    }

    private function spkRows(string $table)
    {
        if (
            !Schema::hasTable($table)
            || !Schema::hasColumn($table, 'nomor_spk_surat')
            || !Schema::hasColumn($table, 'tanggal_spk_surat')
        ) {
            return collect();
        }

        return DB::table($table)
            ->whereIn('status', [
                'SPK Terbit, Pekerjaan Perbaikan Berjalan',
                'Perbaikan Selesai oleh Vendor, menunggu verifikasi Admin/SPV',
                'Hasil Perbaikan Disetujui SPV, menunggu Pimpinan',
                'Perbaikan Selesai',
                'SPK Terbit, Pekerjaan Pengadaan Berjalan',
                'Pengadaan Selesai oleh Vendor, menunggu verifikasi Admin/SPV',
                'Hasil Pengadaan Disetujui SPV, menunggu Pimpinan',
                'Pengadaan Selesai',
            ])
            ->whereNull('nomor_spk_surat')
            ->orderBy('created_at')
            ->orderBy('id')
            ->select(['id', 'created_at'])
            ->get()
            ->map(fn ($item) => [
                'table' => $table,
                'id' => $item->id,
                'date' => $item->created_at,
            ]);
    }

    private function nextNumber(string $jenisSurat, string $date, string $code, string $suffix): string
    {
        $year = (int) Carbon::parse($date)->format('Y');

        DB::table('rdp_surat_numbers')->insertOrIgnore([
            'jenis_surat' => $jenisSurat,
            'tahun' => $year,
            'nomor_terakhir' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $counter = DB::table('rdp_surat_numbers')
            ->where('jenis_surat', $jenisSurat)
            ->where('tahun', $year)
            ->lockForUpdate()
            ->first();

        $nextSequence = ((int) $counter->nomor_terakhir) + 1;

        DB::table('rdp_surat_numbers')
            ->where('id', $counter->id)
            ->update([
                'nomor_terakhir' => $nextSequence,
                'updated_at' => now(),
            ]);

        return sprintf('%03d/%s/%d-%s', $nextSequence, $code, $year, $suffix);
    }

    private function documentDate($date): string
    {
        return Carbon::parse($date ?: now())->toDateString();
    }
};
