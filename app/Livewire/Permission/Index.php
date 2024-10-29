<?php

namespace App\Livewire\Permission;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    #[Validate('required|min:3')] 
    public $name;
    #[Validate('required|min:3')] 
    public $guard_name;
    
    
    public $permissionId; //untuk menyimpan ID izin
    public $mode = "create";
    public $search = ''; //untuk search data
    public $modal_title = "Tambah Permission";
    protected $listeners = ['resetForm' => 'resetInputFields'];// Mendengarkan event 'resetForm' dan menjalankan metode 'resetInputFields' saat event dipicu.
    public $sortBy = 'name'; // Default sort column
    public $sortDirection = 'asc'; // Default sort direction

    public function create()
    {
        $this->resetInputFields();
        $this->mode = "create";
        $this->modal_title = "Tambah Permission";
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'guard_name' => 'required|string|max:255',
        ]);

        Permission::create([
            'name' => $this->name,
            'guard_name' => $this->guard_name,
        ]);

        session()->flash('message', 'Permission saved successfully.');

        $this->dispatch('close-modal');
    }

    public function edit($id)
    {
        $this->resetInputFields();
        $this->mode = "edit";
        $permission = Permission::findOrFail($id);
        $this->name = $permission->name;
        $this->guard_name = $permission->guard_name;
        $this->permissionId = $permission->id;
        $this->modal_title = "Edit Permission";
    }

    public function update()
    {
        $validatedData = $this->validate([
            'name' => 'required|string|min:3|max:255',
            'guard_name' => 'required|string|min:3|max:255',
        ]);

        // Periksa apakah ID izin telah diatur (menunjukkan izin mana yang akan diperbarui).
        if ($this->permissionId) {
            $permission = Permission::find($this->permissionId);
            // Jika izin tersebut ada, perbarui dengan data yang telah divalidasi.
            if ($permission) {
                $permission->update($validatedData);
                session()->flash('message', 'Permission updated successfully.');
                $this->dispatch('close-modal');
            }
        }
    }

    public function delete($id)
    {
        $permission = Permission::find($id);
        if ($permission) {
            $permission->delete();
            session()->flash('message', 'Permission deleted successfully.');
        } else {
            session()->flash('message', 'Permission not found.');
        }
        $this->dispatch('close-modal');
    }

    public function destroy(){
        // dd($this->permissionId);
        Permission::destroy($this->permissionId);

        session()->flash('message', 'Permission delete successfully.');
        $this->dispatch('close-modal');
    }


    public function resetInputFields()
    {
        $this->name = '';
        $this->guard_name = '';
        $this->permissionId = null;
        $this->modal_title = "Tambah Permission";
    }

    //update ketika sudah mencari maka akan ke refresh(balik awal)
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            // Jika sudah diurutkan berdasarkan kolom ini, ubah arah pengurutan
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Urutkan berdasarkan kolom baru
            $this->sortBy = $column;
            $this->sortDirection = 'asc'; //Rset Ke Urutan naik pada kolom baru
        }
    }

    public function render()
    {
        // membuat kueri dengan kondisi pencarian
        $permissions = Permission::where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('guard_name', 'like', '%' . $this->search . '%')
                                ->orderBy($this->sortBy, $this->sortDirection)
                                ->paginate(5);
    
        return view('livewire.permission.index', compact('permissions'));
    }
    
}