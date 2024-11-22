<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CartItemModel;

class CartsModel extends Model
{
    use HasFactory;

    protected $table = 'carts';

    protected $fillable = [
        'user_id',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(CartItemModel::class, 'cart_id', 'id');
    }
}
