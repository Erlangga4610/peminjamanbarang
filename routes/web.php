<?php

use Illuminate\Support\Facades\Route;
use Livewire\Component;
use App\Livewire\Dashboard;

// Route untuk halaman utama
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', Dashboard::class);
