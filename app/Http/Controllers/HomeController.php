<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        return view('layouts.profile');
    }


    public function updateProfile(User $user, Request $request)
    {
        //if (Gate::denies('update-profile', $user))
        //  abort(403, 'این صفحه متعلق به شما نمی باشد.');

        if ($request->hasFile('imageUpload')) {
            $image = $request->file('imageUpload');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(300, 300)->save(public_path('/uploads/avatars/' . $filename));

            $user->avatar = $filename;

        }
        $user->update($request->all());
        $user->update(['password' => Hash::make($request->input('password'))]);
        Session::flash('flash_message', 'پروفایل به روز گردید');
        return back();
    }
}
