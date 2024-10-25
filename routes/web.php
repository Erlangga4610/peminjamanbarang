<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\Login;
use App\Livewire\Register;

// Route untuk halaman utama
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', Dashboard::class);

Route::get('/login',Login::class);
Route::get('/register',Register::class);
