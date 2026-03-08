<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory, SoftDeletes;

    // Tomegesen kitoltheto attribitumok a create/update folyamatokhoz.
    protected $fillable = [
        'user_id',
        'car_id',
        'date',
        'time',
        'description',
        'status',
        'service',
    ];

    // Datum es kulcs mezok egyseges tipizalasa az alkalmazas tobbi reszehez.
    protected $casts = [
        'user_id' => 'integer',
        'car_id' => 'integer',
        'date' => 'date:Y-m-d',
    ];

    // Foglalo felhasznalo kapcsolata.
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A foglalashoz tartozo auto kapcsolata.
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
