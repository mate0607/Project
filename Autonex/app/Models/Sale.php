<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    /** @use HasFactory<\Database\Factories\SaleFactory> */
    use HasFactory, SoftDeletes;

    // A piacteri listing alapadatai, amelyeket create/update soran engedelyezunk.
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

    // Tipuskonverziok az egyseges, kiszamithato kezeleshez.
    protected $casts = [
        'car_id' => 'integer',
        'buyer_id' => 'integer',
        'seller_id' => 'integer',
        'price' => 'decimal:2',
        'mileage' => 'integer',
        'is_active' => 'boolean',
    ];

    // A meghirdetett auto kapcsolata.
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    // A vevo felhasznalo kapcsolata.
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // Az elado felhasznalo kapcsolata.
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
