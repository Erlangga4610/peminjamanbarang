<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Login extends Component
{
    #[Layout('components.layouts.auth')]

    public $email;
    public $password;

    // Validation rules for the login process
    protected $rules = [
        'email' => 'required|email',  // No need for 'unique' here for login
        'password' => 'required|string|min:8', // Removed the 'confirmed' rule
    ];

    public function render()
    {
        return view('livewire.login');
    }

    public function login()
    {
        //validasi 
        $this->validate();

        if (Auth::attempt([
            'email' => $this->email,
            'password' => $this->password
        ])) {
            return $this->redirect('/dashboard');
        }

        session()->flash('error', 'Login Gagal');


        return $this->redirect('/login');
    }
}
