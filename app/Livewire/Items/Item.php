<?php
namespace App\Livewire\Items;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Item as ItemModel;
use App\Models\Categori;
use Illuminate\Support\Facades\Storage;

class Item extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $image, $lastImage;
    public $name, $jumlah;
    public $itemId;  // Untuk memperbarui atau mengedit item tertentu
    public $modal_title = '';
    public $mode = ''; // untuk mengelola mode create/edit
    public $search = '';
    public $selectedCategory = ''; // Untuk menyimpan ID kategori yang dipilih
    public $categories; // Untuk menyimpan kategori untuk dropdown
    protected $paginationTheme = 'bootstrap';
    public $sortBy = 'name'; //kolom default untuk sorting
    public $sortDirection = 'asc';
    

    // Reset input fields setelah create or update
    public function resetInputFields()
    {
        $this->image = '';
        $this->name = '';
        $this->jumlah = '';
        $this->selectedCategory = ''; // Reset category selection
    }

    public function mount()
    {
        // Fetch categories on mount
        $this->categories = Categori::all();
    }

    protected $rules = [
        'name' => 'required|string|max:255',
        'jumlah' => 'required|integer|min:1',
        'image' => 'required|image|max:1024', // image validation
        'selectedCategory' => 'required|exists:categories,id', // Validate category
    ];

    public function create()
    {
        $this->mode = "create";
        $this->modal_title = "Tambah Barang";
        $this->resetInputFields();
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255|min:3',
            'jumlah' => 'required|integer|min:1',
            'image' => 'required|image|max:1024', // image validation
            'selectedCategory' => 'required|exists:categories,id', // Validate category
        ]);

        try {
            // Menyimpan file gambar dengan nama acak di dalam folder 'public/items'
            $imagePath = $this->image->storeAs('photos', $this->image->hashName(), 'public');
            
            ItemModel::create([
                'image' => $imagePath,
                'name' => $this->name,
                'jumlah' => $this->jumlah,
                'category_id' => $this->selectedCategory, // Store the selected category
            ]);

            session()->flash('message', 'Data Berhasil DiSimpan');
            $this->dispatch('close-modal');
        } catch (\Throwable $th) {
            session()->flash('message', $th->getMessage());
        }
    }

    public function edit($itemId)
    {
        $this->mode = "edit";
        $this->modal_title = "Edit Barang";
        $this->resetInputFields();

        // Find the item by ID
        $item = ItemModel::findOrFail($itemId);

        $this->itemId = $item->id;
        $this->name = $item->name;
        $this->lastImage = $item->image;
        $this->jumlah = $item->jumlah;
        $this->selectedCategory = $item->category_id; 

        $this->dispatch('openModal');
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255|min:3',
            'jumlah' => 'required|integer|min:1',
            'image' => 'nullable|image|max:1024|min:', 
            'selectedCategory' => 'required|exists:categories,id', // Validate category
        ]);

        try {
            $item = ItemModel::findOrFail($this->itemId);
            $imagePath = $item->image;

            // Update image if it's changed
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
            $item->category_id = $this->selectedCategory; // Update category
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
        $items = ItemModel::query()
            ->where('name', 'like', "%{$this->search}%")  
            ->orWhere('jumlah', 'like', "%{$this->search}%")  
            ->with('category') //
            ->orderBy($this->sortBy, $this->sortDirection) 
            ->paginate(10);  

        return view('livewire.items.item', compact('items'));
    }
}
