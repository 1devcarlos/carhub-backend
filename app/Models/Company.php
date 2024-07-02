<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
  use HasFactory;

  protected $fillable = [
    'company_name', 'address', 'phone', 'email'
  ];

  public function cars()
  {
    return $this->hasMany(Car::class);
  }

  public function pickupDeliveryPoints()
  {
    return $this->hasMany(PickupDeliveryPoint::class);
  }
}
