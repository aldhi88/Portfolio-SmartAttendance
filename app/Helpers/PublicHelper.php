<?php

namespace App\Helpers;

use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;

class PublicHelper
{

    public static function getHariIndo(): array
    {
        return ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
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

    public static function orderList()
    {
        return [
            'Nama (Asc)',
            'Datang paling cepat',
            'Datang paling lama',
            'Pulang paling cepat',
            'Pulang paling lama',
        ];
    }

    public static function dateInMonth($startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $dates = collect();
        while ($start->lte($end)) {
            $dates->push($start->format('Y-m-d'));
            $start->addDay();
        }

        return $dates->toArray();
    }

    public static function getDtAbsen($param)
    {
        // dump($param['data_attendance_claims']);
        $listJadwal = collect($param['jadwal']);


        foreach ($param['dateInMonth'] as $key => $value) {
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

            $tglCekCarbon = Carbon::parse($value);
            $tglStringYMD = $value;
            $tglIndex = $tglCekCarbon->format('d');
            $jadwalAktif = self::getJadwalAktifByDate($listJadwal, $tglCekCarbon);

            // dd($jadwalAktif, $tglIndex);
            // skip jika tidak ada jadwal aktif
            if (!$jadwalAktif) {
                $result[$tglIndex] = $return;
                continue;
            }

            $filtered = collect($param['data_attendance_claims'] ?? [])
                ->filter(function ($item) use ($tglCekCarbon) {
                    $absenDate = data_get($item, 'absen_date'); // aman untuk array/object
                    if (!$absenDate) return false;
                    return Carbon::parse($absenDate)->isSameDay($tglCekCarbon);
                })
                ->values();

            if ($jadwalAktif['type'] == 'Tetap') {
                $dtTetap['log'] = $param['log'];
                $dtTetap['izin'] = $param['izin'];
                $dtTetap['lembur'] = $param['lembur'];
                $dtTetap['return'] = $return;
                $dtTetap['jadwalAktif'] = $jadwalAktif;
                $dtTetap['tglCekCarbon'] = $tglCekCarbon;
                $dtTetap['tglStringYMD'] = $tglStringYMD;
                $dtTetap['tglMerah'] = $param['tglMerah'];
                $dtTetap['data_attendance_claims'] = $filtered;
                $return = self::cekTetap($dtTetap);
                $result[$tglIndex] = $return;
            }

            if ($jadwalAktif['type'] == 'Rotasi') {
                $dtRotasi['log'] = $param['log'];
                $dtRotasi['izin'] = $param['izin'];
                $dtRotasi['lembur'] = $param['lembur'];
                $dtRotasi['return'] = $return;
                $dtRotasi['jadwalAktif'] = $jadwalAktif;
                $dtRotasi['tglCekCarbon'] = $tglCekCarbon;
                $dtRotasi['tglStringYMD'] = $tglStringYMD;
                $dtRotasi['tglMerah'] = $param['tglMerah'];
                $dtRotasi['data_attendance_claims'] = $filtered;
                $return = self::cekRotasi($dtRotasi);
                $result[$tglIndex] = $return;
            }

            // dump($value,$param['data_attendance_claims']);
            if ($jadwalAktif['type'] == 'Hybrid') {
                // dd($param['data_attendance_claims']);
                $dtHybrid['log'] = $param['log'];
                $dtHybrid['izin'] = $param['izin'];
                $dtHybrid['lembur'] = $param['lembur'];
                $dtHybrid['return'] = $return;
                $dtHybrid['jadwalAktif'] = $jadwalAktif;
                $dtHybrid['tglCekCarbon'] = $tglCekCarbon;
                $dtHybrid['tglStringYMD'] = $tglStringYMD;
                $dtHybrid['tglMerah'] = $param['tglMerah'];
                $dtHybrid['data_attendance_claims'] = $filtered;
                $return = self::cekHybrid($dtHybrid);
                $result[$tglIndex] = $return;
                // dd(0);
            }

            // dd($param);
            if ($jadwalAktif['type'] == 'Bebas') {
                $dtBebas['log'] = $param['log'];
                $dtBebas['izin'] = $param['izin'];
                $dtBebas['lembur'] = $param['lembur'];
                $dtBebas['return'] = $return;
                $dtBebas['jadwalAktif'] = $jadwalAktif;
                $dtBebas['tglCekCarbon'] = $tglCekCarbon;
                $dtBebas['tglStringYMD'] = $tglStringYMD;
                $dtBebas['tglMerah'] = $param['tglMerah'];
                $dtBebas['data_attendance_claims'] = $filtered;
                $return = self::cekBebas($dtBebas);
                $result[$tglIndex] = $return;
                // dd(0);
            }

            // dd(0);
        }
        // dump($result); //dd disini error Undefined array key -1
        return $result;
    }

    public static function cekTetap($dt)
    {
        // dd($dt);
        $dt['return']['type'] = 'Tetap';
        $dayIndex = $dt['tglCekCarbon']->dayOfWeek;
        $hariKerja = $dt['jadwalAktif']['day_work']['day'];

        // jika tanggal merah
        if (in_array($dt['tglStringYMD'], $dt['tglMerah'])) {
            $dtLembur = self::checkLembur($dt['lembur'], $dt['tglCekCarbon'], $dt['return']);
            if ($dtLembur) {
                $timeIn = ReportLemburHelper::getLemburCheckin($dtLembur);
                $timeOut = ReportLemburHelper::getLemburCheckout($dtLembur);
                if ($timeIn !== '-' && $timeOut !== '-') {
                    $dt['return']['time_in']  = Carbon::parse($timeIn)->format('H:i:s');
                    $dt['return']['time_out'] = Carbon::parse($timeOut)->format('H:i:s');
                    $dt['return']['label_in'] = 'lembur';
                    $dt['return']['label_out'] = 'lembur';
                    $dt['return']['status'] = 'lembur';
                    return $dt['return'];
                }
            }

            $dt['return']['label_in'] = 'tgl merah';
            $dt['return']['label_out'] = 'tgl merah';
            $dt['return']['status'] = 'tgl merah';
            return $dt['return'];
        }

        // jika tidak hari kerja;
        if (!in_array($dayIndex, $hariKerja)) {
            $dtLembur = self::checkLembur($dt['lembur'], $dt['tglCekCarbon'], $dt['return']);
            if ($dtLembur) {
                $timeIn = ReportLemburHelper::getLemburCheckin($dtLembur);
                $timeOut = ReportLemburHelper::getLemburCheckout($dtLembur);
                if ($timeIn !== '-' && $timeOut !== '-') {
                    $dt['return']['time_in']  = Carbon::parse($timeIn)->format('H:i:s');
                    $dt['return']['time_out'] = Carbon::parse($timeOut)->format('H:i:s');
                    $dt['return']['label_in'] = 'lembur';
                    $dt['return']['label_out'] = 'lembur';
                    $dt['return']['status'] = 'lembur';
                    return $dt['return'];
                }
            }
            return $dt['return'];
        }

        // ===========Proses waktu kerja============
        $timeRule = self::getTimeRuleTetap($dt['jadwalAktif']['day_work']['time'], $dt['tglCekCarbon']);
        // ===========cek izin============
        $dtIzin = self::checkIzin($dt['izin'], $timeRule, $dt);
        $dt['return'] = $dtIzin['return'];
        if ($dtIzin['status']['kenaIzinMasuk'] && $dtIzin['status']['kenaIzinKeluar']) {
            $dt['return']['status'] = 'izin';
            return $dt['return'];
        }
        // ========CHECK STATUS ABSEN==========
        $dt['return']['status'] = 'hadir';

        $claimTimes = collect($dt['data_attendance_claims'] ?? [])
            ->map(fn($c) => data_get($c, 'time'))
            ->filter()
            ->map(fn($t) => Carbon::parse($t))
            ->values();

        // IN
        if (!$dtIzin['status']['kenaIzinMasuk']) {
            $dt['return']['label_in'] = 'tdk absen';

            // 1) coba dari CLAIM
            $logIn = $claimTimes->isNotEmpty()
                ? $claimTimes
                ->filter(fn($t) => $t >= $timeRule['checkin_start'] && $t <= $timeRule['checkin_end'])
                ->min()
                : null;

            // 2) kalau claim tidak ada / tidak masuk window → baru GPS
            if (!$logIn) {
                $logIn = collect($dt['log'] ?? [])
                    ->map(fn($l) => data_get($l, 'time'))
                    ->filter()
                    ->map(fn($t) => Carbon::parse($t))
                    ->filter(fn($t) => $t >= $timeRule['checkin_start'] && $t <= $timeRule['checkin_end'])
                    ->min();
            }

            // 3) terapkan aturan label (sama persis)
            if ($logIn) {
                $dt['return']['time_in'] = $logIn->format('H:i:s');

                if ($logIn <= $timeRule['checkin_ontime']) {
                    $dt['return']['label_in'] = 'dtg ontime';
                    $dt['return']['time_dtg_cpt'] = $timeRule['checkin_ontime']->diff($logIn)->format('%H:%I:%S');
                } else {
                    $dt['return']['label_in'] = 'terlambat';
                    $dt['return']['time_dtg_lama'] = $logIn->diff($timeRule['checkin_ontime'])->format('%H:%I:%S');
                }
            }
        }

        // OUT
        if (!$dtIzin['status']['kenaIzinKeluar']) {
            $dt['return']['label_out'] = 'tdk absen';

            // 1) coba dari CLAIM
            $logOut = $claimTimes->isNotEmpty()
                ? $claimTimes
                ->filter(fn($t) => $t > $timeRule['checkin_end'] && $t <= $timeRule['checkout_end'])
                ->max()
                : null;

            // 2) kalau claim tidak ada / tidak masuk window → baru GPS
            if (!$logOut) {
                $logOut = collect($dt['log'] ?? [])
                    ->map(fn($l) => data_get($l, 'time'))
                    ->filter()
                    ->map(fn($t) => Carbon::parse($t))
                    ->filter(fn($t) => $t > $timeRule['checkin_end'] && $t <= $timeRule['checkout_end'])
                    ->max();
            }

            // 3) terapkan aturan label (sama persis)
            if ($logOut) {
                $dt['return']['time_out'] = $logOut->format('H:i:s');

                if ($logOut < $timeRule['checkout_start']) {
                    $dt['return']['label_out'] = 'plg cepat';
                    $dt['return']['time_plg_cpt'] = $timeRule['checkout_start']->diff($logOut)->format('%H:%I:%S');
                } else {
                    $dt['return']['label_out'] = 'plg ontime';
                    $dt['return']['time_plg_lama'] = $logOut->diff($timeRule['checkout_start'])->format('%H:%I:%S');
                }
            }
        }

        // IN
        // if (!$dtIzin['status']['kenaIzinMasuk']) {
        //     $dt['return']['label_in'] = 'tdk absen';
        //     $logIn = collect($dt['log'])
        //         ->map(fn($l) => Carbon::parse($l['time']))
        //         ->filter(fn($t) => $t >= $timeRule['checkin_start'] && $t <= $timeRule['checkin_end'])
        //         ->min();
        //     if ($logIn) {
        //         $dt['return']['time_in'] = $logIn->format('H:i:s');
        //         if ($logIn <= $timeRule['checkin_ontime']) {
        //             $dt['return']['label_in'] = 'dtg ontime';
        //             $dt['return']['time_dtg_cpt'] = $timeRule['checkin_ontime']->diff($logIn)->format('%H:%I:%S');
        //         } else {
        //             $dt['return']['label_in'] = 'terlambat';
        //             $dt['return']['time_dtg_lama'] = $logIn->diff($timeRule['checkin_ontime'])->format('%H:%I:%S');
        //         }
        //     }
        // }

        // // OUT
        // if (!$dtIzin['status']['kenaIzinKeluar']) {
        //     $dt['return']['label_out'] = 'tdk absen';
        //     $logOut = collect($dt['log'])
        //         ->map(fn($l) => Carbon::parse($l['time']))
        //         ->filter(fn($t) => $t > $timeRule['checkin_end'] && $t <= $timeRule['checkout_end'])
        //         ->max();
        //     if ($logOut) {
        //         $dt['return']['time_out'] = $logOut->format('H:i:s');

        //         if ($logOut < $timeRule['checkout_start']) {
        //             $dt['return']['label_out'] = 'plg cepat';
        //             $dt['return']['time_plg_cpt'] = $timeRule['checkout_start']->diff($logOut)->format('%H:%I:%S');
        //         } else {
        //             $dt['return']['label_out'] = 'plg ontime';
        //             $dt['return']['time_plg_lama'] = $logOut->diff($timeRule['checkout_start'])->format('%H:%I:%S');
        //         }
        //     }
        // }

        if ($dt['return']['label_in'] === 'tdk absen' && $dt['return']['label_out'] === 'tdk absen') {
            $dt['return']['label_in'] = 'alpha';
            $dt['return']['label_out'] = 'alpha';
            $dt['return']['status'] = 'alpha';
        }

        return $dt['return'];
    }

    public static function cekRotasi($dt)
    {
        $dt['return']['type'] = 'Rotasi';
        $startDate = Carbon::parse($dt['jadwalAktif']['day_work']['start_date'])->startOfDay();
        $workDay = (int) $dt['jadwalAktif']['day_work']['work_day'];
        $offDay = (int) $dt['jadwalAktif']['day_work']['off_day'];
        $totalShift = count($dt['jadwalAktif']['day_work']['time']);
        $diff = $startDate->diffInDays($dt['tglCekCarbon']);
        $jumlahHariSiklus = ($workDay + $offDay) * $totalShift;
        $hariKeBrpDalamSiklus = $diff % $jumlahHariSiklus;
        $shiftIndex = intdiv($hariKeBrpDalamSiklus, ($workDay + $offDay)); // 0=Pagi, 1=Sore, 2=Malam
        // dd($shiftIndex, $dt);
        if ($shiftIndex < 0) {
            $dt['return']['label_in'] = 'Out Date';
            $dt['return']['label_out'] = 'Out Date';
            $dt['return']['status'] = 'outdate';
            return $dt['return'];
        }

        $hariKeBrpDalamSatuShift = $hariKeBrpDalamSiklus % ($workDay + $offDay);  // 0-3
        // jika tanggal merah
        if (in_array($dt['tglStringYMD'], $dt['tglMerah'])) {
            $dtLembur = self::checkLembur($dt['lembur'], $dt['tglCekCarbon'], $dt['return']);
            if ($dtLembur) {
                $timeIn = ReportLemburHelper::getLemburCheckin($dtLembur);
                $timeOut = ReportLemburHelper::getLemburCheckout($dtLembur);
                if ($timeIn !== '-' && $timeOut !== '-') {
                    $dt['return']['time_in']  = Carbon::parse($timeIn)->format('H:i:s');
                    $dt['return']['time_out'] = Carbon::parse($timeOut)->format('H:i:s');
                    $dt['return']['label_in'] = 'lembur';
                    $dt['return']['label_out'] = 'lembur';
                    $dt['return']['status'] = 'lembur';
                    return $dt['return'];
                }
            }

            $dt['return']['label_in'] = 'tgl merah';
            $dt['return']['label_out'] = 'tgl merah';
            $dt['return']['status'] = 'tgl merah';
            return $dt['return'];
        }
        // jika tidak hari kerja;
        if ($hariKeBrpDalamSatuShift >= $workDay) {
            // ===========cek lembur============
            if (count($dt['lembur']) > 0) {
                $dtLembur = self::checkLembur($dt['lembur'], $dt['tglCekCarbon'], $dt['return']);
                if ($dtLembur) {
                    $timeIn = ReportLemburHelper::getLemburCheckin($dtLembur);
                    $timeOut = ReportLemburHelper::getLemburCheckout($dtLembur);
                    if ($timeIn !== '-' && $timeOut !== '-') {
                        $dt['return']['time_in']  = Carbon::parse($timeIn)->format('H:i:s');
                        $dt['return']['time_out'] = Carbon::parse($timeOut)->format('H:i:s');
                        $dt['return']['label_in'] = 'lembur';
                        $dt['return']['label_out'] = 'lembur';
                        $dt['return']['status'] = 'lembur';
                        return $dt['return'];
                    }
                }
            }
            return $dt['return'];
        }

        $dt['return']['shift'] = $dt['jadwalAktif']['day_work']['time'][$shiftIndex]['name'];
        // ===========Proses waktu kerja============
        $timeRule = self::getTimeRuleRotasi($dt['jadwalAktif']['day_work']['time'], $dt['tglCekCarbon'], $shiftIndex);

        // ===========cek izin============
        $dtIzin = self::checkIzin($dt['izin'], $timeRule, $dt);
        $dt['return'] = $dtIzin['return'];
        if ($dtIzin['status']['kenaIzinMasuk'] && $dtIzin['status']['kenaIzinKeluar']) {
            $dt['return']['status'] = 'izin';
            return $dt['return'];
        }

        // ========CHECK STATUS ABSEN==========
        $dt['return']['status'] = 'hadir';

        $claimTimes = collect($dt['data_attendance_claims'] ?? [])
            ->map(fn($c) => data_get($c, 'time'))
            ->filter()
            ->map(fn($t) => Carbon::parse($t))
            ->values();

        // IN
        if (!$dtIzin['status']['kenaIzinMasuk']) {
            $dt['return']['label_in'] = 'tdk absen';

            // 1) coba dari CLAIM
            $logIn = $claimTimes->isNotEmpty()
                ? $claimTimes
                ->filter(fn($t) => $t >= $timeRule['checkin_start'] && $t <= $timeRule['checkin_end'])
                ->min()
                : null;

            // 2) kalau claim tidak ada  baru cek log
            if (!$logIn) {
                $logIn = collect($dt['log'] ?? [])
                    ->map(fn($l) => data_get($l, 'time'))
                    ->filter()
                    ->map(fn($t) => Carbon::parse($t))
                    ->filter(fn($t) => $t >= $timeRule['checkin_start'] && $t <= $timeRule['checkin_end'])
                    ->min();
            }

            // 3) terapkan aturan label (sama persis)
            if ($logIn) {
                $dt['return']['time_in'] = $logIn->format('H:i:s');

                if ($logIn <= $timeRule['checkin_ontime']) {
                    $dt['return']['label_in'] = 'dtg ontime';
                    $dt['return']['time_dtg_cpt'] = $timeRule['checkin_ontime']->diff($logIn)->format('%H:%I:%S');
                } else {
                    $dt['return']['label_in'] = 'terlambat';
                    $dt['return']['time_dtg_lama'] = $logIn->diff($timeRule['checkin_ontime'])->format('%H:%I:%S');
                }
            }
        }

        // OUT
        if (!$dtIzin['status']['kenaIzinKeluar']) {
            $dt['return']['label_out'] = 'tdk absen';

            // 1) coba dari CLAIM
            $logOut = $claimTimes->isNotEmpty()
                ? $claimTimes
                ->filter(fn($t) => $t > $timeRule['checkin_end'] && $t <= $timeRule['checkout_end'])
                ->max()
                : null;

            // 2) kalau claim tidak ada baru log
            if (!$logOut) {
                $logOut = collect($dt['log'] ?? [])
                    ->map(fn($l) => data_get($l, 'time'))
                    ->filter()
                    ->map(fn($t) => Carbon::parse($t))
                    ->filter(fn($t) => $t > $timeRule['checkin_end'] && $t <= $timeRule['checkout_end'])
                    ->max();
            }

            // 3) terapkan aturan label (sama persis)
            if ($logOut) {
                $dt['return']['time_out'] = $logOut->format('H:i:s');

                if ($logOut < $timeRule['checkout_start']) {
                    $dt['return']['label_out'] = 'plg cepat';
                    $dt['return']['time_plg_cpt'] = $timeRule['checkout_start']->diff($logOut)->format('%H:%I:%S');
                } else {
                    $dt['return']['label_out'] = 'plg ontime';
                    $dt['return']['time_plg_lama'] = $logOut->diff($timeRule['checkout_start'])->format('%H:%I:%S');
                }
            }
        }

        // // IN
        // if (!$dtIzin['status']['kenaIzinMasuk']) {
        //     $dt['return']['label_in'] = 'tdk absen';
        //     $logIn = collect($dt['log'])
        //         ->map(fn($l) => Carbon::parse($l['time']))
        //         ->filter(fn($t) => $t >= $timeRule['checkin_start'] && $t <= $timeRule['checkin_end'])
        //         ->min();
        //     if ($logIn) {
        //         $dt['return']['time_in'] = $logIn->format('H:i:s');
        //         if ($logIn <= $timeRule['checkin_ontime']) {
        //             $dt['return']['label_in'] = 'dtg ontime';
        //             $dt['return']['time_dtg_cpt'] = $timeRule['checkin_ontime']->diff($logIn)->format('%H:%I:%S');
        //         } else {
        //             $dt['return']['label_in'] = 'terlambat';
        //             $dt['return']['time_dtg_lama'] = $logIn->diff($timeRule['checkin_ontime'])->format('%H:%I:%S');
        //         }
        //     }
        // }

        // // OUT
        // if (!$dtIzin['status']['kenaIzinKeluar']) {
        //     $dt['return']['label_out'] = 'tdk absen';
        //     $logOut = collect($dt['log'])
        //         ->map(fn($l) => Carbon::parse($l['time']))
        //         ->filter(fn($t) => $t > $timeRule['checkin_end'] && $t <= $timeRule['checkout_end'])
        //         ->max();
        //     if ($logOut) {
        //         $dt['return']['time_out'] = $logOut->format('H:i:s');

        //         if ($logOut < $timeRule['checkout_start']) {
        //             $dt['return']['label_out'] = 'plg cepat';
        //             $dt['return']['time_plg_cpt'] = $timeRule['checkout_start']->diff($logOut)->format('%H:%I:%S');
        //         } else {
        //             $dt['return']['label_out'] = 'plg ontime';
        //             $dt['return']['time_plg_lama'] = $logOut->diff($timeRule['checkout_start'])->format('%H:%I:%S');
        //         }
        //     }
        // }


        if ($dt['return']['label_in'] === 'tdk absen' && $dt['return']['label_out'] === 'tdk absen') {
            $dt['return']['label_in'] = 'alpha';
            $dt['return']['label_out'] = 'alpha';
            $dt['return']['status'] = 'alpha';
        }
        return $dt['return'];
    }

    public static function cekHybrid($dt)
    {
        $dt['return']['type'] = 'Hybrid';

        // jika tanggal merah
        if (in_array($dt['tglStringYMD'], $dt['tglMerah'])) {
            $dtLembur = self::checkLembur($dt['lembur'], $dt['tglCekCarbon'], $dt['return']);
            if ($dtLembur) {
                $timeIn = ReportLemburHelper::getLemburCheckin($dtLembur);
                $timeOut = ReportLemburHelper::getLemburCheckout($dtLembur);
                if ($timeIn !== '-' && $timeOut !== '-') {
                    $dt['return']['time_in']  = Carbon::parse($timeIn)->format('H:i:s');
                    $dt['return']['time_out'] = Carbon::parse($timeOut)->format('H:i:s');
                    $dt['return']['label_in'] = 'lembur';
                    $dt['return']['label_out'] = 'lembur';
                    $dt['return']['status'] = 'lembur';
                    return $dt['return'];
                }
            }

            $dt['return']['label_in'] = 'tgl merah';
            $dt['return']['label_out'] = 'tgl merah';
            $dt['return']['status'] = 'tgl merah';
            return $dt['return'];
        }

        // dd($dt['tglCekCarbon'], $dt['data_attendance_claims']);

        // jika tidak hari kerja;
        $dayIndex = $dt['tglCekCarbon']->dayOfWeek;
        $hariKerja = $dt['jadwalAktif']['day_work']['day'];
        if (!array_key_exists($dayIndex, $hariKerja)) {
            $dtLembur = self::checkLembur($dt['lembur'], $dt['tglCekCarbon'], $dt['return']);
            if ($dtLembur) {
                $timeIn = ReportLemburHelper::getLemburCheckin($dtLembur);
                $timeOut = ReportLemburHelper::getLemburCheckout($dtLembur);
                if ($timeIn !== '-' && $timeOut !== '-') {
                    $dt['return']['time_in']  = Carbon::parse($timeIn)->format('H:i:s');
                    $dt['return']['time_out'] = Carbon::parse($timeOut)->format('H:i:s');
                    $dt['return']['label_in'] = 'lembur';
                    $dt['return']['label_out'] = 'lembur';
                    $dt['return']['status'] = 'lembur';
                    return $dt['return'];
                }
            }
            return $dt['return'];
        }

        $startDate = Carbon::parse($dt['jadwalAktif']['day_work']['start_date'])->startOfDay();
        $workDay = (int) $dt['jadwalAktif']['day_work']['work_day'];
        $offDay = (int) $dt['jadwalAktif']['day_work']['off_day'];
        $totalShift = count($dt['jadwalAktif']['day_work']['time']);
        // $diff = $startDate->diffInDays($dt['tglCekCarbon']); //salah sabtu minggu kehitung
        $diff = collect(CarbonPeriod::create($startDate, $dt['tglCekCarbon']))
            ->filter(fn(Carbon $d) => in_array($d->dayOfWeek, array_keys($hariKerja)))
            ->count() - 1;

        $jumlahHariSiklus = ($workDay + $offDay) * $totalShift;
        $hariKeBrpDalamSiklus = $diff % $jumlahHariSiklus;
        $shiftIndex = intdiv($hariKeBrpDalamSiklus, ($workDay + $offDay)); // 0=Pagi, 1=Sore, 2=Malam
        $hariKeBrpDalamSatuShift = $hariKeBrpDalamSiklus % ($workDay + $offDay);  // 0-3
        // jika tidak hari kerja;
        if ($hariKeBrpDalamSatuShift >= $workDay) {
            // ===========cek lembur============
            if (count($dt['lembur']) > 0) {
                $dtLembur = self::checkLembur($dt['lembur'], $dt['tglCekCarbon'], $dt['return']);
                if ($dtLembur) {
                    $dt['return']['label_in'] = 'lembur';
                    $dt['return']['label_out'] = 'lembur';
                    $dt['return']['status'] = 'lembur';
                    return $dt['return'];
                }
            }
            return $dt['return'];
        }

        $dt['return']['shift'] = $dt['jadwalAktif']['day_work']['time'][$shiftIndex]['name'];

        // ===========Proses waktu kerja============
        $timeRule = self::getTimeRuleRotasi($dt['jadwalAktif']['day_work']['time'], $dt['tglCekCarbon'], $shiftIndex);

        // ===========cek izin============
        $dtIzin = self::checkIzin($dt['izin'], $timeRule, $dt);
        $dt['return'] = $dtIzin['return'];
        if ($dtIzin['status']['kenaIzinMasuk'] && $dtIzin['status']['kenaIzinKeluar']) {
            $dt['return']['status'] = 'izin';
            return $dt['return'];
        }

        // dd($dt);

        // ========CHECK STATUS ABSEN==========
        $dt['return']['status'] = 'hadir';

        $claimTimes = collect($dt['data_attendance_claims'] ?? [])
            ->map(fn($c) => data_get($c, 'time'))
            ->filter()
            ->map(fn($t) => Carbon::parse($t))
            ->values();

        // IN
        if (!$dtIzin['status']['kenaIzinMasuk']) {
            $dt['return']['label_in'] = 'tdk absen';

            // 1) coba dari CLAIM
            $logIn = $claimTimes->isNotEmpty()
                ? $claimTimes
                ->filter(fn($t) => $t >= $timeRule['checkin_start'] && $t <= $timeRule['checkin_end'])
                ->min()
                : null;

            // 2) kalau claim tidak ada / tidak masuk window → baru GPS
            if (!$logIn) {
                $logIn = collect($dt['log'] ?? [])
                    ->map(fn($l) => data_get($l, 'time'))
                    ->filter()
                    ->map(fn($t) => Carbon::parse($t))
                    ->filter(fn($t) => $t >= $timeRule['checkin_start'] && $t <= $timeRule['checkin_end'])
                    ->min();
            }

            // 3) terapkan aturan label (sama persis)
            if ($logIn) {
                $dt['return']['time_in'] = $logIn->format('H:i:s');

                if ($logIn <= $timeRule['checkin_ontime']) {
                    $dt['return']['label_in'] = 'dtg ontime';
                    $dt['return']['time_dtg_cpt'] = $timeRule['checkin_ontime']->diff($logIn)->format('%H:%I:%S');
                } else {
                    $dt['return']['label_in'] = 'terlambat';
                    $dt['return']['time_dtg_lama'] = $logIn->diff($timeRule['checkin_ontime'])->format('%H:%I:%S');
                }
            }
        }

        // OUT
        if (!$dtIzin['status']['kenaIzinKeluar']) {
            $dt['return']['label_out'] = 'tdk absen';

            // 1) coba dari CLAIM
            $logOut = $claimTimes->isNotEmpty()
                ? $claimTimes
                ->filter(fn($t) => $t > $timeRule['checkin_end'] && $t <= $timeRule['checkout_end'])
                ->max()
                : null;

            // 2) kalau claim tidak ada / tidak masuk window → baru GPS
            if (!$logOut) {
                $logOut = collect($dt['log'] ?? [])
                    ->map(fn($l) => data_get($l, 'time'))
                    ->filter()
                    ->map(fn($t) => Carbon::parse($t))
                    ->filter(fn($t) => $t > $timeRule['checkin_end'] && $t <= $timeRule['checkout_end'])
                    ->max();
            }

            // 3) terapkan aturan label (sama persis)
            if ($logOut) {
                $dt['return']['time_out'] = $logOut->format('H:i:s');

                if ($logOut < $timeRule['checkout_start']) {
                    $dt['return']['label_out'] = 'plg cepat';
                    $dt['return']['time_plg_cpt'] = $timeRule['checkout_start']->diff($logOut)->format('%H:%I:%S');
                } else {
                    $dt['return']['label_out'] = 'plg ontime';
                    $dt['return']['time_plg_lama'] = $logOut->diff($timeRule['checkout_start'])->format('%H:%I:%S');
                }
            }
        }

        // // IN
        // if (!$dtIzin['status']['kenaIzinMasuk']) {
        //     $dt['return']['label_in'] = 'tdk absen';

        //     // if(count($dt['data_attendance_claims'])>0){
        //     //     //proses claim, kasi keterangan label_in,time_dtg_cpt,time_in seperti logic if ($logIn)
        //     // }

        //     $logIn = collect($dt['log'])
        //         ->map(fn($l) => Carbon::parse($l['time']))
        //         ->filter(fn($t) => $t >= $timeRule['checkin_start'] && $t <= $timeRule['checkin_end'])
        //         ->min();
        //     if ($logIn) {
        //         $dt['return']['time_in'] = $logIn->format('H:i:s');
        //         if ($logIn <= $timeRule['checkin_ontime']) {
        //             $dt['return']['label_in'] = 'dtg ontime';
        //             $dt['return']['time_dtg_cpt'] = $timeRule['checkin_ontime']->diff($logIn)->format('%H:%I:%S');
        //         } else {
        //             $dt['return']['label_in'] = 'terlambat';
        //             $dt['return']['time_dtg_lama'] = $logIn->diff($timeRule['checkin_ontime'])->format('%H:%I:%S');
        //         }
        //     }
        // }

        // // OUT
        // if (!$dtIzin['status']['kenaIzinKeluar']) {
        //     $dt['return']['label_out'] = 'tdk absen';

        //     // if(count($dt['data_attendance_claims'])>0){
        //     //     //proses claim, kasi keterangan label_out,time_plg_cpt,time_out seperti logic if ($logIn)
        //     // }

        //     $logOut = collect($dt['log'])
        //         ->map(fn($l) => Carbon::parse($l['time']))
        //         ->filter(fn($t) => $t > $timeRule['checkin_end'] && $t <= $timeRule['checkout_end'])
        //         ->max();
        //     if ($logOut) {
        //         $dt['return']['time_out'] = $logOut->format('H:i:s');

        //         if ($logOut < $timeRule['checkout_start']) {
        //             $dt['return']['label_out'] = 'plg cepat';
        //             $dt['return']['time_plg_cpt'] = $timeRule['checkout_start']->diff($logOut)->format('%H:%I:%S');
        //         } else {
        //             $dt['return']['label_out'] = 'plg ontime';
        //             $dt['return']['time_plg_lama'] = $logOut->diff($timeRule['checkout_start'])->format('%H:%I:%S');
        //         }
        //     }
        // }

        if ($dt['return']['label_in'] === 'tdk absen' && $dt['return']['label_out'] === 'tdk absen') {
            $dt['return']['label_in'] = 'alpha';
            $dt['return']['label_out'] = 'alpha';
            $dt['return']['status'] = 'alpha';
        }

        // dd($dt);
        return $dt['return'];
    }

    public static function cekBebas($dt)
    {
        // dump($dt);
        $dt['return']['type'] = 'Bebas';

        // ====== Cari data jadwal bebas sesuai tanggal ======
        $tanggalCek = $dt['tglCekCarbon']->format('Y-m-d');
        $jadwalBebas = collect($dt['jadwalAktif']['data_schedule_bebas'] ?? [])
            ->firstWhere('tanggal', $tanggalCek);

        // jika tanggal merah
        if (in_array($dt['tglStringYMD'], $dt['tglMerah'])) {
            $dtLembur = self::checkLembur($dt['lembur'], $dt['tglCekCarbon'], $dt['return']);
            if ($dtLembur) {
                $timeIn = ReportLemburHelper::getLemburCheckin($dtLembur);
                $timeOut = ReportLemburHelper::getLemburCheckout($dtLembur);
                if ($timeIn !== '-' && $timeOut !== '-') {
                    $dt['return']['time_in']  = Carbon::parse($timeIn)->format('H:i:s');
                    $dt['return']['time_out'] = Carbon::parse($timeOut)->format('H:i:s');
                    $dt['return']['label_in'] = 'lembur';
                    $dt['return']['label_out'] = 'lembur';
                    $dt['return']['status'] = 'lembur';
                    return $dt['return'];
                }
            }

            $dt['return']['label_in'] = 'tgl merah';
            $dt['return']['label_out'] = 'tgl merah';
            $dt['return']['status'] = 'tgl merah';
            return $dt['return'];
        }

        // Jika tidak ada jadwal untuk tanggal itu → off (kecuali lembur)
        if (!$jadwalBebas) {
            $dtLembur = self::checkLembur($dt['lembur'] ?? [], $dt['tglCekCarbon'], $dt['return']);
            if ($dtLembur) {
                $timeIn = ReportLemburHelper::getLemburCheckin($dtLembur);
                $timeOut = ReportLemburHelper::getLemburCheckout($dtLembur);
                if ($timeIn !== '-' && $timeOut !== '-') {
                    $dt['return']['time_in']  = Carbon::parse($timeIn)->format('H:i:s');
                    $dt['return']['time_out'] = Carbon::parse($timeOut)->format('H:i:s');
                    $dt['return']['label_in'] = 'lembur';
                    $dt['return']['label_out'] = 'lembur';
                    $dt['return']['status'] = 'lembur';
                    return $dt['return'];
                }
            }
            return $dt['return'];
        }

        // ====== Ambil jadwal untuk tanggal itu ======
        $jw = $jadwalBebas['day_work'] ?? [];

        // Pastikan field yang diperlukan ada
        foreach (['checkin_time', 'work_time', 'checkin_deadline_time', 'checkout_time', 'checkout_deadline_time'] as $k) {
            if (!isset($jw[$k])) {
                // Data tidak lengkap → treat as off
                return $dt['return'];
            }
        }

        // ====== Bangun timeRule yang konsisten + tangani lintas hari ======
        $checkin_start   = Carbon::parse("$tanggalCek {$jw['checkin_time']}");
        $checkin_ontime  = Carbon::parse("$tanggalCek {$jw['work_time']}");                // <- patokan ontime yg benar
        $checkin_end     = Carbon::parse("$tanggalCek {$jw['checkin_deadline_time']}");

        $checkout_start  = Carbon::parse("$tanggalCek {$jw['checkout_time']}");
        $checkout_end    = Carbon::parse("$tanggalCek {$jw['checkout_deadline_time']}");

        // Jika batas2 checkin melewati tengah malam (jarang, tapi amankan)
        if ($checkin_ontime->lt($checkin_start))  $checkin_ontime->addDay();
        if ($checkin_end->lt($checkin_start))     $checkin_end->addDay();

        // Shift lintas hari: checkout_* ≤ checkin_start → geser ke hari berikutnya
        if ($checkout_start->lte($checkin_start)) $checkout_start->addDay();
        if ($checkout_end->lte($checkin_start))   $checkout_end->addDay();

        $timeRule = compact(
            'checkin_start',
            'checkin_ontime',
            'checkin_end',
            'checkout_start',
            'checkout_end'
        );

        $dt['return']['shift'] = '-';

        // ===========cek izin============
        $dtIzin = self::checkIzin($dt['izin'] ?? [], $timeRule, $dt);
        $dt['return'] = $dtIzin['return'];
        if ($dtIzin['status']['kenaIzinMasuk'] && $dtIzin['status']['kenaIzinKeluar']) {
            $dt['return']['status'] = 'izin';
            return $dt['return'];
        }

        // ========CHECK STATUS ABSEN==========
        $dt['return']['status'] = 'hadir';

        $claimTimes = collect($dt['data_attendance_claims'] ?? [])
            ->map(fn($c) => data_get($c, 'time'))
            ->filter()
            ->map(fn($t) => Carbon::parse($t))
            ->values();

        // IN
        if (!$dtIzin['status']['kenaIzinMasuk']) {
            $dt['return']['label_in'] = 'tdk absen';

            // 1) coba dari CLAIM
            $logIn = $claimTimes->isNotEmpty()
                ? $claimTimes
                ->filter(fn($t) => $t >= $timeRule['checkin_start'] && $t <= $timeRule['checkin_end'])
                ->min()
                : null;

            // 2) kalau claim tidak ada  baru cek log
            if (!$logIn) {
                $logIn = collect($dt['log'] ?? [])
                    ->map(fn($l) => data_get($l, 'time'))
                    ->filter()
                    ->map(fn($t) => Carbon::parse($t))
                    ->filter(fn($t) => $t >= $timeRule['checkin_start'] && $t <= $timeRule['checkin_end'])
                    ->min();
            }

            // 3) terapkan aturan label (sama persis)
            if ($logIn) {
                $dt['return']['time_in'] = $logIn->format('H:i:s');

                if ($logIn <= $timeRule['checkin_ontime']) {
                    $dt['return']['label_in'] = 'dtg ontime';
                    $dt['return']['time_dtg_cpt'] = $timeRule['checkin_ontime']->diff($logIn)->format('%H:%I:%S');
                } else {
                    $dt['return']['label_in'] = 'terlambat';
                    $dt['return']['time_dtg_lama'] = $logIn->diff($timeRule['checkin_ontime'])->format('%H:%I:%S');
                }
            }
        }

        // OUT
        if (!$dtIzin['status']['kenaIzinKeluar']) {
            $dt['return']['label_out'] = 'tdk absen';

            // 1) coba dari CLAIM
            $logOut = $claimTimes->isNotEmpty()
                ? $claimTimes
                ->filter(fn($t) => $t > $timeRule['checkin_end'] && $t <= $timeRule['checkout_end'])
                ->max()
                : null;

            // 2) kalau claim tidak ada baru log
            if (!$logOut) {
                $logOut = collect($dt['log'] ?? [])
                    ->map(fn($l) => data_get($l, 'time'))
                    ->filter()
                    ->map(fn($t) => Carbon::parse($t))
                    ->filter(fn($t) => $t > $timeRule['checkin_end'] && $t <= $timeRule['checkout_end'])
                    ->max();
            }

            // 3) terapkan aturan label (sama persis)
            if ($logOut) {
                $dt['return']['time_out'] = $logOut->format('H:i:s');

                if ($logOut < $timeRule['checkout_start']) {
                    $dt['return']['label_out'] = 'plg cepat';
                    $dt['return']['time_plg_cpt'] = $timeRule['checkout_start']->diff($logOut)->format('%H:%I:%S');
                } else {
                    $dt['return']['label_out'] = 'plg ontime';
                    $dt['return']['time_plg_lama'] = $logOut->diff($timeRule['checkout_start'])->format('%H:%I:%S');
                }
            }
        }

        // // IN
        // if (!$dtIzin['status']['kenaIzinMasuk']) {
        //     $dt['return']['label_in'] = 'tdk absen';
        //     $logIn = collect($dt['log'] ?? [])
        //         ->map(fn($l) => Carbon::parse($l['time']))
        //         ->filter(fn($t) => $t >= $timeRule['checkin_start'] && $t <= $timeRule['checkin_end'])
        //         ->min();

        //     if ($logIn) {
        //         $dt['return']['time_in'] = $logIn->format('H:i:s');

        //         // On-time jika datang <= work_time; selebihnya terlambat
        //         if ($logIn->lte($timeRule['checkin_ontime'])) {
        //             $dt['return']['label_in'] = 'dtg ontime';
        //             $dt['return']['time_dtg_cpt'] = $timeRule['checkin_ontime']->diff($logIn)->format('%H:%I:%S');
        //         } else {
        //             $dt['return']['label_in'] = 'terlambat';
        //             $dt['return']['time_dtg_lama'] = $logIn->diff($timeRule['checkin_ontime'])->format('%H:%I:%S');
        //         }
        //     }
        // }

        // // OUT
        // if (!$dtIzin['status']['kenaIzinKeluar']) {
        //     $dt['return']['label_out'] = 'tdk absen';
        //     $logOut = collect($dt['log'] ?? [])
        //         ->map(fn($l) => Carbon::parse($l['time']))
        //         ->filter(fn($t) => $t > $timeRule['checkin_end'] && $t <= $timeRule['checkout_end'])
        //         ->max();

        //     if ($logOut) {
        //         $dt['return']['time_out'] = $logOut->format('H:i:s');

        //         // Pulang cepat jika sebelum checkout_start; sisanya ontime/normal (atau overtime dihitung via selisih)
        //         if ($logOut->lt($timeRule['checkout_start'])) {
        //             $dt['return']['label_out'] = 'plg cepat';
        //             $dt['return']['time_plg_cpt'] = $timeRule['checkout_start']->diff($logOut)->format('%H:%I:%S');
        //         } else {
        //             $dt['return']['label_out'] = 'plg ontime';
        //             $dt['return']['time_plg_lama'] = $logOut->diff($timeRule['checkout_start'])->format('%H:%I:%S');
        //         }
        //     }
        // }

        // === Tidak ada absen sama sekali ===
        if (($dt['return']['label_in'] ?? null) === 'tdk absen' && ($dt['return']['label_out'] ?? null) === 'tdk absen') {
            $dt['return']['label_in'] = 'alpha';
            $dt['return']['label_out'] = 'alpha';
            $dt['return']['status'] = 'alpha';
        }

        return $dt['return'];
    }

    public static function getLogCheck($log, $timeRule)
    {
        $return['logIn'] = collect($log)
            ->map(fn($l) => Carbon::parse($l['time']))
            ->filter(fn($t) => $t >= $timeRule['checkin_start'] && $t <= $timeRule['checkin_end'])
            ->min();

        $return['logOut'] = collect($log)
            ->map(fn($l) => Carbon::parse($l['time']))
            ->filter(fn($t) => $t > $timeRule['checkin_end'] && $t <= $timeRule['checkout_end'])
            ->max();

        return $return;
    }

    public static function checkLembur($dtLembur, $tglCekCarbon, $dt)
    {
        return collect($dtLembur)->first(function ($item) use ($tglCekCarbon) {
            return !empty($item['tanggal'])
                && $tglCekCarbon->isSameDay(Carbon::parse($item['tanggal']));
        }) ?? false;
    }

    public static function checkIzin($dtIzin, $timeRule, $dt)
    {

        $izinCollection = collect($dtIzin)->map(function ($izin) {
            return [
                'jenis' => $izin['jenis'],
                'from' => Carbon::parse($izin['from']),
                'to' => Carbon::parse($izin['to']),
            ];
        });

        $izinMasuk = $izinCollection->first(
            fn($izin) =>
            $timeRule['checkin_start'] <= $izin['to'] &&
                $timeRule['checkin_end']   >= $izin['from']
        );

        $izinKeluar = $izinCollection->first(
            fn($izin) =>
            $timeRule['checkout_start'] <= $izin['to'] &&
                $timeRule['checkout_end']   >= $izin['from']
        );


        // dd($izinMasuk, $izinKeluar, $izinCollection, $timeRule);

        $dt['status']['kenaIzinMasuk'] = false;
        $dt['status']['kenaIzinKeluar'] = false;

        if ($izinMasuk) {
            $dt['return']['label_in'] = $izinMasuk['jenis'];
            $dt['status']['kenaIzinMasuk'] = true;
        }

        if ($izinKeluar) {
            $dt['return']['label_out'] = $izinKeluar['jenis'];
            $dt['status']['kenaIzinKeluar'] = true;
        }

        // dd($dt);
        return $dt;
    }

    public static function getTimeRuleTetap($dt, $tglCek)
    {
        $checkinStart = $dt['checkin_time'];
        $checkinOntime = $dt['work_time'];
        $checkinEnd = $dt['checkin_deadline_time'];
        $checkoutStart = $dt['checkout_time'];
        $checkoutEnd = $dt['checkout_deadline_time'];
        // format waktu ke tanggal+waktu
        $return['checkin_start'] = $tglCek->copy()->setTimeFromTimeString($checkinStart);

        $return['checkin_ontime'] = $tglCek->copy()->setTimeFromTimeString($checkinOntime);
        if ($return['checkin_ontime']->lt($return['checkin_start'])) {
            $return['checkin_ontime']->addDay();
        }
        $return['checkin_end'] = $tglCek->copy()->setTimeFromTimeString($checkinEnd);
        if ($return['checkin_end']->lt($return['checkin_start'])) {
            $return['checkin_end']->addDay();
        }
        $return['checkout_start'] = $tglCek->copy()->setTimeFromTimeString($checkoutStart);
        if ($return['checkout_start']->lt($return['checkin_start'])) {
            $return['checkout_start']->addDay();
        }
        $return['checkout_end'] = $tglCek->copy()->setTimeFromTimeString($checkoutEnd);
        if ($return['checkout_end']->lt($return['checkin_start'])) {
            $return['checkout_end']->addDay();
        }
        return $return;
    }


    public static function getTimeRuleRotasi($shiftTimeList, $tglCek, $shiftIndex)
    {
        $shift = $shiftTimeList[$shiftIndex];

        $return['checkin_ontime'] = $tglCek->copy()->setTimeFromTimeString($shift['work_time']);
        $return['checkin_start']  = $tglCek->copy()->setTimeFromTimeString($shift['checkin_time']);

        if ($return['checkin_start']->gt($return['checkin_ontime'])) {
            $return['checkin_start']->subDay();
        }

        $return['checkin_end'] = $tglCek->copy()->setTimeFromTimeString($shift['checkin_deadline_time']);
        if ($return['checkin_end']->lt($return['checkin_ontime'])) {
            $return['checkin_end']->addDay();
        }

        $return['checkout_start'] = $tglCek->copy()->setTimeFromTimeString($shift['checkout_time']);
        if ($return['checkout_start']->lt($return['checkin_ontime'])) {
            $return['checkout_start']->addDay();
        }

        $return['checkout_end'] = $tglCek->copy()->setTimeFromTimeString($shift['checkout_deadline_time']);
        if ($return['checkout_end']->lt($return['checkin_ontime'])) {
            $return['checkout_end']->addDay();
        }

        return $return;
    }

    public static function getJadwalAktifByDate($listJadwal, $tglCek)
    {
        $return = $listJadwal->first(function ($jadwal) use ($tglCek) {
            $effective = Carbon::parse($jadwal['pivot']['effective_at']);
            $expired = $jadwal['pivot']['expired_at']
                ? Carbon::parse($jadwal['pivot']['expired_at'])
                : null;

            return $expired
                ? $tglCek->between($effective, $expired)
                : $tglCek->greaterThanOrEqualTo($effective);
        });
        return $return;
    }

    public static function getAkumulasi(
        $dateInMonth,
        $logAttendances,
        $schedules,
        $izin,
        $lembur,
        $tglMerah,
        $data_attendance_claims
    ) {
        $param['dateInMonth'] = $dateInMonth;
        $param['tglMerah'] = $tglMerah;
        $param['izin'] = $izin;
        $param['lembur'] = $lembur;
        $param['log'] = $logAttendances;
        $param['jadwal'] = $schedules;
        $param['data_attendance_claims'] = $data_attendance_claims;

        $result = self::getDtAbsen($param);

        $akumulasi['hari_bulan'] = count($result);

        $akumulasi['off'] = collect($result)->filter(function ($item) {
            return in_array($item['status'] ?? '', ['off']);
        })->count();

        $akumulasi['merah'] = collect($result)->filter(function ($item) {
            return in_array($item['status'] ?? '', ['tgl merah']);
        })->count();

        $akumulasi['hari_kerja'] = $akumulasi['hari_bulan'] - $akumulasi['off'] - $akumulasi['merah'];

        $akumulasi['hadir'] = collect($result)->filter(function ($item) {
            return in_array($item['status'] ?? '', ['hadir']);
        })->count();
        $akumulasi['dtg_ontime'] = collect($result)->filter(function ($item) {
            return in_array($item['label_in'] ?? '', ['dtg ontime']);
        })->count();
        $akumulasi['plg_ontime'] = collect($result)->filter(function ($item) {
            return in_array($item['label_out'] ?? '', ['plg ontime']);
        })->count();

        $akumulasi['alpa'] = collect($result)->filter(function ($item) {
            return in_array($item['label_in'] ?? '', ['alpha', 'Sakit', 'Keluar Urusan Pribadi', 'Pulang']) ||
                in_array($item['label_out'] ?? '', ['Sakit', 'Keluar Urusan Pribadi', 'Pulang']);
        })->count();

        $akumulasi['tdk_absen'] = collect($result)->filter(function ($item) {
            return ($item['label_in'] ?? '') === 'tdk absen' || ($item['label_out'] ?? '') === 'tdk absen';
        })->count();
        $akumulasi['tdk_absen_dtg'] = collect($result)->filter(function ($item) {
            return ($item['label_in'] ?? '') === 'tdk absen';
        })->count();
        $akumulasi['tdk_absen_plg'] = collect($result)->filter(function ($item) {
            return ($item['label_out'] ?? '') === 'tdk absen';
        })->count();

        $akumulasi['terlambat'] = collect($result)->filter(function ($item) {
            return in_array($item['label_in'] ?? '', ['terlambat']) &&
                !in_array($item['label_out'] ?? '', ['plg cepat']);
        })->count();
        $akumulasi['plg_cepat'] = collect($result)->filter(function ($item) {
            return in_array($item['label_out'] ?? '', ['plg cepat']) &&
                !in_array($item['label_in'] ?? '', ['terlambat']);
        })->count();
        $akumulasi['terlambat_plgcepat'] = collect($result)->filter(function ($item) {
            return in_array($item['label_in'] ?? '', ['terlambat']) &&
                in_array($item['label_out'] ?? '', ['plg cepat']);
        })->count();

        $akumulasi['izin']['izin_sakit'] = collect($result)->filter(function ($item) {
            return in_array($item['label_in'] ?? '', ['Sakit']) ||
                in_array($item['label_out'] ?? '', ['Sakit']);
        })->count();
        $akumulasi['izin']['izin_keluar_pribadi'] = collect($result)->filter(function ($item) {
            return in_array($item['label_in'] ?? '', ['Keluar Urusan Pribadi']) ||
                in_array($item['label_out'] ?? '', ['Keluar Urusan Pribadi']);
        })->count();
        $akumulasi['izin']['izin_pulang'] = collect($result)->filter(function ($item) {
            return in_array($item['label_in'] ?? '', ['Pulang']) ||
                in_array($item['label_out'] ?? '', ['Pulang']);
        })->count();

        $akumulasi['izin']['izin_dinas'] = collect($result)->filter(function ($item) {
            return in_array($item['label_in'] ?? '', ['Dinas']) ||
                in_array($item['label_out'] ?? '', ['Dinas']);
        })->count();
        $akumulasi['izin']['izin_keluar_kerja'] = collect($result)->filter(function ($item) {
            return in_array($item['label_in'] ?? '', ['Keluar Urusan Kerja']) ||
                in_array($item['label_out'] ?? '', ['Keluar Urusan Kerja']);
        })->count();
        $akumulasi['izin']['izin_cuti'] = collect($result)->filter(function ($item) {
            return in_array($item['label_in'] ?? '', ['Cuti']) ||
                in_array($item['label_out'] ?? '', ['Cuti']);
        })->count();


        $akumulasi['time_detail'] = collect($result)->reduce(function ($carry, $item) {
            $carry['total_dtg_cpt']  += PublicHelper::toSeconds($item['time_dtg_cpt']  ?? '00:00:00');
            $carry['total_dtg_lama'] += PublicHelper::toSeconds($item['time_dtg_lama'] ?? '00:00:00');
            $carry['total_plg_cpt']  += PublicHelper::toSeconds($item['time_plg_cpt']  ?? '00:00:00');
            $carry['total_plg_lama'] += PublicHelper::toSeconds($item['time_plg_lama'] ?? '00:00:00');
            return $carry;
        }, [
            'total_dtg_cpt'  => 0,
            'total_dtg_lama' => 0,
            'total_plg_cpt'  => 0,
            'total_plg_lama' => 0,
        ]);

        $akumulasi['time_detail']['loyal_time'] =
            ($akumulasi['time_detail']['total_dtg_cpt'] + $akumulasi['time_detail']['total_plg_lama']) -
            ($akumulasi['time_detail']['total_dtg_lama'] + $akumulasi['time_detail']['total_plg_cpt']);

        $akumulasi['time_detail']['total_dtg_cpt_read'] = abs($akumulasi['time_detail']['total_dtg_cpt'] / 3600);
        $akumulasi['time_detail']['total_dtg_lama_read'] = abs($akumulasi['time_detail']['total_dtg_lama'] / 3600);
        $akumulasi['time_detail']['total_plg_cpt_read'] = abs($akumulasi['time_detail']['total_plg_cpt'] / 3600);
        $akumulasi['time_detail']['total_plg_lama_read'] = abs($akumulasi['time_detail']['total_plg_lama'] / 3600);
        $akumulasi['time_detail']['loyal_time_read'] =
            ($akumulasi['time_detail']['loyal_time'] >= 0 ? 1 : -1) *
            abs($akumulasi['time_detail']['loyal_time'] / 3600);


        if ($akumulasi['alpa'] > 0) {
            $akumulasi['rank']['keterlambatan'] = 0;
        } else {
            $hariKeterlambatan = $akumulasi['terlambat'];
            // $hariKeterlambatan = $akumulasi['terlambat'] + $akumulasi['tdk_absen'];
            $akumulasi['rank']['keterlambatan'] = 50 * (1 - (PublicHelper::safeDivide($hariKeterlambatan, $akumulasi['hari_kerja'])));
        }

        // dd($akumulasi, $hariKeterlambatan,$akumulasi['terlambat'],$akumulasi['tdk_absen']);

        $hariIzin = $akumulasi['izin']['izin_sakit'] + $akumulasi['izin']['izin_pulang'];
        $akumulasi['rank']['izin'] = 30 * (1 - (PublicHelper::safeDivide($hariIzin, $akumulasi['hari_kerja'])));

        $hariKeluar = $akumulasi['izin']['izin_keluar_pribadi'];
        $akumulasi['rank']['keluar'] = 20 * (1 - (PublicHelper::safeDivide($hariKeluar, $akumulasi['hari_kerja'])));

        $akumulasi['rank']['total_poin'] =
            $akumulasi['rank']['keterlambatan'] +
            $akumulasi['rank']['izin'] +
            $akumulasi['rank']['keluar'];


        $akumulasi['insight']['dtg_ontime'] = collect($result)->filter(function ($item) {
            return in_array($item['label_in'] ?? '', ['dtg ontime']);
        })->count();
        // dd($akumulasi);
        return $akumulasi;
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

    public static function safeDivide($a, $b, $default = 0)
    {
        return $b == 0 ? $default : $a / $b;
    }
}
