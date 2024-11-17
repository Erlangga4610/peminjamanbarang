<div>
    <div class="pagetitle">
        <h1>Data Barang</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a wire:click href="{{ '/dashboard' }}">Home</a></li>
                <li class="breadcrumb-item"><a wire:click href="{{ url('/item')}}">Data Barang</a></li>
                <li class="breadcrumb-item"><a wire:click href="{{ url('/category')}}">Data Kategori</a></li>

            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12">

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
                Tambah Barang
            </button>      
            <button type="button" class="btn mb-3 btn-sm btn-primary" onclick="window.location='/category'">
                Tambah Kategori
            </button>            

            <div class="mb-1">
                <input type="text" class="form-control" name="query" placeholder="Cari Barang" wire:model.live.debounce.100ms="search">
            </div>

            {{-- Table --}}
            <div class="card border-0 rounded shadow-sm">
                <table class="table table-bordered">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">kategori</th>
                            <th scope="col">Image</th>
                            <th scope="col">Jumlah</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->category ? $item->category->name : 'Tanpa Kategori' }}
                                </td>
                                <td class="">
                                    <img src="{{ asset('/storage/'.$item->image) }}" class="rounded" style="max-width: 50px; height: auto;">
                                </td>   
                                <td>{{ $item->jumlah }}</td>
                                <td>
                                    <button wire:click="edit({{ $item->id }})" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#formModal">
                                        Edit
                                    </button>
                                    <button wire:click="confirmDelete({{ $item->id }})" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-danger">Data Barang belum Tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>{{ $items->links() }}</div>
        </div>
    </div>

    <!-- Form Modal Create and Edit-->
    <div>
        <!-- Form Modal Create and Edit-->
        <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="formModalLabel">{{ $modal_title }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="{{ $mode == 'create' ? 'store' : 'update' }}">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Barang</label>
                                <input type="text" class="form-control" id="name" wire:model="name" required>
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <!-- Inside your modal form -->
                            <div class="mb-3">
                                <label for="category" class="form-label">Kategori</label>
                                <select wire:model="selectedCategory" id="category" class="form-control" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('selectedCategory') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" wire:model="image">
                                @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                                @if ($mode == 'edit' && $lastImage)
                                    <div class="mt-2">
                                        <p><strong>Gambar Lama:</strong></p>
                                        <img src="{{ asset('storage/'.$lastImage) }}" class="img-fluid" style="max-width: 100px; height: auto;" alt="Gambar Lama">
                                    </div>
                                @endif
                                @if ($image)
                                    <div class="mt-2">
                                        <p><strong>Gambar Baru:</strong></p>
                                        <img src="{{ $image->temporaryUrl() }}" class="img-fluid" style="max-width: 100px; height: auto;" alt="Preview Gambar">
                                    </div>
                                @endif
                            </div>                                                      
                            <div class="mb-3">
                                <label for="jumlah" class="form-label">Jumlah</label>
                                <input type="number" class="form-control" id="jumlah" wire:model="jumlah" required>
                                @error('jumlah') <span class="text-danger">{{ $message }}</span> @enderror
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
             // Mencegah modal tertutup saat file upload
             const fileInput = document.querySelector('input[type="file"]');
            if (fileInput) {
                fileInput.addEventListener('change', (e) => {
                    e.stopPropagation();
                });
            }
        });
        document.addEventListener('livewire:init', () => {
            Livewire.on('openModal', () => {
                $('#formModal').modal('show');
                $('#deleteModal').modal('hide');
                Livewire.emit('resetForm');
            });
            
        });
    </script>
</div>

</div>
