<?php

namespace App\Http\Controllers;

use App\Helpers\PublicHelper;
use App\Repositories\Interfaces\DataEmployeeFace;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function absen()
    {
        $data['tab_title'] = "Rekap Absensi | " . config('app.name');
        $data['page_title'] = "Rekap Absensi";
        $data['page_desc'] = "Menamplikan rekap absensi.";
        $data['lw'] = "report.report-absen";
        return view('index', compact('data'));
    }

    public function absenDT(DataEmployeeFace $dataEmployeeRepo, Request $request)
    {
        $data = $dataEmployeeRepo->getReportDT(0);
        $data->where('status', 'Aktif')
            ->with([
                'master_schedules:id,checkin_time,work_time,checkin_deadline_time,checkout_time,day_work',
                'log_attendances' => function ($q) use ($request) {
                    $q->select('id', 'data_employee_id', 'time')
                    ->whereYear('time', $request->filter_year)
                    ->whereMonth('time', $request->filter_month);
                }
            ])
            ->whereHas('log_attendances', function ($q) use ($request) {
                $q->whereYear('time', $request->filter_year)
                    ->whereMonth('time', $request->filter_month);
            })
        ;

        // $dateList = $this->dateInMonth($request->filter_month, $request->filter_year);
        // dd($dateList);
        // dd($data->get()->toArray());
        return DataTables::of($data)
            ->filterColumn('name', function ($query, $keyword) {
                $query->where('name', 'like', "%$keyword%");
            })
            // ->addColumn('absensi', function () use($dateList) {
            //     foreach ($dateList as $key => $value) {
            //         $return[$value]['in'] = '10:00';
            //         $return[$value]['out'] = '11:00';
            //     }
            //     // dd($return);
            //     return $return;
            // })
            ->smart(false)
            ->toJson()
        ;
    }

    public function dateInMonth($month, $year)
    {
        $today = Carbon::now();
        $paramDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $start = $paramDate->copy();
        $end = $paramDate->copy()->endOfMonth();

        if ($month == $today->month && $year == $today->year) {
            $end = $today->copy()->startOfDay();
        }

        $dates = collect();

        while ($start->lte($end)) {
            $dates->push($start->format('d-M-Y'));
            $start->addDay();
        }

        return $dates->toArray();
    }
}

