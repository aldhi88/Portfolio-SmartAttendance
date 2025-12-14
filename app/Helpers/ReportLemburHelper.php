<?php

namespace App\Helpers;

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
        $query = LogAttendance::query()
            ->where('data_employee_id', $data['data_employee_id'])
            ->whereBetween('time', [$data['start'], $data['end']]);

        $result = $type === 'in'
            ? $query->min('time')
            : $query->max('time');

        return $result ?? '-';
    }
}
