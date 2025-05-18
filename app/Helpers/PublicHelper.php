<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;

class PublicHelper
{
    public static function getHariIndo(): array
    {
        return ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    }

    public static function hariIndoByEng($day)
    {
        $hariIndo = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        return $hariIndo[$day];
    }

    public static function indoMonthList()
    {
        return [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
    }

    public static function dateInMonth($thisMonth, $thisYear)
    {
        if ($thisMonth == date('m') && $thisYear == date('Y')) {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now();
        } else {
            $start = Carbon::create($thisYear, $thisMonth, 1)->startOfMonth();
            $end = Carbon::create($thisYear, $thisMonth, 1)->endOfMonth();
        }

        $dates = collect();
        while ($start->lte($end)) {
            $dates->push($start->format('Y-m-d'));

            $start->addDay();
        }

        return $dates->toArray();
    }

    public static function getDtAbsen($dateInMonth, $logAttendances, $schedules)
    {
        // dd($dateInMonth, $logAttendances, $schedules);
        $result = [];
        $logAttendances = collect($logAttendances);
        $schedules = collect($schedules);

        $activeSchedule = $schedules->first(function ($sch) {
            return empty($sch['pivot']['expired_at']);
        });

        // dd($logAttendances, $schedules, $activeSchedule);
        // dd($activeSchedule['type']);

        if($activeSchedule['type'] == 'Tetap'){ //kalau schedule type nya tetap

            foreach ($dateInMonth as $fullDate) {
                $tanggalYMD = Carbon::parse($fullDate)->format('Y-m-d');
                $tanggalKey = Carbon::parse($fullDate)->format('d'); // contoh: "01"
                $hariKe = Carbon::parse($fullDate)->isoWeekday(); // 1 = Senin, 7 = Minggu

                $labelIn = $labelOut = '-';
                $jamIn = $jamOut = '-';
                $tipeJadwal = '-';

                $isRegular = $activeSchedule['day_work']['regular'][$hariKe] ?? false;

                if ($isRegular) {
                    $tipeJadwal = 'Regular';

                    $checkinTime = $activeSchedule['checkin_time'];
                    $workTime = $activeSchedule['work_time'];
                    $checkinDeadline = $activeSchedule['checkin_deadline_time'];
                    $checkoutTime = $activeSchedule['checkout_time'];

                    $logsTanggalIni = $logAttendances->filter(function ($log) use ($tanggalYMD) {
                        return Carbon::parse($log['time'])->format('Y-m-d') === $tanggalYMD;
                    });

                    $logIn = $logsTanggalIni->sortBy('time')->first();
                    $logOut = $logsTanggalIni->sortByDesc('time')->first();

                    // IN
                    if ($logIn) {
                        $waktuMasuk = Carbon::parse($logIn['time'])->format('H:i');

                        if ($waktuMasuk >= $checkinTime && $waktuMasuk <= $workTime) {
                            $labelIn = '(ontime)';
                        } elseif ($waktuMasuk > $workTime && $waktuMasuk <= $checkinDeadline) {
                            $labelIn = '(terlambat)';
                        } else {
                            $waktuMasuk = null;
                        }

                        $jamIn = $waktuMasuk ?? '-';
                    } else {
                        $labelIn = 'alpha';
                    }

                    // OUT
                    if ($logOut) {
                        $waktuPulang = Carbon::parse($logOut['time'])->format('H:i');

                        if ($waktuPulang >= $checkoutTime) {
                            $labelOut = '(ontime)';
                        } elseif ($waktuPulang > $checkinDeadline && $waktuPulang < $checkoutTime) {
                            $labelOut = '(cepat)';
                        } else {
                            $waktuPulang = null;
                        }

                        $jamOut = $waktuPulang ?? '-';
                    } else {
                        $labelOut = 'alpha';
                    }
                }

                $result[$tanggalKey] = [
                    'label_in'    => $labelIn,
                    'label_out'   => $labelOut,
                    'in'          => $jamIn,
                    'out'         => $jamOut,
                    'tipe_jadwal' => $tipeJadwal,
                ];
            }

        }else{

            // logic untuk jadwal rotasi
            foreach ($dateInMonth as $fullDate) {
                // $tanggalCarbon = Carbon::parse($fullDate)->startOfDay();
                $tanggalCarbon = Carbon::createFromFormat('Y-m-d', $fullDate)->startOfDay();
                $tanggalYMD = $tanggalCarbon->format('Y-m-d');
                $tanggalKey = $tanggalCarbon->format('d');
                $tipeJadwal = 'Rotasi';
                $labelIn = $labelOut = '-';
                $jamIn = $jamOut = '-';

                // $startDate = Carbon::parse($activeSchedule['day_work']['start_date'])->startOfDay();
                $startDate = Carbon::createFromFormat('Y-m-d', $activeSchedule['day_work']['start_date'])->startOfDay();
                // $diff = $startDate->diffInDays($tanggalCarbon);
                $diff = $tanggalCarbon->diffInDays($startDate, true);
                $modulo = $diff % 12;

                $shift = null;
                $jadwal = null;
                $tanggalLogIn = $tanggalYMD;
                $tanggalLogOut = $tanggalYMD;

                if (in_array($modulo, [0, 1, 2])) {
                    $shift = 'pagi';
                    // dd($shift);
                    $jadwal = [
                        'checkin_time' => $activeSchedule['checkin_time'],
                        'work_time' => $activeSchedule['work_time'],
                        'checkin_deadline_time' => $activeSchedule['checkin_deadline_time'],
                        'checkout_time' => $activeSchedule['checkout_time'],
                    ];
                } elseif (in_array($modulo, [4, 5, 6])) {
                    $shift = 'sore';
                    // dd($shift);
                    $jadwal = $activeSchedule['day_work']['rotasi']['sore'];
                    $tanggalLogOut = Carbon::parse($tanggalYMD)->addDay()->format('Y-m-d');
                } elseif (in_array($modulo, [8, 9, 10])) {
                    $shift = 'malam';
                    // dd($shift);
                    $jadwal = $activeSchedule['day_work']['rotasi']['malam'];
                    $tanggalLogOut = Carbon::parse($tanggalYMD)->addDay()->format('Y-m-d');
                } else {
                    // OFF day
                    $result[$tanggalKey] = [
                        'label_in'    => 'off',
                        'label_out'   => 'off',
                        'in'          => '-',
                        'out'         => '-',
                        'tipe_jadwal' => $tipeJadwal,
                    ];
                    continue;
                }

                // Ambil log IN
                $logIn = $logAttendances
                    ->filter(function ($log) use ($tanggalLogIn, $jadwal) {
                        $logTime = Carbon::parse($log['time']);
                        return $logTime->isSameDay($tanggalLogIn) &&
                            $logTime->gte(Carbon::parse($tanggalLogIn . ' ' . $jadwal['checkin_time']));
                    })
                    ->sortBy('time')->first();

                // Ambil log OUT
                $logOut = $logAttendances
                    ->filter(function ($log) use ($tanggalLogOut) {
                        return Carbon::parse($log['time'])->isSameDay($tanggalLogOut);
                    })
                    ->sortByDesc('time')->first();

                // --- IN
                if ($logIn) {
                    $waktuMasuk = Carbon::parse($logIn['time']);
                    $batasAwal = Carbon::parse($tanggalLogIn . ' ' . $jadwal['checkin_time']);
                    $batasOntime = Carbon::parse($tanggalLogIn . ' ' . $jadwal['work_time']);
                    $batasTerlambat = Carbon::parse($tanggalLogIn . ' ' . $jadwal['checkin_deadline_time']);

                    if ($waktuMasuk->between($batasAwal, $batasOntime)) {
                        $labelIn = '(ontime)';
                        $jamIn = $waktuMasuk->format('H:i');
                    } elseif ($waktuMasuk->between($batasOntime->copy()->addSecond(), $batasTerlambat)) {
                        $labelIn = '(terlambat)';
                        $jamIn = $waktuMasuk->format('H:i');
                    }
                }

                // --- OUT
                if ($logOut) {
                    $waktuPulang = Carbon::parse($logOut['time']);
                    $batasPulang = $jadwal['checkout_time'] === '00:00'
                        ? Carbon::parse($tanggalLogOut)->addDay()->startOfDay()
                        : Carbon::parse($tanggalLogOut . ' ' . $jadwal['checkout_time']);

                    $batasTerlambatPulang = Carbon::parse($tanggalLogOut . ' ' . $jadwal['checkin_deadline_time']);

                    if ($waktuPulang->gte($batasPulang)) {
                        $labelOut = '(ontime)';
                        $jamOut = $waktuPulang->format('H:i');
                    } elseif ($waktuPulang->between($batasTerlambatPulang, $batasPulang->copy()->subSecond())) {
                        $labelOut = '(plg cepat)';
                        $jamOut = $waktuPulang->format('H:i');
                    }
                }

                // Final check: alpha jika benar-benar kosong
                if ($jamIn === '-' && $jamOut === '-') {
                    $labelIn = 'alpha';
                    $labelOut = 'alpha';
                }


                $result[$tanggalKey] = [
                    'label_in'    => $labelIn,
                    'label_out'   => $labelOut,
                    'in'          => $jamIn,
                    'out'         => $jamOut,
                    'tipe_jadwal' => $tipeJadwal,
                ];
            }


        }

        return $result;

    }


}
