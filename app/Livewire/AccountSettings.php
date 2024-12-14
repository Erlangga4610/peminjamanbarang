<?php
namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AccountSettings extends Component
{
    public $name;
    public $email;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    public function updateSettings()
    {
        // Aturan validasi
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore(Auth::id())],
            'current_password' => 'required',
            'new_password' => 'nullable|min:8|confirmed',
        ];

        // Validasi input form
        $this->validate($rules);

        // Cek apakah password saat ini sesuai
        if (!Hash::check($this->current_password, Auth::user()->password)) {
            session()->flash('error', 'Password saat ini salah.');
            return;
        }

        // Mendapatkan pengguna yang terautentikasi
        $user = Auth::user();

        // Memeriksa apakah $user adalah instance dari model User
        if (!$user instanceof \App\Models\User) {
            throw new \Exception('User bukan instance dari model User.');
        }

        // Menyiapkan data untuk diupdate
        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        // Jika ada password baru, update password
        if ($this->new_password) {
            $data['password'] = Hash::make($this->new_password);
        }

        // Mengupdate data pengguna
        $user->update($data);
        
        // Reset semua field terkait password
        $this->resetInputFields();

        // Menampilkan pesan sukses
        session()->flash('message', 'Pengaturan akun berhasil diperbarui.');
    }

    public function resetInputFields()
    {
        // Reset semua field password
        $this->current_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';
    }


    public function render()
    {
        return view('livewire.account-settings');
    }
}
