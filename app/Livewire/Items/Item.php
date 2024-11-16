<?php
namespace App\Livewire\Items;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Item as ItemModel;
use Illuminate\Support\Facades\Storage;

class Item extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $image, $lastImage;
    public $name;
    public $jumlah;
    public $itemId;  // Untuk memperbarui atau mengedit item tertentu
    public $modal_title = '';
    public $mode = ''; // untuk mengelola mode create/edit
    public $search = '';

    // Reset input fields setelah create or update
    public function resetInputFields()
    {
        $this->image = '';
        $this->name = '';
        $this->jumlah = '';
    }

    public function create()
    {
        $this->mode = "create";
        $this->modal_title = "Tambah Barang";
        $this->resetInputFields();
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'image' => 'required|image|max:1024', // image validation
        ]);

        try {
            // dd($this->image->getClientOriginalExtension());
            // Menyimpan file gambar dengan nama acak di dalam folder 'public/items'
            $imagePath = $this->image->storeAs('photos', $this->image->hashName(), 'public');
            // dd($imageName, $this->image);
            ItemModel::create([
                'image' => $imagePath,
                'name' => $this->name,
                'jumlah' => $this->jumlah,
            ]);

            session()->flash('message', 'Data Berhasil DiSimpan');
            $this->dispatch('close-modal');
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function edit($itemId)
    {
        $this->mode = "edit";
        $this->modal_title = "Edit Barang";

        // memukan by id
        $item = ItemModel::findOrFail($itemId);

        $this->itemId = $item->id;
        $this->name = $item->name;
        $this->lastImage = $item->image;
        $this->jumlah = $item->jumlah;
        $this->dispatch('openModal');
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'image' => 'nullable|image|max:1024', 
        ]);
        try {
            $item = ItemModel::findOrFail($this->itemId);
            $imagePath = $item->image;
            // jika gambar baru maka perbarui
            if ($this->image) {
                $imageNewPath = $this->image->storeAs('photos', $this->image->hashName(), 'public');

                if ($imagePath !== $imageNewPath) {
                    Storage::disk('public')->delete($imagePath);
                    $imagePath = $imageNewPath;
                }
            }

            $item->image = $imagePath;
            $item->name = $this->name;
            $item->jumlah = $this->jumlah;
            $item->save();

            session()->flash('message', 'Data Berhasil DiUpdate');
            $this->resetInputFields();  
            $this->dispatch('close-modal');
        } catch (\Throwable $th) {
            session()->flash('message', $th->getMessage());
        }   
    }


    public function destroy()
    {
        $item = ItemModel::findOrFail($this->itemId);
        Storage::disk('public')->delete($item->image);
        $item->delete();

        session()->flash('message', 'Data Berhasil Dihapus');
        $this->dispatch('close-modal');
    }

    public function confirmDelete($itemId)
    {
        $this->itemId = $itemId;
        $this->dispatch('close-modal');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $items = ItemModel::query()
        ->where('name', 'like', "%{$this->search}%")  // Pencarian berdasarkan nama
        ->orWhere('jumlah', 'like', "%{$this->search}%")  
        ->paginate(10);  

    return view('livewire.items.item', compact('items'));

    }
}
