<?php

namespace App\Livewire\Permission;

use Livewire\Component;
use Spatie\Permission\Models\Permission;

class Index extends Component
{
    public $name;
    public $guard_name; 

    public function render()
    {
        $permissions = Permission::paginate(10); 
        return view('livewire.permission.index', compact('permissions'));
    }

    public function create()
    {
        // Validasi data
        $this->validate([
            'name' => 'required|string|max:255',
            'guard_name' => 'required|string|max:255',
        ]);

        // Buat permission
        Permission::create([
            'name' => $this->name,
            'guard_name' => $this->guard_name,
        ]);

        // Reset input
        $this->name = '';
        $this->guard_name;
        
    }
}
