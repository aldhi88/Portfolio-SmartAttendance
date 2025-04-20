<?php

namespace App\Http\Controllers;

use App\Models\MasterOrganization;
use Illuminate\Http\Request;
use DataTables;

class PerusahaanController extends Controller
{
    public function index()
    {
        $data['tab_title'] = "Data Perusahaan | ".config('app.name');
        $data['page_title'] = "Data Perusahaan";
        $data['page_desc'] = "Semua data perusahaan.";
        $data['lw'] = "perusahaan.perusahaan-data";
        return view('perusahaan.index', compact('data'));
    }

    public function indexDt()
    {
        $data = MasterOrganization::query()
        ;

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
                    <a class="dropdown-item" href="#"><i class="fas fa-unlock-alt fa-fw"></i> Reset Password</a>
                ';
                // $return .= '
                //     <a class="dropdown-item" href="'.route('operator.resetPassword',$data->id).'"><i class="fas fa-unlock-alt fa-fw"></i> Reset Password</a>
                // ';

                // unset($dtJson);
                // $dtJson['msg'] = 'menghapus data Operator '.$data->username;
                // $dtJson['attr'] = $data->username;
                // $dtJson['id'] = $data->id;
                // $dtJson['callback'] = "operatordelete-delete";
                // $dtJson = json_encode($dtJson);
                // $return .= '<div class="dropdown-divider"></div>';
                // $return .= '
                //     <a class="dropdown-item text-danger" data-emit="modalconfirm-prepare" data-toggle="modal" data-target="#modalConfirm" href="javascript:void(0);" data-json=\''.$dtJson.'\'><i class="fas fa-trash-alt fa-fw"></i> Hapus</a>
                // ';

                // $return .='
                //     </div>
                // </div>
                // ';

                return $return;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
