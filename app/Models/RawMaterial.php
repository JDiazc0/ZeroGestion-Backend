<?php

namespace App\Models;

use App\Enums\MeasureType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cost',
        'min_quantity',
        'measure',
        'user_id',
    ];

    protected $casts = [
        'measure' => MeasureType::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_raw_materials')
            ->using(ProductRawMaterial::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function inventory()
    {
        return $this->hasOne(RawMaterialInventory::class);
    }
}
