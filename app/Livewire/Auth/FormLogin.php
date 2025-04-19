<?php

namespace App\Livewire\Auth;

use App\Repositories\Interfaces\AuthInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class FormLogin extends Component
{
    public $dt = [];

    protected $authRepository;
    public function hydrate(AuthInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function formSubmit()
    {
        $validated = $this->validate();

        if ($this->authRepository->login($validated['dt'])) {
            return redirect()->route('anchor');
        }else{
            session()->flash('message', 'Data login tidak ditemukan');
        }

        // $user = $this->userLoginRepository->getByUsername($this->dt['username']);
        // if ($user && Hash::check($this->dt['password'], $user->password)) {
        //     Auth::loginUsingId($user->id);
        //     return redirect()->route('anchor');
        // }else{
        //     session()->flash('message', 'ID Login anda tidak ditemukan');
        // }

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
