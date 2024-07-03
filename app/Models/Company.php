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

  public static function boot()
  {
    parent::boot();

    static::created(function ($company) {
      PickupDeliveryPoint::create([
        'address' => $company->address,
        'company_id' => $company->id
      ]);
    });
  }

  public function cars()
  {
    return $this->hasMany(Car::class);
  }

  public function pickupDeliveryPoints()
  {
    return $this->hasMany(PickupDeliveryPoint::class);
  }
}
