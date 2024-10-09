<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'primary',
        'secondary',
        'tertiary',
        'light_text',
        'primary_text',
        'secondary_text',
        'primary_background',
        'secondary_background',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
