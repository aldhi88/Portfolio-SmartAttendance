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

                // Cek apakah ini hari kerja regular (bukan lembur)
                $isRegular = $scheduleMatch['day_work']['regular'][$hariKe] ?? false;
                if (!$isRegular) { //jika jadwal bukan hari regular (lembur)
                    $result[$tglIndex] = $return;
                    continue; //langsung stop, return off
                }

                $kenaIzinMasuk = false;
                $kenaIzinKeluar = false;

                if ($izinTanggalIni) {
                    $izinStart = Carbon::parse($izinTanggalIni['from']);
                    $izinEnd = Carbon::parse($izinTanggalIni['to']);

                    $jamMasuk = Carbon::parse($tanggalYMD . ' ' . $scheduleMatch['checkin_deadline_time']);
                    $jamKeluar = Carbon::parse($tanggalYMD . ' ' . $scheduleMatch['checkout_time']);

                    // Cek apakah jam masuk dan keluar tercakup dalam izin
                    $kenaIzinMasuk = $izinStart->lte($jamMasuk) && $izinEnd->gte($jamMasuk);
                    $kenaIzinKeluar = $izinStart->lte($jamKeluar) && $izinEnd->gte($jamKeluar);

                    if ($kenaIzinMasuk) {
                        $return['label_in'] = $izinTanggalIni['jenis'];
                    }
                    if ($kenaIzinKeluar) {
                        $return['label_out'] = $izinTanggalIni['jenis'];
                    }

                    if ($kenaIzinMasuk || $kenaIzinKeluar) {
                        // Jika dua-duanya kena izin dan tidak perlu proses lebih lanjut, langsung continue
                        if ($kenaIzinMasuk && $kenaIzinKeluar) {
                            $return['status'] = 'izin';
                            $result[$tglIndex] = $return;
                            continue;
                        }
                    }
                }


                $checkinTime = $scheduleMatch['checkin_time'];
                $workTime = $scheduleMatch['work_time'];
                $checkinDeadline = $scheduleMatch['checkin_deadline_time'];
                $checkoutTime = $scheduleMatch['checkout_time'];

                // === IN ===
                $logIn = $logAttendances->filter(function ($log) use ($tanggalYMD, $checkinTime) {
                    $logTime = Carbon::parse($log['time']);
                    return $logTime->format('Y-m-d') === $tanggalYMD
                        && $logTime->format('H:i') >= $checkinTime;
                })->sortBy('time')->first();
                if (!$kenaIzinMasuk) {
                    $return['label_in'] = 'tdk absen'; //set nilai default
                    if ($logIn) {
                        $waktuMasuk = Carbon::parse($logIn['time'])->format('H:i');
                        $return['status'] = 'hadir';
                        if ($waktuMasuk >= $checkinTime && $waktuMasuk <= $workTime) {
                            $return['label_in'] = 'dtg ontime';
                            $return['time_in'] = $waktuMasuk;
                        } elseif ($waktuMasuk > $workTime && $waktuMasuk <= $checkinDeadline) {
                            $return['label_in'] = 'terlambat';
                            $return['time_in'] = $waktuMasuk;
                        }
                    }
                }
                // === OUT ===
                $logOut = $logAttendances->filter(function ($log) use ($tanggalYMD) {
                    return Carbon::parse($log['time'])->format('Y-m-d') === $tanggalYMD;
                })->sortByDesc('time')->first();

                if (!$kenaIzinKeluar) {
                    $return['label_out'] = 'tdk absen'; //set nilai default
                    if ($logOut) {
                        $waktuPulang = Carbon::parse($logOut['time'])->format('H:i');
                        $return['status'] = 'hadir';
                        if ($waktuPulang >= $checkoutTime) {
                            $return['label_out'] = 'plg ontime';
                            $return['time_out'] = $waktuPulang;
                        } elseif ($waktuPulang > $checkinDeadline && $waktuPulang < $checkoutTime) {
                            $return['label_out'] = 'plg cepat';
                            $return['time_out'] = $waktuPulang;
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
                        $return['time_in'] = $waktuMasuk->format('H:i');
                    } elseif ($waktuMasuk->between($batasOntime->copy()->addSecond(), $batasTerlambat)) {
                        $return['label_in'] = 'terlambat';
                        $return['time_in'] = $waktuMasuk->format('H:i');
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
                        $return['time_out'] = $waktuPulang->format('H:i');
                    } else if ($waktuPulang->between($batasPulangCepat, $batasPulang->copy()->subSecond())) {
                        $return['label_out'] = 'plg cepat';
                        $return['time_out'] = $waktuPulang->format('H:i');
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
        // dump($result);

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
            return in_array($item['label_in'] ?? '', ['alpha', 'Sakit', 'Pulang']) ||
                in_array($item['label_out'] ?? '', ['Sakit', 'Pulang']);
        })->count();

        $akumulasi['izin'] = collect($result)->filter(function ($item) {
            return in_array($item['label_in'] ?? '', ['Keluar', 'Dinas', 'Cuti']) ||
                in_array($item['label_out'] ?? '', ['Keluar', 'Dinas', 'Cuti']);
        })->count();

        // Nilai poin maksimal dan faktor
        $poin_maksimal = 100;
        $poin_per_hari = $akumulasi['hari_kerja'] > 0 ? $poin_maksimal / $akumulasi['hari_kerja'] : 0;
        $faktor_izin = 0.5;
        $faktor_alpa = 0.0;

        $akumulasi['hadir_poin'] = $akumulasi['hadir'] * $poin_per_hari;
        $akumulasi['izin_poin'] = $akumulasi['izin'] * $poin_per_hari * $faktor_izin;
        $akumulasi['alpa_poin'] = $akumulasi['alpa'] * $poin_per_hari * $faktor_alpa;

        $akumulasi['total_poin'] = round($akumulasi['hadir_poin'] + $akumulasi['izin_poin'] + $akumulasi['alpa_poin'], 2);


        return $akumulasi;
    }
}
