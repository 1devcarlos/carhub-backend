<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand', 'model', 'color', 'year', 'daily_price', 'photo_url', 'status', 'company_id'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function revisions()
    {
        return $this->hasMany(Revision::class);
    }
}
