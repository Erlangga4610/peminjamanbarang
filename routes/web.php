<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Dashboard;
use App\Livewire\Login;
use App\Livewire\Register;

// Route untuk halaman utama
Route::get('/', function () {
    return redirect()->route('login');
});

// Kelompokkan rute yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class);
    Route::get('/permission', App\Livewire\Permission\Index::class);

    Route::post('/logout', function () {
        Auth::logout();
        return redirect()->route('login');
    })->name('logout');
});

// Kelompokkan rute yang tidak memerlukan autentikasi (login dan register)
Route::group([], function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class);
});
