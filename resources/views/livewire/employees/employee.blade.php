<div>
    <div class="pagetitle">
        <h1>Data Karyawan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a wire:click href="{{ '/dashboard' }}">Home</a></li>
                <li class="breadcrumb-item"><a wire:click href="{{ url('/i')}}">~</a></li>
                <li class="breadcrumb-item"><a wire:click href="{{ url('/c')}}">~</a></li>
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
                Tambah Karyawan
            </button>

            {{-- table --}}
            <div class="card border-0 rounded shadow-sm">
                <table class="table table-bordered">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th>Nik</th>
                            <th>Name</th>
                            <th>Jenis kelamin</th>
                            <th>Tempat Tanggal Lahir</th>
                            <th>Alamat</th>
                            <th>Kontak</th>
                            <th>Status</th>
                            <th>Image</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employee as $value)
                            <tr>
                                <td>{{ $value->nik }}</td>
                                <td>{{ $value->name }}</td>
                                <td>{{ $value->gender == 1 ? 'Male' : 'Female' }}</td>
                                <td>{{ $value->birth_place }}</td>
                                <td>{{ $value->address }}</td>
                                <td>{{ $value->contact}}</td>
                                <td>{{ $value->status == 0 ? 'Active' : 'Inactive' }}</td>
                                <td><img src="{{ asset('storage/'.$value->image) }}" class="img-fluid" style="max-width: 100px;" alt="Image"></td>
                                <td>
                                    <button wire:click="edit({{ $value->id }})" class="btn btn-warning btn-sm">Edit</button>
                                    <button wire:click="delete({{ $value->id }})" class="btn btn-danger btn-sm">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-danger">Data karyawan belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- Modal create/edit --}}
    <div>
        <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true" wire:ignore.self> 
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="formModalLabel">{{ $modal_title }}</h5>
                        <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="{{ $mode == 'create' ? 'store' : 'update' }}">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text" class="form-control" wire:model="nik" id="nik" required>
                                @error('nik') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" wire:model="name" id="name" required>
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-control" wire:model="gender" required>
                                    <option value="" disabled selected>Pilih Gender</option>
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                </select>
                                @error('gender') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="birth_place" class="form-label">Birth Place</label>
                                <input type="date" class="form-control" wire:model="birth_place" id="birth_place" required>
                                @error('birth_place') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" wire:model="address" id="address" rows="3" required></textarea>
                                @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" wire:model="status" id="status" required>
                                    <option value="" disabled selected>Pilih Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>                            

                            <div class="mb-3">
                                <label for="contact" class="form-label">Contact</label>
                                <input type="text" class="form-control" wire:model="contact" id="contact" required>
                                @error('contact') <span class="text-danger">{{ $message }}</span> @enderror
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

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-warning" wire:click="resetInputFields">Reset</button>
                            <button type="submit" class="btn btn-primary">
                                {{ $mode == 'create' ? 'Save' : 'Update' }}                            
                            </button>
                        </div>
                    </form>
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
