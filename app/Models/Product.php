<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'price',
        'quantity',
        'subcategory_id',
    ];

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderitems()
    {
        return $this->hasMany(OrderItem::class);
    }

    


}
