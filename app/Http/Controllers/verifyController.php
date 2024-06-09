<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class verifyController extends Controller
{
    public function getVerify()
    {
        return view('auth.verify');
    }

    public function postVerify(Request $request)
    {
        if ($user = User::where('code', $request->code)->first()) {
            $user->active = 1;
            $user->code = null;
            $user->save();
            Auth::login($user, true);
            return redirect()->route('profile')->withMessage('Your Account is Actived.');
        } else {
            return back()->withMessage('Verify Code is not Correct,Please try again.');
        }
    }
}
