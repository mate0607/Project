<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    /** @use HasFactory<\Database\Factories\MessageFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'car_id',
        'sale_id',
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
    ];

    protected $casts = [
        'car_id' => 'integer',
        'sale_id' => 'integer',
        'sender_id' => 'integer',
        'receiver_id' => 'integer',
        'is_read' => 'boolean',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
