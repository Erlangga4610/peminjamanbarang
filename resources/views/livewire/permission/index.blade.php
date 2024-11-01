<div>
    <div class="pagetitle">
        <h1>Permissions</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a wire:navigate href="{{ '/dashboard' }}">Home</a></li>
                <li class="breadcrumb-item"><a wire:navigate href="{{ url('/permission')}}">Permissions</a></li>
            </ol>
        </nav>
    </div>

    @if (session()->has('message'))
    <div class="toast-container top-0 end-0 p-3">
        <div class="toast show fade bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true" id="liveToast">
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
        Tambah Permission
    </button>

    <div class="mb-1">
        <input type="text" class="form-control" name="query" placeholder="Cari Role Permission" wire:model.live.debounce.100ms="search">
    </div>

    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
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
            @foreach ($permissions as $index => $permission)
                <tr>
                    <td>{{$permissions->firstItem() + $index }}</td>
                    <td>{{ $permission->name }}</td>
                    <td>{{ $permission->guard_name }}</td>
                    <td>
                        <button wire:click="edit({{ $permission->id }})" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#formModal">
                            Edit
                        </button>
                        <button type="button" wire:click="confirmDelete({{$permission->id}})" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            Delete
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
        <div class="modal-dialog">
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

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('close-modal', () => {
                $('#formModal').modal('hide');
                $('#deleteModal').modal('hide');
                Livewire.emit('resetForm');
            });
        });
    </script>
</div>