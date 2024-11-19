<?php
namespace App\Livewire\Employees;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Division as Divisi;

class Division extends Component
{
    use WithPagination;

    public $name;
    public $mode = '';
    public $modal_title = '';
    public $div_id;
    public $search = '';
    protected $paginationTheme = 'bootstrap';
    public $sortBy = 'name'; //kolom default untuk sorting
    public $sortDirection = 'asc';

    public function resetInputFields()
    {
        $this->name = '';
    }

    public function create()
    {
        $this->mode  = 'create';
        $this->modal_title = 'Tambah Divisi';
        $this->resetInputFields();
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:225|min:3',
        ]);

        //create
        Divisi::create([
            'name' => $this->name,
        ]);

        session()->flash('message', 'Data Berhasil DiSimpan');
        $this->dispatch('close-modal');
    }

    public function edit($div_id)
    {
        $this->mode = 'edit';
        $this->modal_title = 'Edit kategori';

        //menemukan by id
        $divisi = Divisi::findOrFail($div_id);

        $this->div_id = $divisi->id;
        $this->name = $divisi->name;
        // $this->dispatch('close-modal')
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255|min:3',
        ]);

        //menemukan kategori
        $divisi = Divisi::findOrFail($this->div_id);

        //memperbarui dat
        $divisi->name = $this->name;
        $divisi->save();

        session()->flash('message', 'Data Berhasil DiUpdate');
        $this->resetInputFields();  
        $this->dispatch('close-modal');
    }

    public function confirmdelete($div_id)
    {   
        $this->div_id = $div_id;
        $this->dispatch('close-modal');
    }

    public function destroy()
    {
        $divisi = Divisi::findOrFail($this->div_id);
        $divisi->delete();

        session()->flash('message', 'Data Berhasil Dihapus');
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
        $divisions = Divisi::when($this->search, function ($query) {
            return $query->where('name', 'like', '%' . $this->search . '%');
        })
        ->orderBy($this->sortBy, $this->sortDirection)  // Mengurutkan berdasarkan kolom dan arah yang dipilih
        ->paginate(10);  // Menentukan jumlah data per halaman

        // Mengirimkan data ke view Livewire
        return view('livewire.employees.division', compact('divisions'));
    }


}
