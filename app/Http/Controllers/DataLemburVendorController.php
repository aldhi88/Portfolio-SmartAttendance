<?php

namespace App\Http\Controllers;

use App\Helpers\ReportLemburHelper;
use App\Models\DataLembur;
use App\Repositories\Interfaces\DataLemburFace;
use App\Repositories\MasterOrganizationRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;

class DataLemburVendorController extends Controller
{
    public function indexLembur()
    {
        $data['tab_title'] = "Data Lembur | ".config('app.name');
        $data['page_title'] = "Data Lembur";
        $data['page_desc'] = "Manajemen data lembur";
        $data['lw'] = "lembur-vendor.lembur-vendor-data";
        return view('index', compact('data'));
    }

    public function indexLemburDT(
        DataLemburFace $dataLemburRepo,
        Request $request
    )
    {
        $data = $dataLemburRepo->getDataDT(0);
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

        $orgId = Auth::user()->data_vendors->master_organization_id;
        $data->whereHas('data_employees', function($q) use ($orgId){
            $q->where('master_organization_id', $orgId);
        });

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
            ->toJson();
    }

    public function rekapBulanan()
    {
        $data['tab_title'] = "Rekap Lembur Bulanan | " . config('app.name');
        $data['page_title'] = "Rekap Lembur Bulanan";
        $data['page_desc'] = "Laporan data lembur bulanan";
        $data['lw'] = "lembur-vendor.lembur-vendor-rekap-bulanan";
        return view('index', compact('data'));
    }

    public function rekapBulananDT(Request $request)
    {
        $param = [
            'month' => $request->month,
            'year' => $request->year,
        ];
        $orgId = Auth::user()->data_vendors->master_organization_id;


        $data = MasterOrganizationRepo::getOrgLemburBulanan($param);
        $data->where('id', $orgId);
        return DataTables::of($data)
            ->smart(false)
            ->toJson();
    }
}
