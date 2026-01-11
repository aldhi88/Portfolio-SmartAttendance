<?php

namespace App\Http\Controllers;

use App\Helpers\ReportLemburHelper;
use App\Models\DataLembur;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\DataLemburFace;
use App\Repositories\LogGpsRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DataTables;

class DataLemburController extends Controller
{
    public function indexLembur()
    {
        $data['tab_title'] = "Data Lembur | " . config('app.name');
        $data['page_title'] = "Data Lembur";
        $data['page_desc'] = "Manajemen data lembur";
        $data['lw'] = "lembur.lembur-data";
        return view('index', compact('data'));
    }

    public function indexLemburDT(
        DataLemburFace $dataLemburRepo,
        Request $request
    ) {
        $data = $dataLemburRepo->getDataDT(0);
        if (Auth::user()->is_pengawas) {
            $data = $dataLemburRepo->getDataByPengawas(0);
        }

        if (isset($request->month)) {
            if ($request->month != '') {
                $data->whereMonth('tanggal', $request->month);
            }
        }
        if (isset($request->year)) {
            if ($request->year != '') {
                $data->whereYear('tanggal', $request->year);
            }
        }
        if (isset($request->master_organization_id)) {
            if ($request->master_organization_id != '') {
                $data->whereHas('data_employees', function ($q) use ($request) {
                    $q->where('master_organization_id', $request->master_organization_id);
                });
            }
        }

        // dd($data->get()->toArray());

        return DataTables::of($data)
            ->addColumn('laporan_lembur_checkin', function ($data) {
                return ReportLemburHelper::getLemburCheckin($data->toArray());
            })
            ->addColumn('laporan_lembur_checkout', function ($data) {
                return ReportLemburHelper::getLemburCheckout($data->toArray());
            })
            ->addColumn('format', function ($data) {
                return DataLembur::formatOrg($data->data_employees->master_organization_id);
            })
            ->addColumn('log_gps', function ($data) {
                $pass = [
                    'employeeId' => $data->data_employee_id,
                    'start' => $data->checkin_time_lembur,
                    'end' => $data->checkout_deadline_time_lembur,
                ];
                return LogGpsRepo::getLogByEmployeeId($pass);
            })
            ->toJson();
    }
    public function lemburCreate()
    {
        $data['tab_title'] = "Form Data Lembur | " . config('app.name');
        $data['page_title'] = "Form Data Lembur";
        $data['page_desc'] = "Menambah data izin libur karyawan";
        $data['lw'] = "lembur.data-lembur-create";
        return view('index', compact('data'));
    }

    public function lemburEdit($id)
    {
        $data['id'] = $id;
        $data['tab_title'] = "Form Edit Data Lembur | " . config('app.name');
        $data['page_title'] = "Form Edit Data Lembur";
        $data['page_desc'] = "Edit data lembur karyawan";
        $data['lw'] = "lembur.data-lembur-edit";
        return view('index', compact('data'));
    }

    public function printPdf(
        $id,
        DataEmployeeFace $dataEmployeeRepo
    ) {

        $data = DataLembur::query()
            ->where('id', $id)
            ->with([
                'pengawas1',
                'pengawas1.master_positions:id,name',
                'pengawas2',
                'pengawas2.master_positions:id,name',
                'security',
                'security.master_positions:id,name',
                'data_employees',
                'data_employees.master_organizations:id,name',
                'data_employees.master_positions:id,name',
                'data_employees.master_locations:id,name',
            ])
            ->first()
            ->toArray();
        $data['pengawas1']['path_ttd'] = public_path('storage/employees/ttd/' . $data['pengawas1']['ttd']);
        $data['pengawas2']['path_ttd'] = public_path('storage/employees/ttd/' . $data['pengawas2']['ttd']);
        $data['data_lembur']['laporan_lembur_checkin'] = ReportLemburHelper::getLemburCheckin($data);
        $data['data_lembur']['laporan_lembur_checkout'] = ReportLemburHelper::getLemburCheckout($data);
        $data['data_lembur']['start_carbon'] = Carbon::parse($data['data_lembur']['laporan_lembur_checkin'])->locale('id');
        $data['data_lembur']['end_carbon']   = Carbon::parse($data['data_lembur']['laporan_lembur_checkout'])->locale('id');
        $totalMinutes = $data['data_lembur']['start_carbon']->diffInMinutes($data['data_lembur']['end_carbon']);
        $roundedMinutes = intdiv($totalMinutes, 30) * 30;
        $data['data_lembur']['hours'] = intdiv($roundedMinutes, 60);
        $data['data_lembur']['minutes'] = $roundedMinutes % 60;

        $employeeId = $data['data_employee_id'] ?? $data['data_employees']['id'];

        $bulanRef = Carbon::parse($data['tanggal']);
        $startMonth = $bulanRef->copy()->startOfMonth()->startOfDay();
        $endMonth   = $bulanRef->copy()->endOfMonth()->endOfDay();

        $monthlyMinutes = DataLembur::query()
            ->where('data_employee_id', $employeeId)
            ->whereBetween('tanggal', [
                $startMonth->toDateString(),
                $endMonth->toDateString()
            ])
            ->get()
            ->sum(function ($row) {

                $rowArray = $row->toArray();

                $checkin  = ReportLemburHelper::getLemburCheckin($rowArray);
                $checkout = ReportLemburHelper::getLemburCheckout($rowArray);

                if ($checkin === '-' || $checkout === '-') {
                    return 0;
                }

                $start = Carbon::parse($checkin);
                $end   = Carbon::parse($checkout);

                $totalMinutes = $start->diffInMinutes($end);

                return intdiv($totalMinutes, 30) * 30;
            });

        $data['data_lembur']['monthly_hours']   = intdiv($monthlyMinutes, 60);
        $data['data_lembur']['monthly_minutes'] = $monthlyMinutes % 60;

        // dd($data);

        $bladeView = DataLembur::formatOrg($data['data_employees']['master_organization_id']);
        $view = 'lembur.pdf.' . $bladeView;
        // dd($view);
        // $pdf = Pdf::loadView($view)
        //     ->setPaper('A4', 'portrait');
        $pdf = Pdf::loadView($view, compact('data'))
            ->setPaper('A4', 'portrait');

        return $pdf->download(uniqid().'.pdf');
        // return $pdf->stream(uniqid() . '.pdf');
    }
}
