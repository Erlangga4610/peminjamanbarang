<div>
    <h1>Account Settings</h1>

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

        {{-- Error Message --}}
        @if (session()->has('error'))
        <div class="toast-container top-0 end-0 p-3">
            <div class="toast show fade bg-danger text-white" role="alert" aria-live="assertive" aria-atomic="true" id="errorToast" data-bs-delay="3000">
                <div class="toast-body">
                    <div class="d-flex gap-4">
                        <span><i class="fa-solid fa-circle-exclamation fa-lg"></i></span>
                        <div class="d-flex flex-grow-1 align-items-center">
                            <span class="fw-semibold">{{ session('error') }}</span>
                            <button type="button" class="btn-close btn-close-sm btn-close-white ms-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    <form wire:submit.prevent="updateSettings">
        <!-- Name Field -->
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" wire:model="name" required>
            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <!-- Email Field -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" wire:model="email" required>
            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <!-- Current Password Field -->
        <div class="mb-3">
            <label for="current_password" class="form-label">Current Password</label>
            <input type="password" class="form-control" id="current_password" wire:model="current_password" required>
            @error('current_password') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <!-- New Password Field -->
        <div class="mb-3">
            <label for="new_password" class="form-label">New Password (optional)</label>
            <input type="password" class="form-control" id="new_password" wire:model="new_password">
            @error('new_password') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <!-- New Password Confirmation Field -->
        <div class="mb-3">
            <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="new_password_confirmation" wire:model="new_password_confirmation">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Update Settings</button>
    </form>
</div>
