<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CartsModel;

class CartItemModel extends Model
{
    use HasFactory;

    protected $table = 'cart_items';

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price',
    ];

    public function cart()
    {
        return $this->belongsTo(CartsModel::class, 'cart_id', 'id');
    }
}
