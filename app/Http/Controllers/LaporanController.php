<?php

namespace App\Http\Controllers;

use App\Models\LogAttendance;
use Illuminate\Http\Request;
use DataTables;

class LaporanController extends Controller
{
    public function indexLogAbsen()
    {
        $data['tab_title'] = "Log Absensi | ".config('app.name');
        $data['page_title'] = "Log Absensi";
        $data['page_desc'] = "Data absen dari mesin absensi.";
        $data['lw'] = "laporan.log-absen-data";
        return view('laporan.index', compact('data'));
    }

    public function indexLogAbsenDt()
    {
        $data = LogAttendance::query()
            ->select(
                "log_attendances.*",
            )
            ->with([
                'master_machines',
                'master_machines.master_locations',
                'master_minors',
            ])
        ;
        // $data = User::query()
        //     ->select(
        //         "users.*",
        //         DB::raw("DATE_FORMAT(users.created_at, '%d/%m/%Y') as created_at_custom"),
        //     )
        //     ->where('role_id', 2)
        //     ->with([
        //         'sekolah',
        //     ])
        // ;

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
