<?php

namespace App\Livewire\Permission;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermission extends Component
{

    use WithPagination;
    
    public $roles, $permissions, $guard_name;
    public $role_name, $role_id;
    public $modal_title, $mode;
    public $sortBy = 'name'; 
    public $sortDirection = 'asc'; 
    public $search = '';
    public $selectedPermissions = [];


    public function mount()
    {
        $this->loadRoles();
        $this->permissions = Permission::all();
    }

    public function loadRoles()
    {
        // Memastikan bahwa roles selalu diurutkan berdasarkan pencarian, sort, dan paginate.
        $this->roles = Role::query()
            ->where('name', 'like', '%' . $this->search . '%') 
            ->orderBy($this->sortBy, $this->sortDirection)
            ->get();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->modal_title = 'Tambah Role';
        $this->mode = 'create';
        $this->dispatch('openModal');
    }

    public function store()
    {
        $this->validate([
            'role_name' => 'required|unique:roles,name',
            'guard_name' => 'required|string|min:3|max:255',
        ]);

        // Membuat role
        $role = Role::create([
            'name' => $this->role_name,
            'guard_name' => $this->guard_name
        ]);

        // Menambahkan permissions ke role jika ada
        if (!empty($this->selectedPermissions)) {
            $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();

            if ($permissions->count() === count($this->selectedPermissions)) {
                $role->givePermissionTo($permissions);
            } else {
                session()->flash('error', 'One or more permissions do not exist.');
            }
        }

        session()->flash('message', 'Role created successfully.');
        $this->loadRoles(); 
        $this->dispatch('close-modal');
    }

    public function edit($id)
    {
        $this->modal_title = 'Edit Role';
        $this->mode = 'edit';
        $this->role_id = $id;

        $role = Role::findOrFail($id);
        $this->role_name = $role->name;
        $this->guard_name = $role->guard_name;
        $this->selectedPermissions = $role->permissions->pluck('id')->toArray();

        $this->dispatch('openModal');
    }

    public function update()
    {
        $this->validate([
            'role_name' => 'required|unique:roles,name,' . $this->role_id,
            'guard_name' => 'required|string|min:3|max:255',
        ]);

        // Menemukan role berdasarkan ID
        $role = Role::findOrFail($this->role_id);

        // Update nama role dan guard_name
        $role->update([
            'name' => $this->role_name,
            'guard_name' => $this->guard_name
        ]);

        // Sync permissions
        if (!empty($this->selectedPermissions)) {
            $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();

            if ($permissions->count() === count($this->selectedPermissions)) {
                $role->syncPermissions($permissions);
            } else {
                session()->flash('error', 'One or more permissions do not exist.');
            }
        } else {
            $role->syncPermissions([]);
        }

        session()->flash('message', 'Role updated successfully.');
        $this->loadRoles();
        $this->dispatch('close-modal');
    }

    public function confirmDelete($id)
    {

        $role = Role::find($id);
        $this->role_id = $id;
        $this->role_name = $role ? $role->name : '';
        
        $this->dispatch('openDeleteModal');
    }

    public function destroy()
    {
        $role = Role::findOrFail($this->role_id);
        $role->delete();

        session()->flash('message', 'Role deleted successfully.');
        $this->loadRoles();
        $this->dispatch('close-modal');
    }

    public function resetInputFields()
    {
        $this->role_name = '';
        $this->guard_name = '';
        $this->role_id = null;
        $this->selectedPermissions = [];
    }

    public function sort($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }

        $this->loadRoles(); 
    }

    public function updatedSearch()
    {
        $this->loadRoles(); 
    }

    public function render()
    {
        // // Membatasi jumlah roles per halaman dengan pagination
        // $roles = Role::query()
        //     ->where('name', 'like', '%' . $this->search . '%')
        //     ->orderBy($this->sortBy, $this->sortDirection)
        //     ->paginate(5);

        return view('livewire.permission.role-permission', [
            'roles' => $this->roles,
            'permissions' => $this->permissions,  
        ]);
    }
}
