<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $data['tab_title'] = "Data Akun | ".config('app.name');
        $data['page_title'] = "Data Akun";
        $data['page_desc'] = "Semua data akun.";
        $data['lw'] = "user.user-data";
        return view('user.index', compact('data'));
    }
}
