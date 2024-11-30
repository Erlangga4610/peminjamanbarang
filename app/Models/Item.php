<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'image', 'jumlah','category_id',
    ];

    /**
     * Relasi Many-to-Many ke Categori
     */
    public function category()
    {
        return $this->belongsTo(Categori::class, 'category_id');
    }

    public function borrowings()
    {
        return $this->belongsToMany(Borrowing::class,'item_borrowing', 'item_id', 'borrowing_id');
        return $this->belongsToMany(Borrowing::class, 'borrow_item')
                    ->withPivot('jumlah_barang') // Mengakses kolom jumlah_barang di tabel pivot
                    ->withTimestamps();
    }
}
