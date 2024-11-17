<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categori extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    protected $table = 'categories';

    /**
     * Relasi Many-to-Many ke Item
     */
    public function items()
    {
        return $this->belongsToMany(Item::class);
    }
}
