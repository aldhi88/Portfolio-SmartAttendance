<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;

class PublicHelper
{

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
                'time_dtg_lama' => 0,
                'time_plg_cpt' => 0,
                'time_plg_lama' => 0,
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
                    // $return['time_dtg_cpt'] = $workTime->diff($waktuMasuk)->format('%H:%I:%S');
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
                            $return['time_dtg_lama'] = $waktuMasuk->diff($workTime)->format('%H:%I:%S');
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
                            $return['time_plg_lama'] = $checkoutTime->diff($waktuPulang)->format('%H:%I:%S');
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
                            $end = Carbon::parse($tanggalLogOut . ' ' . $jadwal['checkout_time'])->addHours(3);
                        } elseif ($shift === 'sore') {
                            $start = Carbon::parse($tanggalLogOut . ' ' . $jadwal['checkin_deadline_time'])->addSecond();
                            $end = Carbon::parse($tanggalLogOut)->addDay()->setTimeFromTimeString($jadwal['checkout_time'])->addHours(3);
                        } elseif ($shift === 'malam') {
                            $start = Carbon::parse($tanggalLogOut)->addDay()->setTimeFromTimeString($jadwal['checkin_deadline_time'])->addSecond();
                            $end = Carbon::parse($tanggalLogOut)->addDay()->setTimeFromTimeString($jadwal['checkout_time'])->addHours(3);
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
                        $return['time_dtg_lama'] = $waktuMasuk->diff($batasOntime)->format('%H:%I:%S');
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
                        $return['time_plg_lama'] = $batasPulang->diff($waktuPulang)->format('%H:%I:%S');
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
        // dd(json_encode($result));

        return $result;
    }
}
