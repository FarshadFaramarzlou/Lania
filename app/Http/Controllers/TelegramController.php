<?php

namespace App\Http\Controllers;

use App\Basket;
use App\Food;
use App\Http\Controllers\Auth\RegisterController;
use App\Order;
use App\Traits\RequestTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TelegramController extends Controller
{

    use RequestTrait;


    private $callbackButtons = [
        'popularPizza' => ['1', 'پیتزاهای پرطرفدار'],
        'pizza1' => ['2', 'پیتزاهای تک نفره'],
        'pizza2' => ['3', 'پیتزاهای دو نفره'],
        'sandwiches' => ['4', 'ساندویچ ها'],


    ];

    private $adminButtons = [
        'panel' => 'پنل',
        'addFood' => '➕افزودن غذا',
        'foodNameMsg' => 'لطفا نام غذا و قیمت آن را در فرمت زیر ارسال کنید:'
            . PHP_EOL . 'نام کالا:قیمت',
        'orderList' => 'لیست سفارش ها',
        'exitPanel' => 'خروج از پنل'
    ];

    private $buttons = [
        //basket buttons
        'basket' => '🛒 سبد خرید',
        'emptyBasket' => 'خالی کردن سبد',
        'payment' => '💳 پرداخت',
        'showLastOrder' => 'مشاهده آخرین خرید',

        //order buttons
        'startOrder' => 'سفارش',
        'userInfo' => '🙍‍♂️حساب کاربری',
        'register' => ' ✏️ ثبت نام کاربر جدید',
        'name' => 'نام و نام خانوادگی',
        'addBotToPhone' => 'افزودن ربات به حساب',
        'addAddress' => 'افزودن آدرس',
        'addMap' => '🗺 ارسال موقعیت',
        'sonatiFood' => '🍱 غذای سنتی',
        'fastFood' => '🍔 فست فود',
        'drink' => '🥤 نوشیدنی',
        'salad' => '🥗 سالاد',
        'followOrder' => '☎ ارتباط با ما',
        'contactUs' => '☎ تماس با ما',
        'reward' => '🎁 جایزه',
        'back' => '🔙 بازگشت',
        'finishOrder' => 'پیگیری سفارش',
        'sonatiFoodList' => '🍱 لیست غذاهای سنتی',
        'fastFoodList' => '🍔 لیست فست فود ها',
        'drinksList' => '🥤 لیست نوشیدنی ها',
        'saladsList' => '🥗 لیست سالادها'
    ];

    public function webhook()
    {
        return $this->apiRequest('setWebhook', [
            'url' => str_replace('http', 'https', url(route('webhook'))),
            //'url' => url(route('webhook'))
        ]) ? ['success'] : ['something wrong'];
    }

    /**
     *
     * @throws \Throwable
     */
    public function index()
    {
        $update = json_decode(file_get_contents("php://input"));

        if (isset($update->callback_query)) {
            $callback = $update->callback_query;
            $user = DB::table('users')->where('chat_id', $callback->message->chat->id)->first();

            //Administrator callback Section
            if (isset($user))
                if ($user->type_id === '1' or $user->type_id === '2') {
                    switch ($callback->data) {
                        case 'addFood':
                            $this->apiRequest('sendMessage', [
                                'chat_id' => $callback->message->chat->id,
                                'text' => $this->adminButtons['addFood'] . $user->type_id,
                                'reply_markup' => json_encode([
                                    'force_reply' => true,
                                ])
                            ]);
                            $this->apiRequest('sendMessage', [
                                'chat_id' => $callback->message->chat->id,
                                'text' => $this->adminButtons['foodNameMsg'],
                            ]);
                            break;

                        case 'orderList':

                            $orders = Order::with('user')
                                ->get()
                                ->take(5);
                            /*$orders = DB::table('orders')
                                ->take(5)
                                ->get();
*/
                            foreach ($orders as $order) {
                                $l = null;
                                $basket = unserialize($order->basket);
                                foreach ($basket->items as $item) {
                                    $l = $l . '🔸 ' . $item['qty'] . ' عدد ' . $item['item']->name . ' قیمت ' . $item['item']->price . PHP_EOL;
                                }
                                $payAmount = strcmp('peyk', $order->tavil_type) && $basket->totalPrice < 30000 ? $basket->totalPrice + 4000 : $basket->totalPrice;
                                $this->sendBill($l, $payAmount, $callback, $order, $basket);

                            }
                            break;

                        case 'exitPanel':
                            $this->apiRequest('sendMessage', [
                                'chat_id' => $callback->message->chat->id,
                                'text' => 'شما از پنل خارج شدید',
                                'reply_markup' => json_encode([
                                    'keyboard' => [
                                        [$this->buttons['basket']],
                                        [$this->buttons['fastFood'], $this->buttons['sonatiFood']],
                                        //[$this->buttons['drink'], $this->buttons['salad']],
                                        [$this->buttons['userInfo'], $this->buttons['followOrder'], $this->buttons['reward']],

                                    ],
                                    'resize_keyboard' => true,
                                ]),
                                $this->apiRequest('deleteMessage', [
                                    'chat_id' => $callback->message->chat->id,
                                    'message_id' => $callback->message->message_id,
                                ]),
                            ]);
                            break;
                    }
                }

            switch ($callback->data) {
                case $this->callbackButtons['popularPizza'][0]:
                    $this->apiRequest('answerCallbackQuery', [
                        'callback_query_id' => $callback->id,
                        'text' => 'پیتزاهای پرطرفدار'
                    ]);

                    $this->apiRequest('editMessageText', [
                        'chat_id' => $callback->message->chat->id,
                        'message_id' => $callback->message->message_id,
                        'text' => 'پیتزاهای پرطرفدار',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => $this->showPopularFoods(),
                        ]),
                    ]);
                    break;

                case $this->callbackButtons['pizza1'][0]:
                    $this->apiRequest('answerCallbackQuery', [
                        'callback_query_id' => $callback->id,
                        'text' => 'پیتزاهای تک نفره'
                    ]);

                    $this->apiRequest('editMessageText', [
                        'chat_id' => $callback->message->chat->id,
                        'message_id' => $callback->message->message_id,
                        'text' => 'پیتزاهای تک نفره',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => $this->showFoodListById(1),
                        ]),
                    ]);
                    break;

                case $this->callbackButtons['pizza2'][0]:
                    $this->apiRequest('answerCallbackQuery', [
                        'callback_query_id' => $callback->id,
                        'text' => 'پیتزاهای دو نفره'
                    ]);

                    $this->apiRequest('editMessageText', [
                        'chat_id' => $callback->message->chat->id,
                        'message_id' => $callback->message->message_id,
                        'text' => 'پیتزاهای دو نفره',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => $this->showFoodListById(2),
                        ]),
                    ]);
                    break;

                case $this->callbackButtons['sandwiches'][0]:
                    $this->apiRequest('answerCallbackQuery', [
                        'callback_query_id' => $callback->id,
                        'text' => 'ساندویچ ها'
                    ]);

                    $this->apiRequest('editMessageText', [
                        'chat_id' => $callback->message->chat->id,
                        'message_id' => $callback->message->message_id,
                        'text' => 'ساندویچ ها',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => $this->showFoodListById(5),
                        ]),
                    ]);
                    break;

                case str_start($callback->data, 'food'):
                    $food = Food::find(str_replace('food', '', $callback->data));
                    $this->apiRequest('answerCallbackQuery', array(
                        'callback_query_id' => $callback->id,
                        'text' => $food->name . ' انتخاب شد',
                    ));

                    $this->apiRequest('sendPhoto', array(
                        'chat_id' => $callback->message->chat->id,
                        'photo' => $food->imgTel,
                        'caption' => '*' . $food->name . '*' . PHP_EOL . 'قیمت: ' . $food->price . 'تومان',
                        'parse_mode' => 'markdown',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [
                                    [
                                        'text' => '1 عدد',
                                        'callback_data' => 'num1+food' . $food->id,
                                    ],
                                    [
                                        'text' => '2 عدد',
                                        'callback_data' => 'num2+food' . $food->id
                                    ],
                                    [
                                        'text' => '3 عدد',
                                        'callback_data' => 'num3+food' . $food->id
                                    ],
                                    [
                                        'text' => '4 عدد',
                                        'callback_data' => 'num4+food' . $food->id
                                    ]

                                ],
                                [

                                    [
                                        'text' => '5 عدد',
                                        'callback_data' => 'num5+food' . $food->id
                                    ],
                                    [
                                        'text' => '6 عدد',
                                        'callback_data' => 'num6+food' . $food->id
                                    ],
                                    [
                                        'text' => '7 عدد',
                                        'callback_data' => 'num7+food' . $food->id
                                    ],
                                    [
                                        'text' => 'بازگشت',
                                        'callback_data' => 'back'
                                    ]

                                ]
                            ],
                        ]),
                    ));
                    break;

                case str_start($callback->data, 'num'):
                    $tokens = explode('+', $callback->data);
                    $num = str_replace('num', '', $tokens[0]); //need to be check
                    $foodId = str_replace('food', '', $tokens[1]); //need to be check
                    $food = Food::findOrFail($foodId);
                    $basket_key = $callback->message->chat->id;

                    if (is_null(DB::table('bot_baskets')->where('user_id', $basket_key)->first())) {
                        DB::table('bot_baskets')->insert(['user_id' => $basket_key]);
                    }
                    $basketBot = DB::table('bot_baskets')->where('user_id', $basket_key)->first();


                    $oldBasket = $basketBot->basket ? unserialize($basketBot->basket) : null;
                    $basket = new Basket($oldBasket);
                    $i = 0;
                    while ($i < $num) {
                        $basket->add($food, $food->id);
                        $i++;
                    }

                    DB::table('bot_baskets')
                        ->where('user_id', $basket_key)
                        ->update(['basket' => serialize($basket)]);

                    $this->apiRequest('editMessageCaption', [
                        'chat_id' => $callback->message->chat->id,
                        'message_id' => $callback->message->message_id,
                        'caption' => '✅' . $callback->message->caption . PHP_EOL . ' تعداد ' . $num . ' عدد به سبد اضافه شد.',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [
                                    [
                                        'text' => 'حذف از سبد ❌',
                                        'callback_data' => 'del' . $foodId,
                                    ],
                                ],
                            ],
                        ]),
                    ]);

                    $this->apiRequest('answerCallbackQuery', array(
                        'callback_query_id' => $callback->id,
                        'text' => $num . ' عدد ' . $food->name . ' انتخاب شد',
                    ));


                    break;

                case str_start($callback->data, 'del'):
                    $foodId = str_replace('del', '', $callback->data); //need to be check
                    $food = Food::findOrFail($foodId);
                    $basket_key = $callback->message->chat->id;

                    if (is_null(DB::table('bot_baskets')->where('user_id', $basket_key)->first())) {
                        DB::table('bot_baskets')->insert(['user_id' => $basket_key]);
                    }
                    $basketBot = DB::table('bot_baskets')->where('user_id', $basket_key)->first();


                    $oldBasket = $basketBot->basket ? unserialize($basketBot->basket) : null;

                    $basket = new Basket($oldBasket);
                    $basket->removeItem($foodId);

                    DB::table('bot_baskets')
                        ->where('user_id', $basket_key)
                        ->update(['basket' => serialize($basket)]);

                    $this->apiRequest('editMessageCaption', [
                        'chat_id' => $callback->message->chat->id,
                        'message_id' => $callback->message->message_id,
                        'caption' => '❌'
                            . '*' . $food->name . '*' . PHP_EOL
                            . 'قیمت: ' . $food->price . 'تومان' . PHP_EOL
                            . 'از سبد حذف شد.',
                        'parse_mode' => 'markdown',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [
                                    [
                                        'text' => 'حذف شد',
                                        'callback_data' => 'del' . $foodId,
                                    ],
                                ],
                            ],
                        ]),
                    ]);

                    $this->apiRequest('answerCallbackQuery', array(
                        'callback_query_id' => $callback->id,
                        'text' => $food->name . ' از سبد حذف شد',
                    ));

                    break;

                case $this->buttons['emptyBasket']:

                    $basket_key = $callback->message->chat->id;

                    if (is_null(DB::table('bot_baskets')->where('user_id', $basket_key)->first())) {
                        $this->apiRequest('sendMessage', [
                            'chat_id' => $callback->message->chat->id,
                            'text' => 'سبد خرید شما خالی است',
                            'reply_markup' => json_encode([
                                'keyboard' => [
                                    [$this->buttons['back'], $this->buttons['showLastOrder'],],
                                    [$this->buttons['fastFood'], $this->buttons['sonatiFood'],],
                                ],
                                'resize_keyboard' => true,
                            ]),
                        ]);
                    } else {
                        DB::table('bot_baskets')->where('user_id', $basket_key)->delete();
                        if (DB::table('bot_baskets')->where('user_id', $basket_key)->first()) {
                            $this->apiRequest('sendMessage', [
                                'chat_id' => $callback->message->chat->id,
                                'text' => 'خطا در خالی کردن سبد',
                                'reply_markup' => json_encode([
                                    'keyboard' => [
                                        [$this->buttons['back'], $this->buttons['showLastOrder'], $this->buttons['emptyBasket']],

                                    ],
                                    'resize_keyboard' => true,
                                ]),
                            ]);
                        }
                    }
                    break;

                case 'pay':
                    $botBasket = DB::table('bot_baskets')->where('user_id', $callback->message->chat->id)->first();
                    if (isset($botBasket)) {
                        $basket = unserialize($botBasket->basket);
                        $payAmount = $basket->totalPrice < 30000 ? $payAmount = $basket->totalPrice + 4000 : $payAmount = $basket->totalPrice;
                        $this->apiRequest('sendMessage', array(
                            'chat_id' => $callback->message->chat->id,
                            'text' => 'مبلغ: ' . $payAmount . ' تومان پس تحویل غذا توسط پیک اخذ می گردد. ',
                        ));
                    }
                    break;

                case $this->buttons['showLastOrder']:
                    Order::with($user)
                        ->where('chat_id', $callback->message->chat->id)
                        ->latest('id');
                    $this->apiRequest('sendMessage', array(
                        'chat_id' => $callback->message->chat->id,
                        'text' => 'این امکان به زودی به ربات افزوده خواهد شد.',
                    ));
                    break;
                /*
                                default:
                                    $this->apiRequest('sendMessage', [
                                        'chat_id' => $callback->message->chat->id,
                                        'text' => 'بدون دیتای کال بک',
                                    ]);
                */
            }

        }
        if (isset($update->message)) {
            $message = $update->message;
            $user = DB::table('users')->where('chat_id', $message->chat->id)->first();
            if (isset($message->reply_to_message)) {

                //Administrator Reply to Message Section
                if (isset($user))
                    if ($user->type_id === '1' or $user->type_id === '2') {
                        switch ($message->reply_to_message->text) {
                            case $this->adminButtons['addFood']:
                                $replyText = $message;
                                $tokens = explode(':', $replyText);
                                $foodName = trim($tokens[0]);
                                $foodPrice = trim($tokens[1]);

                                $newFood = new Food();
                                $newFood->name = $foodName;
                                $newFood->price = $foodPrice;
                                $newFood->img = '/img/pizza/default.jpg';
                                $newFood->imgTel = 'AgADBAADeq4xG22VuVJdCfl9efrNEEA6IhsABAtr7cXk1imFR1gDAAEC';
                                $newFood->save();

                                $this->apiRequest('sendMessage', [
                                    'chat_id' => $message->chat->id,
                                    'text' => 'uppic' . $newFood->id,
                                    'reply_markup' => json_encode([
                                        'force_reply' => true,
                                    ]),
                                ]);
                                $this->apiRequest('sendMessage', [
                                    'chat_id' => $message->chat->id,
                                    'text' => 'لطفا عکس غذا را در پاسخ ارسال کنید',
                                ]);
                                break;

                            case str_start($message->reply_to_message->text, 'uppic'):
                                $replyText = $message->reply_to_message->text;
                                $foodId = trim(str_replace('uppic', '', $replyText));
                                if (DB::table('foods')->where('id', $foodId)->first()) {
                                    if (is_array($message->photo)) {
                                        $countPhoto = count($message->photo);
                                        DB::table('foods')
                                            ->where('id', $foodId)
                                            ->update(['imgTel' => $message->photo[$countPhoto - 1]->file_id]);

                                        $this->apiRequest('sendMessage', [
                                            'chat_id' => $message->chat->id,
                                            'text' => $foodId . ' ذخیره شد',
                                        ]);
                                    }

                                }
                                break;

                            default:
                                $this->apiRequest('sendMessage', [
                                    'chat_id' => $message->chat->id,
                                    'text' => 'خطا',
                                ]);
                        }
                    }


                //register user
                if (isset($message->contact)) {
                    $phone = str_replace('98', '0', str_replace('+', '', $message->contact->phone_number));
                    $old = DB::table('users')->where('phone', '09034179396')->first();


                    if (isset($old)) {
                        /*$regRequest = new \Illuminate\Http\Request([
                            'chat_id' => $message->chat->id,
                        ]);
                        redirect()->route('updateProfile', [$regRequest, $old]);

                        $this->apiRequest('sendMessage', [
                            'chat_id' => $message->chat->id,
                            'text' => '⚠️ خطا' . PHP_EOL . PHP_EOL . 'اگر قبلا از طریق سایت ثبت نام کرده اید از گزینه *افزودن ربات به حساب* استفاده کنید در غیر اینصورت با مدیریت سایت تماس بگیرید. ',
                            'parse_mode' => 'markdown',

                        ]);*/
                    } else {
                        $regRequest = new \Illuminate\Http\Request([
                            'phone' => $phone,
                            'chat_id' => $message->chat->id,
                            'name' => $message->contact->first_name,
                            'password' => Hash::make(111111),
                            'active' => 0,
                        ]);

                        $register = new RegisterController();
                        $reg = $register->registerBotUsers($regRequest);
                        if ($reg) {
                            $this->apiRequest('sendMessage', [
                                'chat_id' => $message->chat->id,
                                'text' => 'ثبت نام انجام شد.',
                            ]);
                        } else {
                            $this->apiRequest('sendMessage', [
                                'chat_id' => $message->chat->id,
                                'text' => '⚠️ خطا' . PHP_EOL . PHP_EOL . 'اگر قبلا از طریق سایت ثبت نام کرده اید از گزینه *افزودن ربات به حساب* استفاده کنید در غیر اینصورت با مدیریت سایت تماس بگیرید. ',
                                'parse_mode' => 'markdown',

                            ]);
                        }
                    }
                }
            } elseif (isset($message->text)) {

                //Administrator Message Section
                if (isset($user))
                    if ($user->type_id === '1' or $user->type_id === '2') {
                        switch ($message->text) {
                            case $this->adminButtons['panel']:
                                $this->apiRequest('sendMessage', [
                                    'chat_id' => $message->chat->id,
                                    'text' => 'پنل مدیریت',
                                    'reply_markup' => json_encode([
                                        'inline_keyboard' => [
                                            [
                                                [
                                                    'text' => 'افزودن غذا',
                                                    'callback_data' => 'addFood',
                                                ],
                                            ],
                                            [
                                                [
                                                    'text' => 'لیست سفارش ها',
                                                    'callback_data' => 'orderList',
                                                ],
                                            ],
                                            [
                                                [
                                                    'text' => $this->adminButtons['exitPanel'],
                                                    'callback_data' => 'exitPanel',
                                                ],
                                            ],
                                        ],
                                    ]),
                                ]);
                                break;

                            case $this->adminButtons['orderList']:
                                $this->apiRequest('sendMessage', [
                                    'chat_id' => $message->chat->id,
                                    'text' => 'پنل مدیریت',
                                    'reply_markup' => json_encode([
                                        'inline_keyboard' => [
                                            [
                                                [
                                                    'text' => 'افزودن غذا',
                                                    'callback_data' => 'addFood',
                                                ],
                                            ],
                                            [
                                                [
                                                    'text' => 'لیست سفارش ها',
                                                    'callback_data' => 'panel',
                                                ],
                                            ],
                                        ],
                                    ]),
                                ]);
                                break;
                        }
                    }

                switch ($message->text) {
                    case '/start':
                        $this->apiRequest('sendMessage', [
                            'chat_id' => $message->chat->id,
                            'text' => 'به لانیا خوش آمدید',
                            'reply_markup' => json_encode([
                                'keyboard' => [
                                    [$this->buttons['basket']],
                                    [$this->buttons['fastFood'], $this->buttons['sonatiFood']],
                                    //[$this->buttons['drink'], $this->buttons['salad']],
                                    [$this->buttons['userInfo'], $this->buttons['followOrder'], $this->buttons['reward']],

                                ],
                                'resize_keyboard' => true,
                            ]),

                        ]);
                        break;

                    case $this->buttons['basket']:


                        $basket_key = $message->chat->id;

                        if (is_null(DB::table('bot_baskets')->where('user_id', $basket_key)->first())) {
                            $this->apiRequest('sendMessage', [
                                'chat_id' => $message->chat->id,
                                'text' => 'سبد خرید شما خالی است',
                                'reply_markup' => json_encode([
                                    'keyboard' => [
                                        [$this->buttons['back'], $this->buttons['showLastOrder'],],
                                        [$this->buttons['fastFood'], $this->buttons['sonatiFood'],],

                                    ],
                                    'resize_keyboard' => true,
                                ]),
                            ]);
                        } else {
                            $basketBot = DB::table('bot_baskets')->where('user_id', $basket_key)->first();


                            $oldBasket = $basketBot->basket ? unserialize($basketBot->basket) : null;
                            $l = null;
                            foreach ($oldBasket->items as $item) {
                                $l = $l . '🔸 ' . $item['qty'] . ' عدد ' . $item['item']->name . ' قیمت ' . $item['item']->price . PHP_EOL;
                            }

                            $l = $l . PHP_EOL . PHP_EOL
                                . '💵 جمع کل: ' . $oldBasket->totalPrice
                                . PHP_EOL;

                            $this->apiRequest('sendMessage', [
                                'chat_id' => $message->chat->id,
                                'text' => 'سبد خرید:' . PHP_EOL . PHP_EOL
                                    . $l,
                                'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                        [
                                            [
                                                'text' => $this->buttons['payment'],
                                                'callback_data' => 'pay',
                                            ],
                                        ],
                                        [
                                            [
                                                'text' => $this->buttons['showLastOrder'],
                                                'callback_data' => 'showLastOrder',
                                            ],
                                            [
                                                'text' => $this->buttons['emptyBasket'],
                                                'callback_data' => $this->buttons['emptyBasket'],
                                            ],
                                        ],

                                    ],
                                    /*'keyboard' => [
                                        [$this->buttons['payment']],
                                        [$this->buttons['back'], $this->buttons['showLastOrder'], $this->buttons['emptyBasket']],

                                    ],
                                    'resize_keyboard' => true,*/
                                ]),
                            ]);
                        }
                        break;
                    //case $this->buttons['emptyBasket']:
                    // break;


                    case $this->buttons['sonatiFood']:
                        $this->apiRequest('sendMessage', [
                            'chat_id' => $message->chat->id,
                            'text' => 'این بخش به زودی راه اندازی می گردد'
                                . 'و شما همشهریان عزیز می توانید به صورت آنلاین از جغور بغور اسکندر خرید کنید',
                            'reply_markup' => json_encode([
                                'keyboard' => [
                                    [$this->buttons['sonatiFoodList']],
                                    [$this->buttons['back'], $this->buttons['basket']],
                                ],
                                'resize_keyboard' => true,
                            ]),
                        ]);
                        break;

                    case $this->buttons['sonatiFoodList']:
                        $this->apiRequest('sendMessage', [
                            'chat_id' => $message->chat->id,
                            'text' => 'لیست غذا های سنتی:',
                            'reply_markup' => json_encode([
                                'inline_keyboard' => [
                                    [
                                        [
                                            'text' => 'جغور بغور',
                                            'url' => route('product.addToBasket', ['id' => 2])
                                        ]
                                    ]
                                ],

                            ]),
                        ]);
                        break;

                    case $this->buttons['fastFood']:
                        $this->apiRequest('sendMessage', [
                            'chat_id' => $message->chat->id,
                            'text' => 'فست فود ها',
                            'reply_markup' => json_encode([
                                'keyboard' => [
                                    [$this->buttons['fastFoodList']],
                                    [$this->buttons['saladsList'], $this->buttons['drinksList']],
                                    [$this->buttons['back'], $this->buttons['basket']],
                                ],
                                'resize_keyboard' => true,
                            ]),
                        ]);
                        $this->apiRequest('sendMessage', [
                            'chat_id' => $message->chat->id,
                            'text' => 'لیست فست فودها:',
                            'reply_markup' => json_encode([
                                'inline_keyboard' => [
                                    [
                                        [
                                            'text' => $this->callbackButtons['popularPizza'][1],
                                            'callback_data' => $this->callbackButtons['popularPizza'][0],
                                        ],
                                    ],
                                    [
                                        [
                                            'text' => $this->callbackButtons['pizza1'][1],
                                            'callback_data' => $this->callbackButtons['pizza1'][0],
                                        ],
                                    ],
                                    [
                                        [
                                            'text' => $this->callbackButtons['pizza2'][1],
                                            'callback_data' => $this->callbackButtons['pizza2'][0],
                                        ],
                                    ],
                                    [
                                        [
                                            'text' => $this->callbackButtons['sandwiches'][1],
                                            'callback_data' => $this->callbackButtons['sandwiches'][0],
                                        ]
                                    ]

                                ],

                            ]),
                        ]);
                        break;

                    case $this->buttons['fastFoodList']:
                        $this->apiRequest('sendMessage', [
                            'chat_id' => $message->chat->id,
                            'text' => 'لیست فست فودها:',
                            'reply_markup' => json_encode([
                                'inline_keyboard' => [
                                    [
                                        [
                                            'text' => $this->callbackButtons['popularPizza'][1],
                                            'callback_data' => $this->callbackButtons['popularPizza'][0],
                                        ],
                                    ],
                                    [
                                        [
                                            'text' => $this->callbackButtons['pizza1'][1],
                                            'callback_data' => $this->callbackButtons['pizza1'][0],
                                        ],
                                    ],
                                    [
                                        [
                                            'text' => $this->callbackButtons['pizza2'][1],
                                            'callback_data' => $this->callbackButtons['pizza2'][0],
                                        ],
                                    ],
                                    [
                                        [
                                            'text' => $this->callbackButtons['sandwiches'][1],
                                            'callback_data' => $this->callbackButtons['sandwiches'][0],
                                        ]
                                    ]

                                ],

                            ]),
                        ]);
                        break;

                    case $this->buttons['drink']:
                        $this->apiRequest('sendMessage', [
                            'chat_id' => $message->chat->id,
                            'text' => 'نوشیدنی ها',
                            'reply_markup' => json_encode([
                                'keyboard' => [
                                    [$this->buttons['drinksList']],
                                    [$this->buttons['back'], $this->buttons['basket']],
                                ],
                                'resize_keyboard' => true,
                            ]),
                        ]);

                        $this->apiRequest('sendMessage', [
                            'chat_id' => $message->chat->id,
                            'text' => 'لیست نوشیندنی ها:',
                            'reply_markup' => json_encode([
                                'inline_keyboard' => $this->showFoodListById(3),
                            ]),
                        ]);
                        break;

                    case $this->buttons['drinksList']:
                        $this->apiRequest('sendMessage', [
                            'chat_id' => $message->chat->id,
                            'text' => 'لیست نوشیدنی ها:',
                            'reply_markup' => json_encode([
                                'inline_keyboard' => $this->showFoodListById(3),
                            ]),
                        ]);

                        break;

                    case $this->buttons['salad']:
                        $this->apiRequest('sendMessage', [
                            'chat_id' => $message->chat->id,
                            'text' => 'سالاد ها',
                            'reply_markup' => json_encode([
                                'keyboard' => [
                                    [$this->buttons['saladsList']],
                                    [$this->buttons['back'], $this->buttons['basket']],
                                ],
                                'resize_keyboard' => true,
                            ]),
                        ]);
                        $this->apiRequest('sendMessage', [
                            'chat_id' => $message->chat->id,
                            'text' => 'لیست سالادها:',
                            'reply_markup' => json_encode([
                                'inline_keyboard' => $this->showFoodListById(4),

                            ]),
                        ]);
                        break;

                    case $this->buttons['saladsList']:
                        $this->apiRequest('sendMessage', [
                            'chat_id' => $message->chat->id,
                            'text' => 'لیست سالادها:',
                            'reply_markup' => json_encode([
                                'inline_keyboard' => $this->showFoodListById(4),

                            ]),
                        ]);
                        break;

                    case $this->buttons['userInfo']:

                        $this->apiRequest('sendMessage', [
                            'chat_id' => $message->chat->id,
                            'text' => 'لطفا برای دسترسی به تمام امکانات، ثبت نام کنید'
                                . PHP_EOL,
                            'reply_markup' => json_encode([
                                'keyboard' => [
                                    [
                                        [
                                            'text' => $this->buttons['register'],
                                            'request_contact' => true,


                                        ]
                                    ],
                                    [
                                        [
                                            'text' => $this->buttons['addAddress'],
                                            'request_contact' => true
                                        ],
                                        [
                                            'text' => $this->buttons['addMap'],
                                            'request_location' => true
                                        ]
                                    ],
                                    [$this->buttons['back']],

                                ],
                                'resize_keyboard' => true,
                            ]),
                        ]);
                        break;

                    case $this->buttons['addAddress']:
                        $old = DB::table('users')->where('phone', '09034179396');
                        $regRequest = new \Illuminate\Http\Request([
                            'chat_id' => $message->chat->id,
                        ]);
                        redirect()->route('updateProfile', [$regRequest, $old]);
                        break;

                    case $this->buttons['followOrder']:
                        $this->apiRequest('sendMessage', [
                            'chat_id' => $message->chat->id,
                            'text' => 'پشتیبانی' . PHP_EOL . PHP_EOL
                                . 'تلفن تماس: 09353302945' . PHP_EOL
                                . 'آدرس: زنجان منظریه نبش خ نسیم هشتم شرقی(روبروی آپارتمان های الغدیر)' . PHP_EOL,
                            'reply_markup' => json_encode([
                                'keyboard' => [
                                    [$this->buttons['basket']],
                                    [$this->buttons['fastFood'], $this->buttons['sonatiFood']],
                                    [$this->buttons['userInfo'], $this->buttons['followOrder'], $this->buttons['reward']],

                                ],
                                'resize_keyboard' => true,
                            ]),
                        ]);
                        break;

                    case $this->buttons['reward']:
                        $this->apiRequest('sendMessage', [
                            'chat_id' => $message->chat->id,
                            'text' => '🎁 جوایز' . PHP_EOL
                                . PHP_EOL . '1- پیک رایگان برای خرید های بالای 30 هزار تومان',
                            'reply_markup' => json_encode([
                                'keyboard' => [
                                    [$this->buttons['basket']],
                                    [$this->buttons['fastFood'], $this->buttons['sonatiFood']],
                                    [$this->buttons['userInfo'], $this->buttons['followOrder'], $this->buttons['reward']],

                                ],
                                'resize_keyboard' => true,
                            ]),
                        ]);
                        break;

                    case 'contactUs':
                        $this->apiRequest('sendMessage', [
                            'chat_id' => $message->chat->id,
                            'text' => 'به لانیا خوش آمدید',
                            'reply_markup' => json_encode([
                                'keyboard' => [
                                    [$this->buttons['basket']],
                                    [$this->buttons['fastFood'], $this->buttons['sonatiFood']],
                                    [$this->buttons['drink'], $this->buttons['salad']],
                                    [$this->buttons['userInfo'], $this->buttons['followOrder'], $this->buttons['reward']],

                                ],
                                'resize_keyboard' => true,
                            ]),
                        ]);
                        break;

                    case $this->buttons['back']:
                        $this->apiRequest('sendMessage', [
                            'chat_id' => $message->chat->id,
                            'text' => 'منوی اصلی',
                            'reply_markup' => json_encode([
                                'keyboard' => [
                                    [$this->buttons['basket']],
                                    [$this->buttons['fastFood'], $this->buttons['sonatiFood']],
                                    //[$this->buttons['drink'], $this->buttons['salad']],
                                    [$this->buttons['userInfo'], $this->buttons['followOrder'], $this->buttons['reward']],

                                ],
                                'resize_keyboard' => true,
                            ]),
                        ]);
                        break;
                    /*
                                        default:
                                            $this->apiRequest('sendMessage', [
                                                'chat_id' => $message->chat->id,
                                                'text' => 'دستور ناشناخته',
                                            ]);
                                            break;*/
                }


            }

        }

    }

    private function showFoodListById($id)
    {
        $foods = DB::table('foods')
            ->where('food_type_id', $id)
            ->orderBy('sell_count', 'desc')
            //->take(5)
            ->get();
        $list = array(array());

        if (isset($foods)) {
            foreach ($foods as $food) {
                array_push($list, array(array(
                    'text' => $food->name,
                    'callback_data' => 'food' . $food->id,
                ),));
            }

        }
        return $list;

    }

    private function showPopularFoods()
    {
        $foods = DB::table('foods')
            ->orderBy('sell_count', 'desc')
            ->take(5)
            ->get();
        $list = array(array());

        if (isset($foods)) {
            foreach ($foods as $food) {
                array_push($list, array(array(
                    'text' => $food->name,
                    'callback_data' => 'food' . $food->id,
                ),));
            }

        }
        return $list;

    }


    public function sendBill($l, $payAmount, $callback, $order, $basket)
    {
        $this->apiRequest('sendMessage', [
            'chat_id' => $callback->message->chat->id,
            'text' => '*کد سفارش:* ' . $order->id
                . PHP_EOL . $l
                . PHP_EOL . '*مجموع کل سبد خرید: * ' . $basket->totalPrice
                . PHP_EOL . '🙎‍♂️*مشتری:* ' . $order->user->name
                . PHP_EOL . '🗺*آدرس:* ' . $order->address
                . PHP_EOL . '📞*تلفن:* ' . $order->user->phone
                . PHP_EOL . PHP_EOL . '💳*نوع پرداخت:* ' . $order->pay_type
                . PHP_EOL . '🏍*نوع ارسال:* ' . $order->tahvil_type
                . PHP_EOL . PHP_EOL . '💵*قابل پرداخت:* ' . $payAmount,

            'parse_mode' => 'markdown',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        [
                            'text' => 'سفارش ارسال شد',
                            'callback_data' => 'sendOrder' . $order->id,
                        ],
                    ],
                    [
                        [
                            'text' => 'به اشتراک گذاری سفارش',
                            'url' => 'tg://share?text = www.example.com?t=12',
                        ],
                    ],
                ]
            ])
        ]);

    }

}

