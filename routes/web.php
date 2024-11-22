<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route untuk halaman utama
Route::get('/', function () {
    return redirect()->route('login');
});

// Kelompokkan rute yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', App\Livewire\Dashboard::class);
    Route::get('/permission', App\Livewire\Permission\Index::class);
    Route::get('/role-permission', App\Livewire\Permission\RolePermission::class);
    Route::get('/user-role', App\Livewire\Permission\UserRole::class);
    Route::get('/item', App\Livewire\Items\Item::class);
    Route::get('/category', App\Livewire\Items\Category::class);
    Route::get('/employee', App\Livewire\Employees\Employee::class);
    Route::get('/division', \App\Livewire\Employees\Division::class);
    Route::get('/borrowing', \App\Livewire\Borrowings\Borrowing::class);


    Route::post('/logout', function () {
        Auth::logout();
        return redirect()->route('login');
    })->name('logout');
});

// Kelompokkan rute yang tidak memerlukan autentikasi (login dan register)
Route::group([], function () {
    Route::get('/login', App\Livewire\Login::class)->name('login');
    Route::get('/register', App\Livewire\Register::class);
});
