<?php

namespace App\Livewire\Auth;

use App\Repositories\Interfaces\UserLoginInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class FormLogin extends Component
{
    public $dt = [];

    protected $userLoginRepository;
    public function hydrate(UserLoginInterface $userLoginRepository)
    {
        $this->userLoginRepository = $userLoginRepository;
    }

    public function formSubmit()
    {
        $this->validate();
        $user = $this->userLoginRepository->getByUsername($this->dt['username']);
        if ($user && Hash::check($this->dt['password'], $user->password)) {
            Auth::loginUsingId($user->id);
            return redirect()->route('anchor');
        }else{
            session()->flash('message', 'ID Login anda tidak ditemukan');
        }

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
