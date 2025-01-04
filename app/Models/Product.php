<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'selling_price',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the raw materials for the product.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rawMaterials()
    {
        return $this->belongsToMany(RawMaterial::class, 'product_raw_materials')
            ->using(ProductRawMaterial::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }

    /**
     * Get the orders for the product.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_products')
            ->using(OrderProduct::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }


    public function inventory()
    {
        return $this->hasOne(ProductInventory::class);
    }

    public function temporaryReservations()
    {
        return $this->hasMany(TemporaryInventoryReservation::class);
    }
}
