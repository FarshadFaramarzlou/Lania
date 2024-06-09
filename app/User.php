<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @method static find($id)
 */
class User extends Authenticatable
{
    use Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'type_id', 'avatar', 'code', 'active', 'password', 'address', 'chat_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function botBasket()
    {
        return $this->hasOne('App\BotBasket', 'user_id');
    }

    public function userType()
    {
        return $this->belongsTo('App\userType');
    }

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

}
