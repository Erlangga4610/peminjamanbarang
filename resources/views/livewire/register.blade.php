<div>
  <div class="card mb-3">
      <div class="card-body">
          <div class="pt-4 pb-2">
              <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
              <p class="text-center small">Enter your personal details to create an account</p>
          </div>

          @if (session()->has('message'))
              <div class="alert alert-success">
                  {{ session('message') }}
              </div>
          @endif

          <form wire:submit.prevent="register" class="row g-3 needs-validation" novalidate>
              <div class="col-12">
                  <label for="yourName" class="form-label">Your Name</label>
                  <input wire:model="name" type="text" class="form-control" id="yourName" required>
                  <div class="invalid-feedback">Please, enter your name!</div>
                  @error('name') <span class="text-danger">{{ $message }}</span> @enderror
              </div>

              <div class="col-12">
                  <label for="yourEmail" class="form-label">Your Email</label>
                  <input wire:model="email" type="email" class="form-control" id="yourEmail" required>
                  <div class="invalid-feedback">Please enter a valid email address!</div>
                  @error('email') <span class="text-danger">{{ $message }}</span> @enderror
              </div>

              <div class="col-12">
                  <label for="yourPassword" class="form-label">Password</label>
                  <input wire:model="password" type="password" class="form-control" id="yourPassword" required>
                  <div class="invalid-feedback">Please enter your password!</div>
                  @error('password') <span class="text-danger">{{ $message }}</span> @enderror
              </div>

              <div class="col-12">
                  <label for="yourPasswordConfirmation" class="form-label">Confirm Password</label>
                  <input wire:model="password_confirmation" type="password" class="form-control" id="yourPasswordConfirmation" required>
                  <div class="invalid-feedback">Please confirm your password!</div>
              </div>

              <div class="col-12">
                  <div class="form-check">
                      <input wire:model="terms" class="form-check-input" type="checkbox" id="acceptTerms" required>
                      <label class="form-check-label" for="acceptTerms">I agree and accept the <a href="#">terms and conditions</a></label>
                      <div class="invalid-feedback">You must agree before submitting.</div>
                  </div>
              </div>

              <div class="col-12">
                  <button class="btn btn-primary w-100" type="submit">Create Account</button>
              </div>

              <div class="col-12">
                  <p class="small mb-0">Already have an account? <a href="{{ url('/login') }}">Log in</a></p>
              </div>
          </form>
      </div>
  </div>
</div>
