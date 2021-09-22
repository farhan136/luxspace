<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Cart;
use App\Models\Product_Gallerie;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'slug',
    ];

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function galleries()
    {
        return $this->hasMany(Product_Gallerie::class);
    }

}
