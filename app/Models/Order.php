<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_number', 'customer_name', 'customer_address'];

    // Generate nomor invoice otomatis sebelum menyimpan
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            $order->invoice_number = 'INV/SOLJU/ ' . now()->format('Ymd') . '-' . str_pad(Order::max('id') + 1, 4, '0', STR_PAD_LEFT);
        });
    }
    public function products()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }
}
