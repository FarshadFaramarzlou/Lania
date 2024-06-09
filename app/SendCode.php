<?php

namespace App;

use phplusir\smsir\Smsir;


class SendCode
{

    public static function genRandomString()
    {
        $length = 6;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIGKLMNOPQRSTUVWXYZ';
        $string = '';

        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0, strlen($characters)-1)];
        }

        return $string;
    }


    public static function sendCode($phone)
    {


        $code = SendCode::genRandomString();

        $sms = 'کد فعال سازی:' . $code . ' سرویس آنلاین لانیا';
        Smsir::send($sms, $phone);

        return $code;
    }


}
