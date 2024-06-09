<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\SendCode;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/verify';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */


    public function register(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return Redirect::to('login')->withInput();
        } else {
            $validator->validate();
        }
        event(new Registered($user = $this->create($request->all())));
        return $this->registered($request, $user) ? route('basket') : redirect('verify?phone=' . $request->phone);

    }


    public function registerBotUsers(Request $request)
    {
        try {
            $this->validate($request, [
                'chat_id' => 'required',
                'name' => 'max:255',
                'phone' => 'required|max:11|min:10|unique:users'
            ]);
        } catch (\Exception $exception) {
            return false;
        }
        $user = new User([
            'phone' => $request->input('phone'),
            'name' => $request->input('name'),
            'password' => $request->input('password'),
            'active' => $request->input('active'),
            'chat_id' => $request->input('chat_id'),
        ]);
        return $user->save();

        try {
            //return $user->saveOrFail();
        } catch (\Throwable $e) {
        }//$this->registered($request, $user) ? true : false;

    }


    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'max:255',
            'phone' => 'required|max:11|min:10|unique:users',
            //'password' => 'string|min:6',
        ]);

        return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'password' => Hash::make(111111),
            'active' => 0,
            'type_id' => '3',
        ]);
        if ($user) {
            $user->code = SendCode::sendCode($user->phone);
            $user->password = Hash::make($user->code);
            $user->save();
        }

    }

    protected function verifyCode(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'verify_code' => $data['verify_code'],
            'password' => Hash::make($data['password']),
            'active' => 1,
            'type_id' => '3',

        ]);


    }
}
