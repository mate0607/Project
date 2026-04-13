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
        'vehicle_type',
        'model',
        'body_type',
        'engine_cc',
        'fuel_type',
        'documents_available',
        'document_type',
        'technical_inspection',
        'buyer_id',
        'seller_id',
        'price',
        'description',
        'car_condition',
        'mileage',
        'is_active',
        'image',
    ];

    // Tipuskonverziok az egyseges, kiszamithato kezeleshez.
    protected $casts = [
        'car_id' => 'integer',
        'buyer_id' => 'integer',
        'seller_id' => 'integer',
        'price' => 'decimal:2',
        'mileage' => 'integer',
        'engine_cc' => 'integer',
        'is_active' => 'boolean',
        'documents_available' => 'boolean',
        'technical_inspection' => 'boolean',
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

    public function images()
    {
        return $this->hasMany(SaleImage::class)->orderBy('sort_order');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
