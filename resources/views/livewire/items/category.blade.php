<div>
    <div class="pagetitle">
        <h1>Data Kategori</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a wire:click href="{{ '/dashboard' }}">Home</a></li>
                <li class="breadcrumb-item"><a wire:click href="{{ url('/category')}}">Data Kategori</a></li>
                <li class="breadcrumb-item"><a wire:click href="{{ url('/item')}}">Data Barang</a></li>
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

            <button type="button" class="btn mb-3 btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#formModal"
                wire:click="create">
                Tambah Kategori
            </button>

            <div class="mb-1">
                <input type="text" class="form-control" name="query" placeholder="Cari Barang" wire:model.live.debounce.100ms="search">
            </div>

            {{-- table --}}
            <div class="card border-0 rounded shadow-sm">
                <table class="table table-bordered">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th scope="col">Name kategori</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $value)
                            <tr>
                                <td>{{$value->name}}</td>
                                <td>
                                    <button wire:click="edit({{$value->id}})" class="btn btn-sm btn-warning"data-bs-toggle="modal" data-bs-target="#formModal">
                                        Edit
                                    </button>
                                    <button wire:click="confirmdelete({{$value->id}})" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-danger">Data Belum tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div>
                    {{$categories->links()}}
                </div>

            </div>
        </div>

    </div>

    {{-- Modal create dan Edit--}}
    <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalLabel">{{$modal_title}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
    
                <!-- Modal Form -->
                <form wire:submit.prevent="{{$mode == 'create' ? 'store' : 'update'}}">
                    <div class="modal-body">
                        <!-- Nama Kategori Input -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="name" wire:model="name" required>
                            @error('name')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
    
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="reset" class="btn btn-warning">Reset</button>
                        <button type="submit" class="btn btn-primary">
                            {{$mode == 'create' ? 'Save' : 'Update'}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

    {{-- konfirmasi Delete --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus Kategori "{{}}"?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button wire:click="destroy" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('close-modal', () => {
                $('#formModal').modal('hide');
                $('#deleteModal').modal('hide');
                
            })
        })
    </script>
</div>


