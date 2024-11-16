<div>

    <div class="pagetitle">
        <h1>Permissions</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a wire:click href="{{ '/dashboard' }}">Home</a></li>
                <li class="breadcrumb-item"><a wire:click href="{{ url('/permission')}}">Permissions</a></li>
                <li class="breadcrumb-item"><a wire:click href="{{ url('/user-role')}}">User Role</a></li>
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

    <button type="button" class="btn mb-3 btn-sm btn-primary" wire:click="create">
        Tambah Role
    </button>

    <div class="mb-1">
        <input type="text" class="form-control" name="query" placeholder="Cari Role" wire:model.live.debounce.100ms="search">
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th wire:click="sort('name')" style="cursor: pointer;">
                    Roles
                    @if ($sortBy === 'name')
                        <span>{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                    @endif
                </th>
                <th>Guard</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->guard_name }}</td>
                    <td>
                        <button wire:click="edit({{ $role->id }})" class="btn btn-warning">
                            Edit
                        </button>
                        <button wire:click="confirmDelete({{ $role->id }})" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            Delete
                        </button>
                        <button wire:click="show({{$role->id}})" class="btn btn-info">View</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Form Modal for Create and Edit -->
    <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalLabel">{{ $modal_title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="{{ $mode == 'create' ? 'store' : 'update' }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="role_name" class="form-label">Role Name</label>
                            <input type="text" class="form-control" id="role_name" wire:model="role_name" required>
                        </div>    
                        <div class="mb-3">
                            <label for="guard_name" class="form-label">Guard Name</label>
                            <input type="text" class="form-control" id="guard_name" wire:model="guard_name" required>
                        </div>
                        <div class="row">
                            @if(!$permissions->isEmpty())
                                @foreach ($permissions as $item)
                                    <div class="col-md-4">
                                        <input 
                                            type="checkbox" 
                                            value="{{ $item->id }}" 
                                            wire:model="selectedPermissions" 
                                            id="permission-{{ $item->id }}" 
                                        />
                                        <label for="permission-{{ $item->id }}">{{ $item->name }}</label>
                                    </div>
                                @endforeach
                            @endif
                        </div>                                          
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            {{ $mode == 'create' ? 'Save' : 'Update' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Role Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">{{ $modal_title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Display Role Details -->
                    <div class="mb-3">
                        <label for="view_role_name" class="form-label">Role Name</label>
                        <input type="text" class="form-control" id="view_role_name" wire:model="role_name" readonly>
                    </div>
                    {{-- <div class="mb-3">
                        <label for="view_guard_name" class="form-label">Guard Name</label>
                        <input type="text" class="form-control" id="view_guard_name" wire:model="guard_name" readonly>
                    </div> --}}

                    <!-- Display Permissions in Grid (4 per row) -->
                    <div class="mb-3">
                        <label for="view_permissions" class="form-label">Permissions</label>
                        <div class="row">
                            @foreach ($selectedPermissions as $permission)
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="permission-{{ $loop->index }}" disabled checked>
                                        <label class="form-check-label" for="permission-{{ $loop->index }}">{{ $permission }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
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
                    <p>Apakah Anda yakin ingin menghapus role "{{$this->role_name}}"?</p>
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
                $('#viewModal').modal('hide');  //keluar modal View 
                Livewire.emit('resetForm');
            });
            Livewire.on('openModal', () => {
                $('#formModal').modal('show');
                $('#deleteModal').modal('hide');
                Livewire.emit('resetForm');
            });
            Livewire.on('openViewModal', () => {
                $('#viewModal').modal('show');  // buka Modal View 
                Livewire.emit('resetForm');
            });
        });
    </script>
</div>
