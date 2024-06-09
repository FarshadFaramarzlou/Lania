<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\SendCode;
use App\Traits\RequestTrait;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    use RequestTrait;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/profile';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        if ($this->guard()->validate($this->credentials($request))) {
            $user = $this->guard()->getLastAttempted();
            if ($user->active && $this->attemptLogin($request)) {
                $this->sendBotNotification($request);
                $this->redirectTo = route('basket');
                return $this->sendLoginResponse($request);
            } else {
                $this->incrementLoginAttempts($request);
                $user->code = SendCode::sendCode($user->phone);
                if ($user->save()) {
                    return redirect('/verify?phone=' . $user->phone);
                }
            }
        }
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);

    }


    public function sendBotNotification(Request $request)
    {
        if (isset(Auth::user()->chat_id))
            $this->apiRequest('sendMessage', [
                'chat_id' => Auth::user()->chat_id,//Your Bot Chat_ID
                'text' => Auth::user()->name . ' عزیز'
                    . "\n"
                    . 'ورود موفقیت آمیز به حساب کاربری '
                    . Auth::user()->phone
                    . "\n"
                    . 'در تاریخ'
                    . Carbon::now()
                    . 'با آی پی '
                    . $request->ip()
                    . 'صورت پذیرفت.',
            ]);

    }
}
