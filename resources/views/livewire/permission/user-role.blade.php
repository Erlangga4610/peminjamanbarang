<div>
    <!-- Pesan berhasil -->
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

    <!-- Form untuk Create atau Edit Role pada User -->
    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
        <div class="mb-3">
            <label for="user" class="form-label">Pilih User</label>
            <select wire:model="user_id" class="form-select">
                <option value="">Pilih User</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
            @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Pilih Role</label>
            <select wire:model="role_id" class="form-select">
                <option value="">Pilih Role</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
            @error('role_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            {{ $isEdit ? 'Update Role' : 'Assign Role' }}
        </button>
        <button type="reset" class="btn btn-warning">Reset</button>
    </form>

    <hr>

    <!-- Tabel untuk menampilkan user dan role mereka -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>User Name</th>
                <th>User Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @foreach ($user->roles as $role)
                            <span class="badge bg-primary">{{ $role->name }}</span>
                        @endforeach
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm" wire:click="edit({{ $user->id }})">Edit</button>
                        <button class="btn btn-danger btn-sm" wire:click="deleteRole({{ $user->id }})">Remove Role</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
