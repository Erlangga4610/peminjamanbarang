<?php

// app/Models/Borrowing.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = ['user_id', 'employee_id', 'tanggal_pinjam', 'tanggal_kembali', 'status'];

    // Relasi Many-to-Many dengan Item
    public function items()
    {
        // Pastikan nama tabel pivot adalah 'item_borrowing' dengan kolom 'borrowing_id' dan 'item_id'
        return $this->belongsToMany(Item::class, 'item_borrowing', 'borrowing_id', 'item_id')->withPivot('quantity');
    }

    // Relasi Many-to-One dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi Many-to-One dengan Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

