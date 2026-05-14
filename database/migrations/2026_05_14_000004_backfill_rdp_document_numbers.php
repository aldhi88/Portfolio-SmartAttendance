<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            $this->backfillSipNumbers();
            $this->backfillSpkNumbers(
                'rdp_perbaikans',
                'SPK Terbit, Pekerjaan Perbaikan Berjalan',
                [
                    'SPK Terbit, Pekerjaan Perbaikan Berjalan',
                    'Perbaikan Selesai oleh Vendor, menunggu verifikasi Admin/SPV',
                    'Perbaikan Disetujui SPV, menunggu Pimpinan',
                    'Perbaikan Selesai',
                ]
            );
            $this->backfillSpkNumbers(
                'rdp_pengadaans',
                'SPK Terbit, Pekerjaan Pengadaan Berjalan',
                [
                    'SPK Terbit, Pekerjaan Pengadaan Berjalan',
                    'Pengadaan Selesai oleh Vendor, menunggu verifikasi Admin/SPV',
                    'Pengadaan Disetujui SPV, menunggu Pimpinan',
                    'Pengadaan Selesai',
                ]
            );
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
                        'nomor_sip_surat' => $this->nextNumber(
                            'rdp_karyawan_masuks',
                            'nomor_sip_surat',
                            'tanggal_sip_surat',
                            $date,
                            'PND4A0000',
                            'S8'
                        ),
                        'tanggal_sip_surat' => $date,
                        'updated_at' => now(),
                    ]);
            });
    }

    private function backfillSpkNumbers(string $table, string $firstStatus, array $statuses): void
    {
        if (
            !Schema::hasColumn($table, 'nomor_spk_surat')
            || !Schema::hasColumn($table, 'tanggal_spk_surat')
        ) {
            return;
        }

        DB::table($table)
            ->whereIn('status', $statuses)
            ->whereNull('nomor_spk_surat')
            ->orderByRaw('status = ? desc', [$firstStatus])
            ->orderBy('created_at')
            ->orderBy('id')
            ->select(['id', 'created_at'])
            ->get()
            ->each(function ($item) use ($table) {
                $date = $this->documentDate($item->created_at);
                DB::table($table)
                    ->where('id', $item->id)
                    ->whereNull('nomor_spk_surat')
                    ->update([
                        'nomor_spk_surat' => $this->nextNumber(
                            $table,
                            'nomor_spk_surat',
                            'tanggal_spk_surat',
                            $date,
                            'PND448000',
                            'S5'
                        ),
                        'tanggal_spk_surat' => $date,
                        'updated_at' => now(),
                    ]);
            });
    }

    private function nextNumber(string $table, string $numberColumn, string $dateColumn, string $date, string $code, string $suffix): string
    {
        $year = (int) Carbon::parse($date)->format('Y');
        $maxSequence = DB::table($table)
            ->whereYear($dateColumn, $year)
            ->whereNotNull($numberColumn)
            ->lockForUpdate()
            ->pluck($numberColumn)
            ->map(fn ($nomor) => (int) substr((string) $nomor, 0, 3))
            ->max() ?: 0;

        return sprintf('%03d/%s/%d-%s', $maxSequence + 1, $code, $year, $suffix);
    }

    private function documentDate($date): string
    {
        return Carbon::parse($date ?: now())->toDateString();
    }
};
