<div>

    <div class="pagetitle">
        <h1>Peminjaman</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a wire:click href="{{ '/dashboard' }}">Home</a></li>
                <li class="breadcrumb-item"><a wire:click href="{{ url('/item')}}">Data Barang</a></li>
                <li class="breadcrumb-item"><a wire:click href="{{ url('/category')}}">Data Kategori</a></li>

            </ol>
        </nav>
    </div>

    {{-- Success Message --}}
    @if (session()->has('message'))
    <div class="toast-container top-0 end-0 p-3">
        <div class="toast show fade bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true" id="liveToast" data-bs-delay="3000">
            <div class="toast-body">
                <div class="d-flex gap-4">
                    <span><i class="fa-solid fa-circle-check fa-lg"></i></span>
                    <div class="d-flex flex-grow-1 align-items-center">
                        <span class="fw-semibold">{{ session('message') }}</span>
                        <button type="button" class="btn-close btn-close-sm btn-close-white ms-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <button type="button" class="btn mb-3 btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#formModal" wire:click="create">
        Peminjaman
    </button> 

    <div class="mb-1">
        <input type="text" class="form-control" name="query" placeholder="Cari Barang" wire:model.live.debounce.100ms="search">
    </div>

    <!-- Tabel Peminjaman -->
    <table class="table table-striped mt-4 table-bordered">
        <thead>
            <tr>
                <th>Nama Karyawan</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Items</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowings as $borrowing)
                <tr>
                    <td>{{$borrowing->employee->name }}</td>
                    <td>{{ $borrowing->tanggal_pinjam }}</td>
                    <td>{{ $borrowing->tanggal_kembali }}</td>
                    <td>
                        @foreach($borrowing->items as $item)
                            {{ $item->name }}<br>
                        @endforeach
                    </td>
                    <td>
                        <button wire:click="edit({{ $borrowing->id }})" data-bs-toggle="modal"data-bs-target="#formModal"><i class="fa fa-edit"></i></button>
                        <button wire:click="confirmDelete({{ $borrowing->id }})" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fa fa-trash" ></i> 
                        </button>
                        
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $borrowings->links() }}

    <!-- Modal untuk Create/Edit -->
    <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalLabel">{{ $modal_title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="{{ $mode == 'create' ? 'store' : 'update' }}">
                    <div class="modal-body">

                        {{-- karyawan --}}
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee</label>
                            <select wire:model="employee_id" id="employee_id" class="form-control" required>
                                <option value="">Pilih Karyawan</option>
                                @foreach ($employees as $employee)
                                    <option value="{{$employee->id}}">{{$employee->name}}</option>
                                @endforeach
                            </select>
                            @error ('employee_id') <span class="text-danger">{{$message}}</span> @enderror
                        </div>

                        {{-- Untuk Items --}}
                        <div class="mb-3">
                            <label for="item_id" class="form-label">Items</label>
                            <select wire:model="item_id" id="items" class="form-control" required>
                                <option value="">Pilih Barang</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('items') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Tanggal Pinjam -->
                        <div class="mb-3">
                            <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                            <input type="date" wire:model="tanggal_pinjam" id="tanggal_pinjam" class="form-control" required>
                            @error('tanggal_pinjam') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tanggal Kembali -->
                        <div class="mb-3">
                            <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                            <input type="date" wire:model="tanggal_kembali" id="tanggal_kembali" class="form-control" required>
                            @error('tanggal_kembali') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                       
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="reset" class="btn btn-warning">Reset</button>
                        <button type="submit" class="btn btn-primary">
                            {{ $mode == 'create' ? 'Save' : 'Update' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

     <!-- Delete Confirmation Modal -->
     <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus Barang ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button wire:click="destroy" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('close-modal', () => {
                $('#formModal').modal('hide');
                $('#deleteModal').modal('hide');
                Livewire.emit('resetForm');
            });
        });
        document.addEventListener('livewire:init', () => {
            Livewire.on('openModal', () => {
                $('#formModal').modal('show');
                $('#deleteModal').modal('hide');
                Livewire.emit('resetForm');
            });
            
            
        });
    </script>

    {{-- css untuk table --}}
    <style>
        .table th, .table td {
            padding: 5px 10px; /* Mengurangi padding untuk membuat tabel lebih kecil */
            font-size: 12px; /* Mengurangi ukuran font */
        }

        .table img {
            max-width: 50px; /* Membuat gambar lebih kecil */
            height: auto;
        }

        .btn-sm i {
        font-size: 14px; /* Adjust icon size */
        margin-right: 5px; /* Add some spacing between icon and text */
        }
    </style>

    
</div>
