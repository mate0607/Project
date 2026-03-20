<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    /** @use HasFactory<\Database\Factories\CarFactory> */
    use HasFactory, SoftDeletes;

    // Ezek a mezok tolthetok tomegesen (create/update).
    protected $fillable = [
        'user_id',
        'make_model',
        'vin',
        'license_plate',
        'year',
    ];

    // Egy autohoz tobb idopont is tartozhat.
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // Egy autohoz tobb hibajegy is tartozhat.
    public function issues()
    {
        return $this->hasMany(Issue::class);
    }

    // Egy auto tobb piacteri hirdetesben is szerepelhet.
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    // Az auto tulajdonosa (felhasznalo).
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
