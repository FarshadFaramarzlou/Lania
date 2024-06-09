<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail(int $int)
 * @method static find(string $trim)
 */
class Food extends Model
{

    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function FoodType()
    {
        return $this->belongsTo('App\FoodType');
    }

    public function getFoodsById($id)
    {
        $foods = FoodType::with('foods')
            ->find(2)
            ->foods;
        return $foods;
    }
}

