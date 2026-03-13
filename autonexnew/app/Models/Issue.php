<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Issue extends Model
{
    /** @use HasFactory<\Database\Factories\IssueFactory> */
    use HasFactory, SoftDeletes;

    // Ezek a mezok frissithetok/beszurhatok model szinten.
    protected $fillable = [
        'car_id',
        'category',
        'description',
        'urgency',
    ];

    // A projektben az urgencynel fix enum-jellegu string ertekekkel dolgozunk.
    protected $casts = [
        'car_id' => 'integer',
    ];

    // A hibajegy mindig egy autohoz tartozik.
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
