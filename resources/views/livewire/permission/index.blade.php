<div>
    @if(auth()->user()->can('view-permission'))
    <div class="pagetitle">
        <h1>Permissions</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a wire:click href="{{ '/dashboard' }}">Home</a></li>
                <li class="breadcrumb-item"><a wire:click href="{{ url('/permission')}}">Permissions</a></li>
            </ol>
        </nav>
    </div>

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

    {{-- @can('role-create') --}}
    <button type="button" class="btn mb-3 btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#formModal" wire:click="create">
        Tambah Permission
    </button>
    {{-- @endcan --}}

    <div class="mb-1">
        <input type="text" class="form-control" name="query" placeholder="Cari Role Permission" wire:model.live.debounce.100ms="search">
    </div>

    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                {{-- <th scope="col">No</th> --}}
                <th scope="col" wire:click="sort('name')" style="cursor: pointer;">
                    Name
                    @if ($sortBy === 'name')
                        <span>{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                    @endif
                </th>
                <th scope="col" wire:click="sort('guard_name')" style="cursor: pointer;">
                    Guard Name
                    @if ($sortBy === 'guard_name')
                        <span>{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                    @endif
                </th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permissions as  $permission)
                <tr>
                    {{-- <td>{{ $loop->iteration }}</td> --}}
                    <td>{{ $permission->name }}</td>
                    <td>{{ $permission->guard_name }}</td>
                    <td>
                        <button wire:click="edit({{ $permission->id }})"data-bs-toggle="modal" data-bs-target="#formModal">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button type="button" wire:click="confirmDelete({{$permission->id}})"  data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div>
        {{ $permissions->links() }}
    </div>

    <!-- Form Modal Create dan Edit-->
    <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalLabel">{{ $modal_title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="{{ $mode == 'create' ? 'store' : 'update' }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Permission</label>
                            <input type="text" class="form-control" id="name" wire:model="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="guard_name" class="form-label">Guard Name</label>
                            <input type="text" class="form-control" id="guard_name" wire:model="guard_name" required>
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
                    <p>Apakah Anda yakin ingin menghapus permission "{{ $name }}"?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button wire:click="destroy" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </div>
    </div>
    
    @else
    <div class="mb-3">
        <h3 align="center">Kamu  Tidak Mempunyai Akses untuk Halaman ini</h3>
    </div>
    @endif

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('close-modal', () => {
                $('#formModal').modal('hide');
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