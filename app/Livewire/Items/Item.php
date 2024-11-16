<?php

namespace App\Livewire\Items;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Item as ItemModel;


class Item extends Component
{
    use WithPagination, WithFileUploads;

    public $image;
    public $name;
    public $jumlah;
    public $itemId;  // Untuk memperbarui atau mengedit item tertentu
    public $modal_title;
    public $mode = 'create'; // untuk mengelola mode create/edit

    // Reset input fields setelah create or update
    public function resetInputFields()
    {
        $this->image = null;
        $this->name = '';
        $this->jumlah = '';
    }

    public function create()
    {
        $this->resetInputFields();
        $this->mode = "create";
        $this->modal_title = "Tambah Barang";
        $this->dispatch('openModal');
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'image' => 'required|image|max:1024', // image validation
        ]);

        
        $imageName = $this->image->storeAs('public/items', $this->image->hashName());

        ItemModel::create([
            'image' => $imageName,
            'name' => $this->name,
            'jumlah' => $this->jumlah,
        ]);

        session()->flash('message', 'Data Berhasil DiSimpan');
        $this->dispatch('close-modal');
    }

    public function edit($itemId)
    {
        $this->mode = "edit";
        $this->modal_title = "Edit Barang";

        // memukan by id
        $item = ItemModel::findOrFail($itemId);

        $this->itemId = $item->id;
        $this->name = $item->name;
        $this->image = $item->imageName;
        $this->jumlah = $item->jumlah;
        $this->dispatch('openModal');
    }

    public function update()
    {
        // Validasi input
        $this->validate([
            'name' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'image' => 'nullable|image|max:1024', 
        ]);

        $item = ItemModel::findOrFail($this->itemId);

        // jika gambar baru maka perbarui
        if ($this->image) {
            $imageName = $this->image->storeAs('public/items', $this->image->hashName());
            $item->image = $imageName;
        }

        // Update the item details
        $item->name = $this->name;
        $item->jumlah = $this->jumlah;
        $item->save();

        session()->flash('message', 'Data Berhasil DiUpdate');
        $this->resetInputFields();  
    }

    public function delete($itemId)
    {
        $item = ItemModel::findOrFail($itemId);
        $item->delete();

        session()->flash('message', 'Data Berhasil Dihapus');
    }

    public function confirmDelete($itemId)
    {
        $this->itemId = $itemId;
    }


    public function render()
    {
        return view('livewire.items.item', [
            'items' => ItemModel::paginate(3)  
        ]);
    }
}
