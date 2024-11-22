<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    use HasFactory;


    protected $fillable = [
        'nik','name','gender','birth_place','address','contact','status','image', 'division_id'
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    //relasi one-to-one
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
