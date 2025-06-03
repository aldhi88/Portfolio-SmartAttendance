<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\UserRoleFace;
use Illuminate\Http\Request;
use DataTables;

class SettingController extends Controller
{
    public function indexAuthorize()
    {
        $data['tab_title'] = "Data Authorization | ".config('app.name');
        $data['page_title'] = "Data Authorization";
        $data['page_desc'] = "Manajemen data hak akses";
        $data['lw'] = "setting.authorize-data";
        return view('index', compact('data'));
    }

    public function indexAuthorizeDT(UserRoleFace $userRoleRepo)
    {
        $data = $userRoleRepo->getDT(0);

        return DataTables::of($data)
            ->toJson();
    }
}
