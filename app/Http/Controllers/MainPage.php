<?php

namespace App\Http\Controllers;

use App\FoodType;
use Illuminate\Support\Facades\Session;

class MainPage extends Controller
{


    public function index()
    {
        $foods = FoodType::with('foods')
            ->get();
        //return $foods;

        if (!Session::has('basket')) {
            return view('welcome', compact('foods'));

        } else {
            $oldBasket = Session::has('basket') ? Session::get('basket') : null;
            return view('welcome', ['foods' => $foods, 'products' => $oldBasket->items, 'totalPrice' => $oldBasket->totalPrice]);
            //return $oldBasket->items;
        }
//return redirect()->route('basket',compact('foods'));
    }

    public function getAgent()
    {
        $ismobile = 0;
        $container = $_SERVER['HTTP_USER_AGENT'];
        $browser = '';
// Mobile Company And Os
        $useragents = array('Blazer', 'Palm', 'Handspring', 'Nokia', 'Kyocera', 'Samsung', 'Motorola', 'Smartphone', 'Windows CE', 'Blackberry', 'WAP', 'SonyEricsson', 'PlayStation Portable', 'LG', 'MMP', 'OPWV', 'Symbian', 'EPOC', 'android', 'Android');

        foreach ($useragents as $useragents) {
            if (strstr($container, $useragents)) {
                $ismobile = 1;
                $browser = $useragents;
            } else {
                $ismobile = 2;
                $browser = 'Unknowen';
            }
        }
        if ($ismobile == 1) {
            echo "<p>Browsing Using " . $browser . " device</p>";
        }

        return $browser;
    }
}
