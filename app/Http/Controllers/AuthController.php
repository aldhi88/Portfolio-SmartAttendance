<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function formLogin()
    {
        $data['tab_title'] = "Login | ".config('app.name');
        $data['page_title'] = "Login";
        $data['page_desc'] = "Form Login";
        $data['lw'] = "auth.form-login";

        return view('auth.index', compact('data'));
    }
}
