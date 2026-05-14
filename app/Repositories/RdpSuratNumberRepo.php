<?php

namespace App\Repositories;

use App\Models\RdpSuratNumber;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RdpSuratNumberRepo
{
    public const JENIS_SIP = 'SIP';
    public const JENIS_SPK = 'SPK';
    public const SIP_CODE = 'PND4A0000';
    public const SIP_SUFFIX = 'S8';
    public const SPK_CODE = 'PND448000';
    public const SPK_SUFFIX = 'S5';

    public static function nextSipNumber($date = null): string
    {
        $year = self::yearFromDate($date);

        return self::formatNumber(
            self::nextSequence(self::JENIS_SIP, $year),
            self::SIP_CODE,
            $year,
            self::SIP_SUFFIX
        );
    }

    public static function nextSpkNumber($date = null): string
    {
        $year = self::yearFromDate($date);

        return self::formatNumber(
            self::nextSequence(self::JENIS_SPK, $year),
            self::SPK_CODE,
            $year,
            self::SPK_SUFFIX
        );
    }

    protected static function nextSequence(string $jenisSurat, int $year): int
    {
        return DB::transaction(function () use ($jenisSurat, $year) {
            RdpSuratNumber::query()->insertOrIgnore([
                'jenis_surat' => $jenisSurat,
                'tahun' => $year,
                'nomor_terakhir' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $counter = RdpSuratNumber::query()
                ->where('jenis_surat', $jenisSurat)
                ->where('tahun', $year)
                ->lockForUpdate()
                ->firstOrFail();

            $nextSequence = $counter->nomor_terakhir + 1;
            $counter->update(['nomor_terakhir' => $nextSequence]);

            return $nextSequence;
        });
    }

    protected static function formatNumber(int $sequence, string $code, int $year, string $suffix): string
    {
        return sprintf('%03d/%s/%d-%s', $sequence, $code, $year, $suffix);
    }

    protected static function yearFromDate($date): int
    {
        if (empty($date)) {
            return (int) now()->format('Y');
        }

        return (int) Carbon::parse($date)->format('Y');
    }
}
