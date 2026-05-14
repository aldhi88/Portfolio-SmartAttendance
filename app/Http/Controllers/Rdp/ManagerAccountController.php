<?php

namespace App\Http\Controllers\Rdp;

use App\Http\Controllers\Controller;
use App\Repositories\RdpManagerAccountRepo;
use DataTables;

class ManagerAccountController extends Controller
{
    public function index()
    {
        $data['tab_title'] = "Akun Manager | RDP";
        $data['page_title'] = "Akun Manager";
        $data['page_desc'] = "Manajemen akun Manager HC Region dan Manager Aset Region.";
        $data['lw'] = "rdp.manager-account.manager-account-data";

        return view('rdp.index', compact('data'));
    }

    public function indexDT()
    {
        return DataTables::of(RdpManagerAccountRepo::getDT())
            ->filterColumn('print_role_name', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('print_role_name', 'like', "%$keyword%")
                        ->orWhereHas('user_roles', function ($role) use ($keyword) {
                            $role->where('name', 'like', "%$keyword%");
                        });
                });
            })
            ->toJson();
    }

    public function profile()
    {
        $data['tab_title'] = "Akun Manager | RDP";
        $data['page_title'] = "Akun Manager";
        $data['page_desc'] = "Kelola data login dan tanda tangan manager.";
        $data['lw'] = "rdp.manager-account.manager-account-profile";

        return view('rdp.index', compact('data'));
    }
}
