<?php
namespace App\Livewire\Permission;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserRole extends Component
{
    public $users, $user_id, $role_id;
    public $user_name, $user_email;
    public $roles;
    public $isEdit = false;  // Untuk mode edit atau create
    public $selectedUserId = null;  // Menyimpan ID user yang akan dihapus role-nya

    protected $rules = [
        'role_id' => 'required',  // Validasi untuk role yang dipilih
    ];

    // Method untuk mengambil data user dan role
    public function mount()
    {
        $this->roles = Role::all();  // Mengambil semua role
        $this->users = User::all();  // Mengambil semua user
    }

    // Method untuk menyimpan role pada user
    public function store()
    {
        $this->validate();

        $user = User::find($this->user_id);
        $role = Role::find($this->role_id);

        if ($user && $role) {
            $user->assignRole($role);  // Menetapkan role ke user
            session()->flash('message', 'Role assigned to user successfully.');
            $this->resetFields();
            $this->users = User::all();  // Refresh data user
        }
    }

    // Method untuk mengedit role pada user
    public function edit($id)
    {
        $this->isEdit = true;

        $user = User::find($id);
        $this->user_id = $user->id;
        $this->user_name = $user->name;
        $this->user_email = $user->email;
        $this->role_id = $user->roles->first()->id ?? null;  // Ambil role pertama jika ada
    }

    // Method untuk mengupdate role pada user
    public function update()
    {
        $this->validate();

        $user = User::find($this->user_id);
        $role = Role::find($this->role_id);

        if ($user && $role) {
            $user->syncRoles([$role]);  // Update role dengan role yang baru
            session()->flash('message', 'User role updated successfully.');
            $this->resetFields();
            $this->users = User::all();  // Refresh data user
        }
    }

    // Method untuk menghapus role pada user
    public function confirmDeleteRole($userId)
    {
        $this->selectedUserId = $userId;
        $user = User::find($userId);
        $this->user_name = $user->name;  // Menyimpan nama user untuk konfirmasi
    }

    public function removeRoleFromUser()
    {
        $user = User::find($this->selectedUserId);
        if ($user) {
            $user->removeRole($user->roles->first());  // Menghapus role yang sudah diberikan
            session()->flash('message', 'Role removed from user successfully.');
            $this->users = User::all();  // Refresh data user
            $this->dispatch('close-modal');  // Emit event untuk menutup modal
        }
    }

    // Method untuk reset form
    public function resetFields()
    {
        $this->role_id = null;
        $this->user_id = null;
        $this->user_name = '';
        $this->user_email = '';
        $this->isEdit = false;
    }

    public function render()
    {
        return view('livewire.permission.user-role');
    }
}
