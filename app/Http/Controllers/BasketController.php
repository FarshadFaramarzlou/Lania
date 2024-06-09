<?php /** @noinspection ALL */

namespace App\Http\Controllers;

use App\Basket;
use App\Food;
use App\lib\zarinpal;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BasketController extends Controller
{

    public function index()
    {
        $foods = Food::all();

        if (!Session::has('basket')) {
            //Seesion
            return view('basket', compact('foods'));
        } else {
            $oldBasket = Session::has('basket') ? Session::get('basket') : null;

            return view('basket', ['foods' => $foods, 'products' => $oldBasket->items, 'totalPrice' => $oldBasket->totalPrice]);
            //return $oldBasket->items;
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function saveBasket(Request $request)
    {

        if (Auth::check()) {
            if (!Session::has('basket')) {
                return redirect()->route('index');
            }
            $oldBasket = Session::has('basket') ? Session::get('basket') : null;
            $basket = new Basket($oldBasket);
            if (!isset(Auth::user()->address)) {
                Session::flash('warning', 'هشدار: ابتدا با ورود به پروفایل، آدرس خود را تکمیل کنید');
                return redirect()->back();
            }
            //Bank Pay
            if ($basket->totalPrice < 30000 && $request->input('tahvil_type') == 'peyk') {
                $basket->totalPrice = $basket->totalPrice + 4000;
            }
            if ($request->input('pay_type') == 'zarrin') {
                $zarinOrder = new zarinpal();
                $res = $zarinOrder->pay($basket->totalPrice, "", Auth::user()->phone);
                return redirect('https://www.zarinpal.com/pg/StartPay/' . $res);

            } elseif ($request->input('pay_type') === 'cash' or $request->input('pay_type') === 'pos') {
                //save Order
                $order = new Order();
                $order->basket = serialize($basket);

                $order->address = Auth::user()->address;

                $order->pay_type = $request->input('pay_type');
                $order->tahvil_type = $request->input('tahvil_type');

                Auth::user()->orders()->save($order);
                Session::forget('basket');
            }
            Session::flash('success', 'سفارش شما با کد: ' . $order->id . ' ثبت شد' . 'با تشکر از خرید شما');
            return redirect()->back();


        } else {
            return redirect()->route('login')->withInput(['phone' => $request->input('phone')]);
        }
    }

    public function ifOrderIsPayed(Request $request)
    {

        $MerchantID = env('ZARRINPAL_MERCHAENT_ID');
        $Authority = $request->get('Authority');

        //ما در اینجا مبلغ مورد نظر را بصورت دستی نوشتیم اما در پروژه های واقعی باید از دیتابیس بخوانیم
        $payment = \DB::table('payments')->where('Authority', $Authority)->first();
        $Amount = $payment->Amount;
        if ($request->get('Status') == 'OK') {
            $client = new nusoap_client('https://www.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
            $client->soap_defencoding = 'UTF-8';

            //در خط زیر یک درخواست به زرین پال ارسال می کنیم تا از صحت پرداخت کاربر مطمئن شویم
            $result = $client->call('PaymentVerification', [
                [
                    //این مقادیر را به سایت زرین پال برای دریافت تاییدیه نهایی ارسال می کنیم
                    'MerchantID' => $MerchantID,
                    'Authority' => $Authority,
                    'Amount' => $Amount,
                ],
            ]);

            if ($result['Status'] == 100) {
                $payment->resCode = $request['Status'];
                $payment->save();

                //save Order
                $order = new Order();
                $order->basket = serialize($basket);
                $order->address = Auth::user()->address;
                $order->pay_type = $request->input('pay_type');
                $order->tahvil_type = $request->input('tahvil_type');
                $order->authority = $authority;

                Auth::user()->orders()->save($order);
                Session::forget('basket');
                return redirect()->route('index', ['order' => $order]);
                return 'پرداخت با موفقیت انجام شد.';

            } else {
                return 'خطا در انجام عملیات';
            }
        } else {
            return 'خطا در انجام عملیات';
        }


    }

    public function getAddToBasket(Request $request, $id)
    {
        $products = Food::find($id);
        $oldBasket = Session::has('basket') ? Session::get('basket') : null;
        $basket = new Basket($oldBasket);
        $basket->add($products, $products->id);

        $request->session()->put('basket', $basket);
        //dd($request->session()->get('basket'));
        return redirect()->back();
        //return view('welcome',['products'=>$basket->items,'totalPrice'=>$basket->totalPrice]);

    }

    public function getAddToBasketBot($basket_key, $id)
    {
        $products = Food::find($id);
        $oldBasket = Session::has($basket_key) ? Session::get($basket_key) : null;
        $basket = new Basket($oldBasket);
        $basket->add($products, $products->id);

        Session::put($basket_key, $basket);
        //dd(Session()->get($basket_key));
        return redirect()->back();
        //return view('welcome',['products'=>$basket->items,'totalPrice'=>$basket->totalPrice]);

    }

    public function getDropFromBasket(Request $request, $id)
    {
        $products = Food::find($id);
        $oldBasket = Session::has('basket') ? Session::get('basket') : null;
        $basket = new Basket($oldBasket);
        $basket->reduceByOne($products, $products->id);

        $request->session()->put('basket', $basket);
        //dd($request->session()->get('basket'));
        return redirect()->back();
        //return view('welcome',['products'=>$basket->items,'totalPrice'=>$basket->totalPrice]);

    }

    public function getRemoveFromBasket(Request $request, $id)
    {
        $oldBasket = Session::has('basket') ? Session::get('basket') : null;
        $basket = new Basket($oldBasket);
        $basket->removeItem($id);

        $request->session()->put('basket', $basket);
        return redirect()->back();
        //return view('welcome',['products'=>$basket->items,'totalPrice'=>$basket->totalPrice]);

    }


}
