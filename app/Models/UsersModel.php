<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CartsModel;

class UsersModel extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'full_name',
        'email',
        'phone_number',
        'password',
        'address',
        'date_birth',
    ];

    public function cart()
    {
        return $this->hasMany(CartsModel::class, 'user_id', 'id');
    }
}
