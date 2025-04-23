<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\MasterOrganizationFace;
use Illuminate\Http\Request;
use DataTables;

class OrganizationController extends Controller
{
    public function index()
    {
        $data['tab_title'] = "Data Perusahaan | ".config('app.name');
        $data['page_title'] = "Data Perusahaan";
        $data['page_desc'] = "Manajemen data organization.";
        $data['lw'] = "organization.organization-data";
        return view('organization.index', compact('data'));
    }

    public function indexDT(MasterOrganizationFace $masterOrganizationRepo)
    {
        $data = $masterOrganizationRepo->getDT(0);

        return DataTables::of($data)
            ->addColumn('action', function($data){
                $return = '
                <div class="btn-group">
                    <a href="javascript:void(0)" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                        <i class="mdi mdi-dots-vertical"></i>
                    </a>
                    <div class="dropdown-menu" style="">
                ';

                $return .= '
                    <a data-id="'.$data->id.'" data-toggle="modal" data-target="#modal-edit" class="dropdown-item" href="#"><i class="fas fa-edit fa-fw"></i> Ubah Data</a>
                ';

                if($data->data_employees->isEmpty()){
                    $dtJson['msg'] = 'Apakah anda yakin menghapus data '.$data->name. ' ?';
                    $dtJson['id'] = $data->id;
                    $dtJson = json_encode($dtJson);
                    $return .= '
                        <a data-json=\''.$dtJson.'\' class="dropdown-item text-danger" data-toggle="modal" data-target="#modal-delete" href="javascript:void(0);"><i class="fas fa-trash-alt fa-fw"></i> Hapus Data</a>
                    ';
                }
                return $return;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
