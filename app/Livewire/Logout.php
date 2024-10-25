<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Logout extends Component
{

    public function Logout()
    {
        Auth::Logout();
        return redirect('/login');
    }
    public function render()
    {
        return view('livewire.logout');
    }
}
