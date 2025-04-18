<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\AuthInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authRepository;
    public function __construct(AuthInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function formLogin()
    {
        $data['tab_title'] = "Login | ".config('app.name');
        $data['page_title'] = "Login";
        $data['page_desc'] = "Sign in to continue to application.";
        $data['lw'] = "auth.form-login";

        return view('auth.index', compact('data'));
    }

    public function logout()
    {
        if($this->authRepository->logout()){
            return redirect()->route('anchor');
        }
    }
}
