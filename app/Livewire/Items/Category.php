<?php

namespace App\Livewire\Items;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Categori;

class Category extends Component
{

    use WithPagination;
    public $name;
    public $modal_title = '';
    public $mode = '';
    public $kat_id;
    public $search = '';
    protected $paginationTheme = 'bootstrap';
    public $sortBy = 'name'; //kolom default untuk sorting
    public $sortDirection = 'asc';

    public function create()
    {
        $this->mode  = 'create';
        $this->modal_title = 'Tambah kategori';
        $this->resetInputFields();
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:225|min:3',
        ]);

        //create
        Categori::create([
            'name' => $this->name,
        ]);

        session()->flash('message', 'Data Berhasil DiSimpan');
        $this->dispatch('close-modal');
    }

    public function edit($kat_id)
    {
        $this->mode = 'edit';
        $this->modal_title = 'Edit kategori';

        //menemukan by id
        $category = Categori::findOrFail($kat_id);

        $this->kat_id = $category->id;
        $this->name = $category->name;
        // $this->dispatch('close-modal')
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255|min:3',
        ]);

        //menemukan kategori
        $category = Categori::findOrFail($this->kat_id);

        //memperbarui dat
        $category->name = $this->name;
        $category->save();

        session()->flash('message', 'Data Berhasil DiUpdate');
        $this->resetInputFields();  
        $this->dispatch('close-modal');
    }

    public function confirmdelete($kat_id)
    {   
        $this->kat_id = $kat_id;
        $this->dispatch('close-modal');
    }

    public function destroy()
    {
        $category = Categori::findOrFail($this->kat_id);
        $category->delete();

        session()->flash('message', 'Data Berhasil Dihapus');
        $this->dispatch('close-modal');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetInputFields()
    {
        $this->name = '';
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
        $categories = Categori::when($this->search, function ($query) {
            return $query->where('name', 'like', '%' . $this->search . '%');
        })
        ->orderBy($this->sortBy, $this->sortDirection) 
        ->paginate(10); 

        return view('livewire.items.category', compact('categories'));
    }

}
