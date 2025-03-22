<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function formLogin()
    {
        $data['tab_title'] = "SIADM";
        $data['page_title'] = "Dasbor";
        $data['page_desc'] = "Ringkasan data saat ini";
        $data['lw'] = "auth.form-login";

        return view('auth.index', compact('data'));
    }
}
