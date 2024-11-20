<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Employee as Employe;
use Illuminate\Support\Facades\Storage;
use App\Models\Division;


class Employee extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $nik, $name, $gender, $birth_place, $address, $status, $contact, $division_id;
    public $image, $lastImage;
    public $mode = '';
    public $modal_title = '';
    public $employeId;
    public $search = '';
    protected $paginationTheme = 'bootstrap';
    public $sortBy = 'nik'; //kolom default untuk sorting
    public $sortDirection = 'asc';

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
        $this->division_id = '';
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
            'division_id' => 'required|exists:divisions,id'            
        ]);

        try {
            $imagePath = $this->image ? $this->image->storeAs('photos', $this->image->hashName(), 'public') : null;

            Employe::create([
                'image' => $imagePath,
                'nik' => $this->nik,
                'name' => $this->name,
                'gender' => $this->gender,
                'birth_place' => $this->birth_place,
                'address' => $this->address,
                'contact' => $this->contact,
                'status' => $this->status,
                'division_id' => $this->division_id,
            ]);

            session()->flash('message', 'Data Berhasil DiSimpan');
            $this->dispatch('close-modal');
        } catch (\Throwable $th) {
            session()->flash('message', 'Terjadi Kesalahan: ' . $th->getMessage());
        }
    }

    public function edit($employeId)
    {
        $this->mode = 'edit';
        $this->modal_title = 'Edit Karyawan';
        $this->dispatch('openModal');
        $this->resetInputFields();

        $employee = Employe::findOrFail($employeId);
        $this->employeId = $employee->id;
        $this->nik = $employee->nik;
        $this->name = $employee->name;
        $this->gender = $employee->gender;
        $this->birth_place = $employee->birth_place;
        $this->address = $employee->address;
        $this->status = $employee->status;
        $this->contact = $employee->contact;
        $this->lastImage = $employee->image;
        $this->division_id = $employee->division_id;
    }

    public function update()
    {
        $this->validate([
            'nik' => 'required|unique:employees,nik,' . $this->employeId . '|max:50|min:3',
            'name' => 'required|string|max:255|min:3',
            'gender' => 'required|in:1,2',
            'birth_place' => 'required|string|max:255',
            'address' => 'required|string',
            'status' => 'required|boolean',
            'contact' => 'required|string|min:3',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'division_id' => 'required|exists:divisions,id',
        ]);

        try {
            $employee = Employe::findOrFail($this->employeId);

            $imagePath = $employee->image;

            if ($this->image) {
                $imageNewPath = $this->image->storeAs('photos', $this->image->hashName(), 'public');
                if ($imagePath && $imagePath !== $imageNewPath) {
                    Storage::disk('public')->delete($imagePath);
                    $imagePath = $imageNewPath;
                }
            }

            $employee->update([
                'nik' => $this->nik,
                'name' => $this->name,
                'gender' => $this->gender,
                'birth_place' => $this->birth_place,
                'address' => $this->address,
                'status' => $this->status,
                'contact' => $this->contact,
                'image' => $imagePath,
                'division_id' => $this->division_id,
            ]);

            session()->flash('message', 'Data Karyawan Berhasil Diupdate');
            $this->resetInputFields();
            $this->dispatch('close-modal');
        } catch (\Throwable $th) {
            session()->flash('message', 'Terjadi Kesalahan: ' . $th->getMessage());
        }
    }

    public function confirmDelete($employeId)
    {
        $this->employeId = $employeId;
        $this->dispatch('openDeleteModal');
    }

    public function destroy()
    {
        $employee = Employe::findOrFail($this->employeId);
        Storage::disk('public')->delete($employee->image);
        $employee->delete();

        session()->flash('message', 'Data Berhasil Dihapus');
        $this->resetInputFields();
        $this->dispatch('close-modal');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sort($column)
    {
        if ($this->sortBy === $column){
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $employee = Employe::query()
            ->with('division')
            ->when($this->search, function ($query) {
                $query->where('nik', 'like', '%' . $this->search . '%')
                    ->orWhere('name', 'like', '%' . $this->search . '%')
                    ->orWhere('address', 'like', '%' . $this->search . '%')
                    ->orWhere('contact', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(5);
            

        $divisions = Division::all();

        return view('livewire.employees.employee', [
            'employee' => $employee,
            'divisions' => $divisions,
        ]);
    }
}
