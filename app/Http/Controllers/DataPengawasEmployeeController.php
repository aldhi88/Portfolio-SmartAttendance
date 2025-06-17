<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\DataEmployeeFace;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataPengawasEmployeeController extends Controller
{
    public function index()
    {
        $data['tab_title'] = "Data Pengawas | " . config('app.name');
        $data['page_title'] = "Data Pengawas";
        $data['page_desc'] = "Manajemen Anggota Pengawas";
        $data['lw'] = "pengawas.pengawas-data";
        return view('index', compact('data'));
    }

    public function indexDTEmployee(DataEmployeeFace $dataEmployeeRepo, Request $request)
    {
        $data['id'] = $request->pengawas;
        $data = $dataEmployeeRepo->getNonMember($data);
        // dd($data->get()->toArray());
        return DataTables::of($data)
            ->filterColumn('name', function ($query, $keyword) {
                $query->where('data_employees.name', 'like', "%$keyword%");
            })
            ->addColumn('role_name', function ($row) {
                return optional(optional($row->user_logins)->user_roles)->name ?? '-';
            })
            ->smart(false)
            ->toJson()
        ;
    }
    public function indexDTMember(DataEmployeeFace $dataEmployeeRepo, Request $request)
    {
        $data['id'] = $request->pengawas;
        $data = $dataEmployeeRepo->getMember($data);
        // dd($data->get()->toArray());
        return DataTables::of($data)
            ->filterColumn('name', function ($query, $keyword) {
                $query->where('data_employees.name', 'like', "%$keyword%");
            })
            ->addColumn('role_name', function ($row) {
                return optional(optional($row->user_logins)->user_roles)->name ?? '-';
            })
            ->smart(false)
            ->toJson()
        ;
    }
}
