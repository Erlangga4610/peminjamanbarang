<div>
@if(auth()->user()->can('view-dashboard'))
<div class="pagetitle">
  <h1>Dashboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a wire:click href="{{ '/dashboard' }}">Home</a></li>
      <li class="breadcrumb-item"><a wire:click href="{{ '/dashboard' }}">Pages</a></li>
      <li class="breadcrumb-item active">Blank</li>
    </ol>
  </nav>
</div><!-- End Page Title -->


<section class="section">
  {{-- <div class="row">
    <div class="col-lg-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Example Card</h5>
          <p>This is an examle page with no contrnt. You can use it as a starter for your custom pages.</p>
        </div>
      </div>
    </div>

    
    <div class="col-lg-6">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Example Card</h5>
          <p>This is an examle page with no contrnt. You can use it as a starter for your custom pages.</p>
        </div>
      </div>
    </div>
  </div> --}}
  <div class="row">
    <div class="col-12">
      <div class="alert alert-success text-center" role="alert">
        <h3>Selamat Datang, {{ auth()->user()->name }}!</h3>
        <p class="mb-0">We hope you're having a great day!</p>
      </div>
    </div>
  </div>
</section>

@else
<div class="mb-3" align="center">
  <h2>kamu tidak Punya akses Untuk Halam ini</h2>
</div>
@endif
</div>