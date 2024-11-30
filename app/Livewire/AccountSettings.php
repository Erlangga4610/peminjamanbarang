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
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore(Auth::id())],
            'current_password' => 'required',
            'new_password' => 'nullable|min:8|confirmed',
        ];

        // Validate the form input
        $this->validate($rules);

        if (!Hash::check($this->current_password, Auth::user()->password)) {
            session()->flash('error', 'Current password is incorrect.');
            return;
        }

        $user = Auth::user();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->new_password) {
            $data['password'] = Hash::make($this->new_password);
        }

        // Update the user
        $user->update($data);

        // Flash success message
        session()->flash('message', 'Account settings updated successfully.');
    }

    public function render()
    {
        return view('livewire.account-settings');
    }
}
