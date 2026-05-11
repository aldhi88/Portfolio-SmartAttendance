<?php

namespace App\Livewire\Auth;

use App\Repositories\Interfaces\AuthInterface;
use Livewire\Component;

class FormLogin extends Component
{
    public $dt = [];

    protected $authRepository;
    public function boot(AuthInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function formSubmit()
    {
        $validated = $this->validate();
        $cekLogin = $this->authRepository->login($validated['dt']);
        if($cekLogin==='not_active'){
            session()->flash('message', 'Status user tidak aktif');
            return;
        }

        if($cekLogin==='not_rdp_eligible'){
            session()->flash('message', 'Akses login karyawan tidak diizinkan.');
            return;
        }

        if($cekLogin){
            return redirect()->route('anchor');
        }

        session()->flash('message', 'Data login tidak ditemukan');
    }

    public $showPassword = false;
    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function rules()
    {
        return [
            "dt.username" => "required",
            "dt.password" => "required",
        ];
    }
    protected $validationAttributes = [
        "dt.username" => "Username",
        "dt.password" => "Password",
    ];


    public function render()
    {
        return view('auth.form_login');
    }
}
