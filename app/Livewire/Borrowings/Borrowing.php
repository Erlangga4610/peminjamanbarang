<?php
namespace App\Livewire\Borrowings;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Borrowing as Borrow;
use App\Models\Employee;
Use App\Models\Item;

class Borrowing extends Component
{
    use WithPagination;

    public $tanggal_pinjam, $tanggal_kembali, $borrowId, $employee_id, $item_id, $status;
    public $modal_title = '';
    public $mode = '';
    public $search = '';
    public $borrowName;

    protected $rules = [
        'tanggal_pinjam' => 'required|date',
        'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
        'employee_id' => 'required|exists:employees,id',
        'item_id' => 'required|exists:items,id', 
        'status' => 'required|boolean', 
    ];

    public function resetInputFields()
    {
        $this->tanggal_pinjam = '';
        $this->tanggal_kembali = '';
        $this->employee_id = '';
        $this->item_id = 'null';
        $this->status = '';
    }

    public function create()
    {
        $this->resetInputFields();
        $this->modal_title = 'Tambah Peminjaman';
        $this->mode = 'create';
    }

    public function store()
    {
        $this->validate();
        // dd($this->tanggal_pinjam, $this->tanggal_kembali, $this->employee_id, $this->item_id); // Add this line

        $borrowing = Borrow::create([
            'tanggal_pinjam' => $this->tanggal_pinjam,
            'tanggal_kembali' => $this->tanggal_kembali,
            'employee_id' => $this->employee_id,
            'status' => $this->status,
        ]);

        $borrowing->items()->sync($this->item_id);
        
        $this->resetInputFields();
        $this->dispatch('close-modal');
        session()->flash('message', 'Borrowing added successfully!');
    }

    public function edit($borrowId)
    {
        $this->mode = "edit";
        $this->modal_title = "Edit Peminjaman";
        $this->resetInputFields();

        $borrowings = Borrow::findOrFail($borrowId);

        $this->borrowId = $borrowings->id;
        $this->tanggal_pinjam = $borrowings->tanggal_pinjam;
        $this->tanggal_kembali = $borrowings->tanggal_kembali;
        $this->employee_id = $borrowings->employee_id;
        $this->item_id = $borrowings->items->first()->id ?? null;
        $this->status = $borrowings->status;

        $this->dispatch('openModal');
    }

    public function update()
    {
        $this->validate();
        // dd($this->tanggal_pinjam, $this->tanggal_kembali, $this->employee_id, $this->item_id);


        $borrow = Borrow::findOrFail($this->borrowId);

        $borrow->update([
            'tanggal_pinjam' => $this->tanggal_pinjam,
            'tanggal_kembali' => $this->tanggal_kembali,
            'employee_id' => $this->employee_id,
            'status' => $this->status,
        ]);

        // Tangani item berdasarkan situasi
        // Jika $this->item_id adalah array (beberapa item), gunakan sinkronisasi
        if (is_array($this->item_id)) {
            $borrow->items()->sync($this->item_id); // Ini menyinkronkan beberapa item
        } else {
            $borrow->items()->sync([$this->item_id]); // Sinkronkan satu item sebagai array
        }

        
        $this->resetInputFields();
        $this->dispatch('close-modal');
        session()->flash('message', 'Borrowing updated successfully!');
    }

    public function confirmDelete($borrowId)
    {
        $this->borrowId = $borrowId;
        $borrow = Borrow::with('employee')->find($borrowId);
        $this->borrowName = $borrow ? $borrow->employee->name : null;
        $this->dispatch('openDeleteModal');
    }

    public function destroy()
    {
        $borrow = Borrow::findOrFail($this->borrowId);
        $borrow->delete();

        $this->dispatch('close-modal');
        session()->flash('message', 'Borrowing deleted successfully!');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $borrowings = Borrow::with('user', 'employee')
            ->when($this->search, function ($query) {
                $query->where('tanggal_pinjam', 'like', '%'.$this->search.'%')
                      ->orWhere('tanggal_kembali', 'like', '%'.$this->search.'%')
                      ->orWhereHas('employee', function ($query) {
                          $query->where('name', 'like', '%'.$this->search.'%'); 
                      })
                      ->orWhereHas('items', function ($query) {
                          $query->where('name', 'like', '%'.$this->search.'%'); 
                      });
            })
            ->paginate(10);

            $items = Item::all(); 
            $employees = Employee::all();
    
            return view('livewire.borrowings.borrowing', [
                'borrowings' => $borrowings,
                'employees' => $employees,
                'items' => $items,
            ]);
    }

}
