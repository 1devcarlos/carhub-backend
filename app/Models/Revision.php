<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id', 'revision_date', 'description'
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
