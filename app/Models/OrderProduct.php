<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $fillable = ['order_id', 'name', 'quantity', 'price'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
