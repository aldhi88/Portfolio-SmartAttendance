<?php

namespace App\Http\Controllers;

use App\Helpers\PublicHelper;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\DataLiburFace;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data['tab_title'] = "Dashboard | " . config('app.name');
        $data['page_title'] = "Dashboard";
        $data['page_desc'] = "Quick summary data.";
        $data['lw'] = "dashboard.insight";

        return view('dashboard.index', compact('data'));
    }

    public function getSummary(
        DataEmployeeFace $dataEmployeeRepo,
        DataLiburFace $dataLiburRepo,
    ) {
        $data = $dataEmployeeRepo->getReportDT(0);
        $data->select([
            'data_employees.id',
            'data_employees.name',
            'data_employees.status',
            'data_employees.master_organization_id',
            'data_employees.master_position_id',
            'data_employees.master_location_id',
            'data_employees.master_function_id',
        ])
            ->where('status', 'Aktif')
            ->has('master_schedules')
            ->with([
                'master_schedules:id,type,kode,checkin_time,work_time,checkin_deadline_time,checkout_time,day_work',
                'log_attendances' => function ($q) {
                    $q->select('data_employee_id', 'time')
                        ->whereYear('time', date('Y'))
                        ->whereMonth('time', date('m'));
                },
                'data_izins' => function ($q) {
                    $q->select('id', 'data_employee_id', 'jenis', 'from', 'to', 'desc')
                        ->where('status', 'Disetujui');
                },
            ])
        ;

        $dateInMonth = PublicHelper::dateInMonth(date('m'), date('Y'));
        $tglMerah = $dataLiburRepo->getByDate(date('m'), date('Y'));

        $results = $data->get()->map(function ($item) use ($dateInMonth, $tglMerah) {
            $item->akumulasi = PublicHelper::getAkumulasi(
                $dateInMonth,
                $item->log_attendances->toArray(),
                $item->master_schedules->toArray(),
                $item->data_izins->toArray(),
                $tglMerah
            );
            return $item;
        });

        return response()->json([
            'status' => 'success',
            'data' => $results,
        ]);

    }
}
