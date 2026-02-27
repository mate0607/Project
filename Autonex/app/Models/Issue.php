<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Issue extends Model
{
    /** @use HasFactory<\Database\Factories\IssueFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'car_id',
        'category',
        'description',
        'urgency',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
