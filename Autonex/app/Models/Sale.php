<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    /** @use HasFactory<\Database\Factories\SaleFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'car_id',
        'buyer_id',
        'seller_id',
        'price',
        'description',
        'car_condition',
        'mileage',
        'is_active',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
