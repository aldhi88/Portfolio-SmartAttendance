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

    public static function toSeconds($time)
    {
        if (is_numeric($time)) return (int) $time;
        if (!is_string($time)) return 0;

        $parts = explode(':', $time);
        if (count($parts) !== 3) return 0;

        [$h, $m, $s] = array_map('intval', $parts);
        return $h * 3600 + $m * 60 + $s;
    }

    public static function getDtAbsen($dateInMonth, $logAttendances, $schedules, $izin, $tglMerah)
    {
        // dd($dateInMonth, $logAttendances, $schedules, $izin, $tglMerah);
        $result = [];
        $logAttendances = collect($logAttendances);
        $schedules = collect($schedules);
        $izin = collect($izin);

        foreach ($dateInMonth as $key => $value) {
            $return = [
                'label_in' => 'off',
                'label_out' => 'off',
                'time_in' => '-',
                'time_out' => '-',
                'status' => 'off',
                'type' => '-',
                'shift' => '-',
                'time_dtg_cpt' => 0,
                'time_terlambat' => 0,
                'time_plg_cpt' => 0,
            ];

            $tglCek = Carbon::parse($value);
            $tanggalYMD = $tglCek->format('Y-m-d');
            $tglIndex = $tglCek->format('d');

            // get jadwal kerja yang digunakan di tanggal ini
            $scheduleMatch = $schedules->first(function ($schedule) use ($tglCek) {
                $effective = Carbon::parse($schedule['pivot']['effective_at']);
                $expired = $schedule['pivot']['expired_at']
                    ? Carbon::parse($schedule['pivot']['expired_at'])
                    : null;

                return $expired
                    ? $tglCek->between($effective, $expired)
                    : $tglCek->greaterThanOrEqualTo($effective);
            });

            if (!$scheduleMatch) {
                $result[$tglIndex] = $return;
                continue;
            }

            if (in_array($value, $tglMerah)) {
                $return = [
                    'label_in' => 'tgl merah',
                    'label_out' => 'tgl merah',
                    'time_in' => '-',
                    'time_out' => '-',
                    'status' => 'tgl merah'
                ];
                $result[$tglIndex] = $return;
                continue;
            }

            $hariKe = $tglCek->isoWeekday(); // 1 = Senin, ..., 7 = Minggu

            $izinTanggalIni = $izin->first(function ($iz) use ($tglCek) {
                $from = Carbon::parse($iz['from'])->startOfDay();
                $to = Carbon::parse($iz['to'])->endOfDay();
                return $tglCek->between($from, $to);
            });

            // dump($izinTanggalIni);

            if ($scheduleMatch['type'] == 'Tetap') {
                $return['type'] = 'Tetap';

                // Cek apakah ini hari kerja regular (bukan lembur atau hari libur kerja)
                $isRegular = $scheduleMatch['day_work']['regular'][$hariKe] ?? false;
                if (!$isRegular) {
                    $result[$tglIndex] = $return;
                    continue;
                }

                $kenaIzinMasuk = false;
                $kenaIzinKeluar = false;

                if ($izinTanggalIni) {
                    $izinStart = Carbon::parse($izinTanggalIni['from']);
                    $izinEnd = Carbon::parse($izinTanggalIni['to']);

                    $jamMasuk = Carbon::parse($tanggalYMD . ' ' . $scheduleMatch['checkin_deadline_time']);
                    $jamKeluar = Carbon::parse($tanggalYMD . ' ' . $scheduleMatch['checkout_time']);

                    $kenaIzinMasuk = $izinStart->lte($jamMasuk) && $izinEnd->gte($jamMasuk);
                    $kenaIzinKeluar = $izinStart->lte($jamKeluar) && $izinEnd->gte($jamKeluar);

                    if ($kenaIzinMasuk) $return['label_in'] = $izinTanggalIni['jenis'];
                    if ($kenaIzinKeluar) $return['label_out'] = $izinTanggalIni['jenis'];

                    if ($kenaIzinMasuk && $kenaIzinKeluar) {
                        $return['status'] = 'izin';
                        $result[$tglIndex] = $return;
                        continue;
                    }
                }

                $checkinTime = Carbon::parse($tanggalYMD . ' ' . $scheduleMatch['checkin_time']);
                $workTime = Carbon::parse($tanggalYMD . ' ' . $scheduleMatch['work_time']);
                $checkinDeadline = Carbon::parse($tanggalYMD . ' ' . $scheduleMatch['checkin_deadline_time']);
                $checkoutTime = Carbon::parse($tanggalYMD . ' ' . $scheduleMatch['checkout_time']);

                // === IN ===
                $logIn = $logAttendances->filter(function ($log) use ($tanggalYMD, $checkinTime) {
                    $logTime = Carbon::parse($log['time']);
                    return $logTime->format('Y-m-d') === $tanggalYMD && $logTime->gte($checkinTime);
                })->sortBy('time')->first();

                if (!$kenaIzinMasuk) {
                    $return['label_in'] = 'tdk absen';
                    if ($logIn) {
                        $waktuMasuk = Carbon::parse($logIn['time']);
                        $return['status'] = 'hadir';

                        if ($waktuMasuk->between($checkinTime, $workTime)) {
                            $return['label_in'] = 'dtg ontime';
                            $return['time_in'] = $waktuMasuk->format('H:i:s');
                            $return['time_dtg_cpt'] = $workTime->diff($waktuMasuk)->format('%H:%I:%S');
                        } elseif ($waktuMasuk->between($workTime->copy()->addSecond(), $checkinDeadline)) {
                            $return['label_in'] = 'terlambat';
                            $return['time_in'] = $waktuMasuk->format('H:i:s');
                            $return['time_terlambat'] = $waktuMasuk->diff($workTime)->format('%H:%I:%S');
                        }
                    }
                }

                // === OUT ===
                $logOut = $logAttendances->filter(function ($log) use ($tanggalYMD) {
                    return Carbon::parse($log['time'])->format('Y-m-d') === $tanggalYMD;
                })->sortByDesc('time')->first();

                if (!$kenaIzinKeluar) {
                    $return['label_out'] = 'tdk absen';
                    if ($logOut) {
                        $waktuPulang = Carbon::parse($logOut['time']);
                        $return['status'] = 'hadir';

                        if ($waktuPulang->gte($checkoutTime)) {
                            $return['label_out'] = 'plg ontime';
                            $return['time_out'] = $waktuPulang->format('H:i:s');
                        } elseif ($waktuPulang->between($checkinDeadline, $checkoutTime->copy()->subSecond())) {
                            $return['label_out'] = 'plg cepat';
                            $return['time_out'] = $waktuPulang->format('H:i:s');
                            $return['time_plg_cpt'] = $checkoutTime->diff($waktuPulang)->format('%H:%I:%S');
                        }
                    }
                }

                // === Final Evaluasi ===
                if ($return['label_in'] === 'tdk absen' && $return['label_out'] === 'tdk absen') {
                    $return['label_in'] = 'alpha';
                    $return['label_out'] = 'alpha';
                    $return['status'] = 'alpha';
                }
            }

            if ($scheduleMatch['type'] == 'Rotasi') {
                $return['type'] = 'Rotasi';

                $startDate = Carbon::createFromFormat('Y-m-d', $scheduleMatch['day_work']['start_date'])->startOfDay();
                $diff = $tglCek->diffInDays($startDate, true);
                $modulo = $diff % 12;

                $jadwal = null;
                $tanggalLogIn = $value;
                $tanggalLogOut = $value;
                $shift = null;

                if (in_array($modulo, [0, 1, 2])) {
                    $jadwal = [
                        'checkin_time' => $scheduleMatch['checkin_time'],
                        'work_time' => $scheduleMatch['work_time'],
                        'checkin_deadline_time' => $scheduleMatch['checkin_deadline_time'],
                        'checkout_time' => $scheduleMatch['checkout_time'],
                    ];
                    $shift = 'pagi';
                } elseif (in_array($modulo, [4, 5, 6])) {
                    $jadwal = $scheduleMatch['day_work']['rotasi']['sore'];
                    $shift = 'sore';
                } elseif (in_array($modulo, [8, 9, 10])) {
                    $jadwal = $scheduleMatch['day_work']['rotasi']['malam'];
                    $shift = 'malam';
                } else {
                    $result[$tglIndex] = $return;
                    continue;
                }
                $return['shift'] = $shift;

                if ($izinTanggalIni) {
                    $return['label_in'] = $izinTanggalIni['jenis'];
                    $return['label_out'] = $izinTanggalIni['jenis'];
                    $return['status'] = 'izin';
                    $result[$tglIndex] = $return;
                    continue;
                }

                $logIn = $logAttendances
                    ->filter(function ($log) use ($tanggalLogIn, $jadwal, $shift) {
                        $logTime = Carbon::parse($log['time']);

                        if ($shift === 'malam') {
                            $start = Carbon::parse($tanggalLogIn . ' ' . $jadwal['checkin_time']);
                            $end = Carbon::parse($tanggalLogIn)->addDay()
                                ->setTimeFromTimeString($jadwal['checkin_deadline_time']);
                        } else {
                            $start = Carbon::parse($tanggalLogIn . ' ' . $jadwal['checkin_time']);
                            $end = Carbon::parse($tanggalLogIn . ' ' . $jadwal['checkin_deadline_time']);
                        }

                        return $logTime->between($start, $end, true); // presisi: >= start && <= end
                    })
                    ->sortBy('time')
                    ->first();

                $logOut = $logAttendances
                    ->filter(function ($log) use ($jadwal, $shift, $tanggalLogOut) {
                        $logTime = Carbon::parse($log['time']);

                        if ($shift === 'pagi') {
                            $start = Carbon::parse($tanggalLogOut . ' ' . $jadwal['checkin_deadline_time'])->addSecond();
                            $end = Carbon::parse($tanggalLogOut)->endOfDay();
                        } elseif ($shift === 'sore') {
                            $start = Carbon::parse($tanggalLogOut . ' ' . $jadwal['checkin_deadline_time'])->addSecond();
                            $end = Carbon::parse($tanggalLogOut)->addDay()->endOfDay();
                        } elseif ($shift === 'malam') {
                            $start = Carbon::parse($tanggalLogOut)->addDay()->setTimeFromTimeString($jadwal['checkin_deadline_time'])->addSecond();
                            $end = Carbon::parse($tanggalLogOut)->addDay()->endOfDay();
                        } else {
                            return false;
                        }

                        return $logTime->between($start, $end, true);
                    })
                    ->sortByDesc('time') // cari yang paling akhir
                    ->first();


                // --- IN
                if ($logIn) {
                    $waktuMasuk = Carbon::parse($logIn['time']);

                    // Evaluasi tanggal acuan batas berdasarkan shift
                    $tanggalBatas = ($shift === 'malam')
                        ? Carbon::parse($tanggalLogIn)->addDay()->toDateString()
                        : $tanggalLogIn;

                    $batasAwal = Carbon::parse($tanggalLogIn . ' ' . $jadwal['checkin_time']);
                    $batasOntime = Carbon::parse($tanggalBatas . ' ' . $jadwal['work_time']);
                    $batasTerlambat = Carbon::parse($tanggalBatas . ' ' . $jadwal['checkin_deadline_time']);

                    if ($waktuMasuk->between($batasAwal, $batasOntime)) {
                        $return['label_in'] = 'dtg ontime';
                        $return['time_in'] = $waktuMasuk->format('H:i:s');
                        $return['time_dtg_cpt'] = $batasOntime->diff($waktuMasuk)->format('%H:%I:%S');
                    } elseif ($waktuMasuk->between($batasOntime->copy()->addSecond(), $batasTerlambat)) {
                        $return['label_in'] = 'terlambat';
                        $return['time_in'] = $waktuMasuk->format('H:i:s');
                        $return['time_terlambat'] = $waktuMasuk->diff($batasOntime)->format('%H:%I:%S');
                    }
                } else {
                    $return['label_in'] = 'tdk absen';
                }

                // // --- OUT
                if ($logOut) {

                    $waktuPulang = Carbon::parse($logOut['time']);

                    // Tentukan tanggal evaluasi berdasarkan shift
                    if ($shift === 'malam') {
                        // Untuk shift malam, logout biasanya di hari berikutnya
                        $tanggalEvaluasi = Carbon::parse($tanggalLogOut)->addDay()->toDateString();
                    } else {
                        $tanggalEvaluasi = $tanggalLogOut;
                    }

                    // Batas waktu pulang (ontime)
                    $batasPulang = Carbon::parse($tanggalEvaluasi . ' ' . $jadwal['checkout_time']);

                    // Batas waktu pulang cepat (maksimal masih dianggap cepat)
                    $batasPulangCepat = Carbon::parse($tanggalEvaluasi . ' ' . $jadwal['checkin_deadline_time']);

                    if ($waktuPulang->gte($batasPulang)) {
                        $return['label_out'] = 'plg ontime';
                        $return['time_out'] = $waktuPulang->format('H:i:s');
                    } else if ($waktuPulang->between($batasPulangCepat, $batasPulang->copy()->subSecond())) {
                        $return['label_out'] = 'plg cepat';
                        $return['time_out'] = $waktuPulang->format('H:i:s');
                        $return['time_plg_cpt'] = $batasPulang->diff($waktuPulang)->format('%H:%I:%S');
                    }
                } else {
                    $return['label_out'] = 'tdk absen';
                }

                if ($logIn || $logOut) {
                    $return['status'] = 'hadir';
                }

                if ($return['label_in'] === 'tdk absen' && $return['label_out'] === 'tdk absen') {
                    $return['label_in'] = 'alpha';
                    $return['label_out'] = 'alpha';
                    $return['status'] = 'alpha';
                }
            }

            $result[$tglIndex] = $return;
        }

        return $result;
    }

    public static function getAkumulasi($dateInMonth, $logAttendances, $schedules, $izin, $tglMerah)
    {
        $result = PublicHelper::getDtAbsen($dateInMonth, $logAttendances, $schedules, $izin, $tglMerah);
        // dd($result);
        $akumulasi['hari_bulan'] = count($result);

        $akumulasi['off'] = collect($result)->filter(function ($item) {
            return in_array($item['status'] ?? '', ['off']);
        })->count();

        $akumulasi['merah'] = collect($result)->filter(function ($item) {
            return in_array($item['status'] ?? '', ['tgl merah']);
        })->count();

        $akumulasi['hadir'] = collect($result)->filter(function ($item) {
            return in_array($item['status'] ?? '', ['hadir']);
        })->count();

        $akumulasi['hari_kerja'] = $akumulasi['hari_bulan'] - $akumulasi['off'] - $akumulasi['merah'];

        $akumulasi['alpa'] = collect($result)->filter(function ($item) {
            return in_array($item['label_in'] ?? '', ['alpha', 'Sakit', 'Keluar Urusan Pribadi', 'Pulang']) ||
                in_array($item['label_out'] ?? '', ['Sakit', 'Keluar Urusan Pribadi', 'Pulang']);
        })->count();

        $akumulasi['izin'] = collect($result)->filter(function ($item) {
            return in_array($item['label_in'] ?? '', ['Keluar Urusan Kerja', 'Dinas', 'Cuti']) ||
                in_array($item['label_out'] ?? '', ['Keluar Urusan Kerja', 'Dinas', 'Cuti']);
        })->count();

        $akumulasi['tdk_absen'] = collect($result)->filter(function ($item) {
            return ($item['label_in'] ?? '') === 'tdk absen' || ($item['label_out'] ?? '') === 'tdk absen';
        })->count();

        $akumulasi['loyal_time'] = collect($result)->reduce(function ($carry, $item) {
            $terlambat = PublicHelper::toSeconds($item['time_terlambat'] ?? 0);
            $plgCepat = PublicHelper::toSeconds($item['time_plg_cpt'] ?? 0);
            $dtgCepat = PublicHelper::toSeconds($item['time_dtg_cpt'] ?? 0);

            return $carry + ($dtgCepat - ($terlambat + $plgCepat));
        }, 0);

        $akumulasi['loyal_time_read'] =
            ($akumulasi['loyal_time'] >= 0 ? '+' : '-') . // kasih tanda + atau -
            number_format(abs($akumulasi['loyal_time'] / 3600), 3, ',', '');

        // Nilai poin maksimal dan faktor
        $poin_maksimal = 100;
        $alpa = $akumulasi['alpa']/$akumulasi['hari_kerja']*100;
        $izin = $akumulasi['izin']/$akumulasi['hari_kerja']*100/2;
        $tdkAbsen = $akumulasi['tdk_absen']/$akumulasi['hari_kerja']*100;
        $totalPoin = $poin_maksimal - $alpa - $izin - $tdkAbsen;
        $akumulasi['total_poin'] = floor($totalPoin * 100) / 100;

        // $poin_per_hari = $akumulasi['hari_kerja'] > 0 ? $poin_maksimal / $akumulasi['hari_kerja'] : 0;
        // $faktor_izin = 0.5;
        // $faktor_alpa = 0.0;

        // $akumulasi['hadir_poin'] = $akumulasi['hadir'] * $poin_per_hari;
        // $akumulasi['izin_poin'] = $akumulasi['izin'] * $poin_per_hari * $faktor_izin;
        // $akumulasi['alpa_poin'] = $akumulasi['alpa'] * $poin_per_hari * $faktor_alpa;

        // $akumulasi['total_poin'] = round($akumulasi['hadir_poin'] + $akumulasi['izin_poin'] + $akumulasi['alpa_poin'], 2);

        return $akumulasi;
    }
}
