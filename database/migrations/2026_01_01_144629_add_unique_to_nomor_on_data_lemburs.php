<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        // 1) S8: xxxx/PND448000/YYYY-S8
        $this->fixByTanggalYear(
            '%/PND448000/%-S8',
            fn (string $seq, int $year) => "{$seq}/PND448000/{$year}-S8"
        );

        // 2) SO: xxxx/PND448000/IV/YYYY-SO
        $this->fixByTanggalYear(
            '%/PND448000/IV/%-SO',
            fn (string $seq, int $year) => "{$seq}/PND448000/IV/{$year}-SO"
        );

        // 3) DEFAULT: YYYY-xxxx (HARUS exact 4-4)
        $this->fixByTanggalYear(
            '____-____',
            fn (string $seq, int $year) => "{$year}-{$seq}"
        );

        Schema::table('data_lemburs', function (Blueprint $table) {
            $table->unique('nomor', 'data_lemburs_nomor_unique');
        });
    }

    /**
     * Perbaiki sequence per (tahun dari kolom tanggal) + pattern.
     * Urut berdasarkan created_at ASC.
     */
    private function fixByTanggalYear(string $like, callable $formatter): void
    {
        $rows = DB::table('data_lemburs')
            ->select('id', 'nomor', 'tanggal', 'created_at')
            ->whereNotNull('tanggal')
            ->where('nomor', 'like', $like)
            ->orderBy('created_at')
            ->get();

        // group per tahun(tanggal)
        $grouped = $rows->groupBy(function ($row) {
            return (int) date('Y', strtotime($row->tanggal));
        });

        foreach ($grouped as $year => $items) {
            $counter = 1;

            foreach ($items as $row) {
                $seq = str_pad($counter++, 4, '0', STR_PAD_LEFT);

                DB::table('data_lemburs')
                    ->where('id', $row->id)
                    ->update([
                        'nomor' => $formatter($seq, (int) $year),
                    ]);
            }
        }
    }

    public function down(): void
    {
        // tidak rollback karena ini perbaikan data
    }
};
