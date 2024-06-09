<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BotBasket extends Model
{

    public function user()
    {
        return $this->hasOne('App\User','chat_id');
    }
    /*
        public function __construct($user_id)
        {
            if ($user_id) {
                $this->user_id = $user_id;
            }
        }*/
}
