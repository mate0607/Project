<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleImage extends Model
{
    protected $fillable = ['sale_id', 'path', 'sort_order'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
