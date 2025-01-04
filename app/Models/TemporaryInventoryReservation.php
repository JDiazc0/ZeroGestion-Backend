<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryInventoryReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'raw_material_id',
        'quantity',
        'type',
        'expires_at',
    ];

    protected $cast = [
        'expires_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }
}
