<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\DataEmployeeFace;
use Illuminate\Http\Request;
use DataTables;

class DataEmployeeController extends Controller
{
    public function index()
    {
        $data['tab_title'] = "Data Karyawan | " . config('app.name');
        $data['page_title'] = "Data Karyawan";
        $data['page_desc'] = "Manajemen data karyawan";
        $data['lw'] = "employee.employee-data";
        return view('index', compact('data'));
    }

    public function indexDT(DataEmployeeFace $dataEmployeeRepo)
    {
        $data = $dataEmployeeRepo->getDTKaryawan(0);
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

    public function create()
    {
        $data['tab_title'] = "Karyawan Baru | " . config('app.name');
        $data['page_title'] = "Karyawan Baru";
        $data['page_desc'] = "Form menambah data karyawan";
        $data['lw'] = "employee.employee-create";
        return view('index', compact('data'));
    }

    public function edit($id)
    {
        $data['tab_title'] = "Edit Data Karyawan | " . config('app.name');
        $data['page_title'] = "Edit Data Karyawan";
        $data['page_desc'] = "Form edit data karyawan";
        $data['lw'] = "employee.employee-edit";
        $data['editId'] = $id;
        return view('index', compact('data'));
    }
}
