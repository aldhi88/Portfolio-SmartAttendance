<?php

namespace App\Helpers;

use App\Models\DataAttendanceClaim;
use App\Models\LogAttendance;

class ReportLemburHelper
{
    public static function getLemburCheckin($data)
    {
        $paramLog['data_employee_id'] = $data['data_employee_id'];
        $paramLog['start'] = $data['checkin_time_lembur'];
        $paramLog['end'] = $data['checkin_deadline_time_lembur'];
        $dataLogAttd = self::getLogAttd($paramLog, 'in');
        return $dataLogAttd;
    }
    public static function getLemburCheckout($data)
    {
        $paramLog['data_employee_id'] = $data['data_employee_id'];
        $paramLog['start'] = $data['checkout_time_lembur'];
        $paramLog['end'] = $data['checkout_deadline_time_lembur'];
        $dataLogAttd = self::getLogAttd($paramLog, 'out');
        return $dataLogAttd;
    }

    public static function getLogAttd($data, $type)
    {
        $columnMethod = $type === 'in' ? 'min' : 'max';

        $baseFilter = [
            ['data_employee_id', '=', $data['data_employee_id']],
        ];

        $result = DataAttendanceClaim::where($baseFilter)
            ->whereBetween('time', [$data['start'], $data['end']])
            ->{$columnMethod}('time');

        if (!$result) {
            $result = LogAttendance::where($baseFilter)
                ->whereBetween('time', [$data['start'], $data['end']])
                ->{$columnMethod}('time');
        }

        return $result ?? '-';
    }

}
