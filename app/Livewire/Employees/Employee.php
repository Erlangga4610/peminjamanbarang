<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Employee as Employe; 
use Illuminate\Support\Facades\Storage;


class Employee extends Component
{

    use WithPagination;
    use WithFileUploads;

    public $nik, $name, $gender, $birth_place, $address, $status, $contact;
    public $image, $lastImage;
    public $mode = '';
    public $modal_title = '';
    public $employeId;
    public $search = '';
    protected $paginationTheme = 'bootstrap';


    public function resetInputFields()
    {
        $this->nik = '';
        $this->name = '';
        $this->gender = '';
        $this->birth_place = '';
        $this->address = '';
        $this->status = '';
        $this->contact = '';
        $this->image = '';
    }

    public function create()
    {
        $this->mode = 'create';
        $this->modal_title = 'Tambah Employee';
        $this->resetInputFields();
    }

    public function store()
    {
        $this->validate([
            'nik' => 'required|unique:employees,nik|max:50|min:3',
            'name' => 'required|string|max:255|min:3',
            'gender' => 'required|in:1,2',
            'birth_place' => 'required|string|max:255|',
            'address' => 'required|string|min:3',
            'status' => 'required|boolean',
            'contact' => 'required|string|min:4',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try{
            // Menyimpan file gambar dengan nama acak di dalam folder 'public/items'
            $imagePath = $this->image->storeAs('Photos', $this->image->hashName(), 'public');

            Employe::create([
                'image' => $imagePath,
                'nik' => $this->nik,
                'name' => $this->name,
                'gender' => $this->gender,
                'birth_place' => $this->birth_place,
                'address' => $this->address,
                'contact' => $this->contact,
                'status' => $this->status,
            ]);

            session()->flash('message', 'Data Berhasil DiSimpan');
            $this->dispatch('close-modal');
        } catch (\Throwable $th) {
            session()->flash('message', $th->getMessage());

        }
    }

    public function edit($employeId)
    {
        $this->mode = 'edit';
        $this->modal_title = 'Edit karyawan';
        $this->dispatch('openModal');

        // Ambil data employee berdasarkan ID
        $employee = Employe::findOrFail($employeId);
        
        // Set nilai input dengan data yang akan diedit
        $this->employeId = $employee->id;
        $this->nik = $employee->nik;
        $this->name = $employee->name;
        $this->gender = $employee->gender;
        $this->birth_place = $employee->birth_place;
        $this->address = $employee->address;
        $this->status = $employee->status;
        $this->contact = $employee->contact;
        $this->lastImage = $employee->image;  // Menyimpan path gambar lama
    }

    public function update()
    {
        // Validasi input
        $this->validate([
            'nik' => 'required|unique:employees,nik,' . $this->employeId . '|max:50|min:3', // Unique except the current employee
            'name' => 'required|string|max:255|min:3',
            'gender' => 'required|in:1,2',
            'birth_place' => 'required|string|max:255',
            'address' => 'required|string',
            'status' => 'required|boolean',
            'contact' => 'required|string|min:3',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            // Ambil data employee berdasarkan ID
            $employee = Employe::findOrFail($this->employeId);

            // Menyimpan gambar lama jika tidak ada gambar baru
            $imagePath = $employee->image;

            // Cek apakah ada gambar baru yang di-upload
            if ($this->image) {
                // Menyimpan gambar baru di folder 'photos' dengan nama acak
                $imageNewPath = $this->image->storeAs('photos', $this->image->hashName(), 'public');

                // Cek apakah gambar baru berbeda dengan gambar lama
                if ($imagePath && $imagePath !== $imageNewPath) {
                    // Hapus gambar lama dari penyimpanan jika ada gambar baru
                    Storage::disk('public')->delete($imagePath);
                    $imagePath = $imageNewPath;
                }
            }

            // Update data employee
            $employee->update([
                'nik' => $this->nik,
                'name' => $this->name,
                'gender' => $this->gender,
                'birth_place' => $this->birth_place,
                'address' => $this->address,
                'status' => $this->status,
                'contact' => $this->contact,
                'image' => $imagePath, // Update path gambar
            ]);

            // Menampilkan pesan berhasil
            session()->flash('message', 'Data Karyawan Berhasil Diupdate');

            // Reset input fields dan tutup modal
            $this->resetInputFields();
            $this->dispatch('close-modal');
            
        } catch (\Throwable $th) {
            // Menampilkan pesan error jika ada kesalahan
            session()->flash('message', 'Terjadi Kesalahan: ' . $th->getMessage());
        }
    }

    public function confirmDelete($employeId)
    {
        $this->$employeId = $employeId;
        $this->dispatch('openDeleteModal');
    }

    public function destroy()
    {
        $employee = Employe::findOrFail($this->employeId);
        Storage::disk('public')->delete($employee->image);
        $employee->delete();

        session()->flash('message', 'Data Berhasil Dihapus');
        $this->dispatch('close-modal');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function render()
    {
        // Ambil data employee berdasarkan pencarian
        $employee = Employe::where('nik', 'like', '%'.$this->search.'%')
                        ->orWhere('name', 'like', '%'.$this->search.'%')
                        ->orWhere('address', 'like', '%'.$this->search.'%')
                        ->orWhere('contact', 'like', '%'.$this->search.'%')
                        ->paginate(5);  
        return view('livewire.employees.employee', [
            'employee' => $employee
        ]);
    }

}
