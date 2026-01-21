<?php

namespace App\Http\Controllers;

use App\Repositories\DataLiburIzinRepo;
use App\Repositories\Interfaces\DataLiburIzinFace;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;

class DataLiburController extends Controller
{
    public function indexMerah()
    {
        $data['tab_title'] = "Data Tanggal Merah | ".config('app.name');
        $data['page_title'] = "Data Tanggal Merah";
        $data['page_desc'] = "Manajemen data tanggal merah";
        $data['lw'] = "libur.tgl-merah";
        return view('index', compact('data'));
    }
    public function indexIzin()
    {
        $data['tab_title'] = "Data Izin Libur | ".config('app.name');
        $data['page_title'] = "Data Izin Libur";
        $data['page_desc'] = "Manajemen data izin libur karyawan";
        $data['lw'] = "libur.data-izin";
        return view('index', compact('data'));
    }
    public function indexIzinDT(DataLiburIzinFace $dataLiburIzinRepo)
    {
        if(Auth::user()->is_pengawas){
            $data = $dataLiburIzinRepo->getDataIzinByPengawasDT(0);
        }else{
            $data = $dataLiburIzinRepo->getDataIzinDT(0);
        }

        return DataTables::of($data)
            ->toJson();
    }
    public function izinCreate()
    {
        $data['tab_title'] = "Form Izin Libur Karyawan | ".config('app.name');
        $data['page_title'] = "Form Izin Libur Karyawan";
        $data['page_desc'] = "Menambah data izin libur karyawan";
        $data['lw'] = "libur.create-izin";
        return view('index', compact('data'));
    }

    public function passingGradeCuti()
    {
        $data['tab_title'] = "Passing Grade Cuti | " . config('app.name');
        $data['page_title'] = "Passing Grade Cuti";
        $data['page_desc'] = "Data passing grade cuti";
        $data['lw'] = "libur.libur-cuti-passing-grade";
        return view('index', compact('data'));
    }

    public function passingGradeCutiDT(
        Request $request
    ) {

        $month = (int) $request->month;
        $year  = (int) $request->year;
        $org   = (int) $request->org;

        $data = DataLiburIzinRepo::getPassingGradeCuti();

        if ($org !== 0) {
            $data->where('master_organization_id', $org);
        }

        $filter = function ($query) use ($month, $year) {
            $query->whereMonth('from', $month)
                ->whereYear('from', $year)
                ->where('jenis','Cuti')
                ->where('status','Disetujui')
                ;
        };

        $data->whereHas('data_izins', $filter)
            ->withCount([
                'data_izins as total_hari_cuti' => $filter,
            ])
            ->with([
                'data_izins' => $filter,
            ])
        ;

        return DataTables::of($data)
            ->toJson();
    }

    public function izinEdit($id)
    {
        $data['id'] = $id;
        $data['tab_title'] = "Form Edit Izin Libur Karyawan | ".config('app.name');
        $data['page_title'] = "Form Edit Izin Libur Karyawan";
        $data['page_desc'] = "Edit data izin libur karyawan";
        $data['lw'] = "libur.edit-izin";
        return view('index', compact('data'));
    }


}
