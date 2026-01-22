<?php

namespace App\Helpers;

use App\Models\DataAttendanceClaim;
use App\Models\LogAttendance;
use Carbon\Carbon;

class ReportLemburHelper
{
    public static function getLemburCheckin($data)
    {
        $paramLog['data_employee_id'] = $data['data_employee_id'];
        $paramLog['start'] = $data['checkin_time_lembur'];
        $paramLog['end'] = $data['checkin_deadline_time_lembur'];
        $paramLog['id'] = $data['id'];
        $dataLogAttd = self::getLogAttd($paramLog, 'in');
        return $dataLogAttd;
    }
    public static function getLemburCheckout($data)
    {
        $paramLog['data_employee_id'] = $data['data_employee_id'];
        $paramLog['start'] = $data['checkout_time_lembur'];
        $paramLog['end'] = $data['checkout_deadline_time_lembur'];
        $paramLog['id'] = $data['id'];
        $dataLogAttd = self::getLogAttd($paramLog, 'out');
        return $dataLogAttd;
    }

    public static function getLogAttd($data, $type)
    {
        $columnMethod = $type === 'in' ? 'min' : 'max';

        $baseQuery = DataAttendanceClaim::query()
            ->where('data_employee_id', $data['data_employee_id'])
            ->where('type', 'Lembur')
            ->whereBetween('time', [$data['start'], $data['end']]);

        $prioritizedTime = (clone $baseQuery)
            ->where('data_lembur_id', $data['id'])
            ->value('time'); // ambil langsung time-nya

        $result = $prioritizedTime
            ?? (clone $baseQuery)->{$columnMethod}('time');

        if (!$result) {
            $result = LogAttendance::query()
                ->where('data_employee_id', $data['data_employee_id'])
                ->whereBetween('time', [$data['start'], $data['end']])
                ->{$columnMethod}('time');
        }

        return $result ?? '-';
    }

    public static function getTotalJamLemburAktual($lemburs, int $precision = 1): float
    {
        // Bisa Collection atau array
        if ($lemburs instanceof Collection) {
            $lemburs = $lemburs->all();
        }

        $totalMenit = 0;

        foreach ($lemburs as $lembur) {
            // pastikan array
            if (!is_array($lembur)) {
                if ($lembur instanceof \Illuminate\Database\Eloquent\Model) {
                    $lembur = $lembur->toArray();
                } else {
                    $lembur = (array) $lembur;
                }
            }

            $checkin  = self::getLemburCheckin($lembur);
            $checkout = self::getLemburCheckout($lembur);

            // kalau tidak ada absensi lembur -> skip
            if (
                !$checkin || $checkin === '-' ||
                !$checkout || $checkout === '-'
            ) {
                continue;
            }

            try {
                $in  = Carbon::parse($checkin);
                $out = Carbon::parse($checkout);

                // handle lembur nyebrang hari
                if ($out->lt($in)) {
                    $out->addDay();
                }

                $totalMenit += $in->diffInMinutes($out);
            } catch (\Throwable $e) {
                continue;
            }
        }

        // konversi menit → jam desimal, misal 17.8
        return $totalMenit > 0
            ? round($totalMenit / 60, $precision)
            : 0.0;
    }
}
