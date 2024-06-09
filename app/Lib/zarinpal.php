<?php

namespace App\lib;

use App\Payment;
use DB;
use nusoap_client;

/*require_once 'nusoap.php';*/

class zarinpal
{
    public $MerchantID;

    public function __construct()
    {
        $this->MerchantID = env('ZARRINPAL_MERCHAENT_ID');
    }

    public function pay($Amount, $Email, $Mobile)
    {
        $Description = 'فروش محصول';  // Required
        $CallbackURL = 'https://lania-online.ir/returnToLania';//url('/returnToLania'); // Required


        $client = new nusoap_client('https://www.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
        $client->soap_defencoding = 'UTF-8';
        $result = $client->call('PaymentRequest', [
            [
                'MerchantID' => $this->MerchantID,
                'Amount' => $Amount,
                'Description' => $Description,
                'Email' => $Email,
                'Mobile' => $Mobile,
                'CallbackURL' => $CallbackURL,
            ],
        ]);

        //Redirect to URL You can do it also by creating a form
        if ($result['Status'] == 100) {
            $payment = new Payment();
            $payment->MerchantID = $this->MerchantID;
            $payment->Authority = $result['Authority'];
            $payment->Amount = $Amount;
            $payment->Description = $Description;
            $payment->Email = $Email;
            $payment->Mobile = $Mobile;
            $payment->CallbackURL = $CallbackURL;
            $payment->save();

            return $result['Authority'];
        } else {
            return false;
        }


    }

}