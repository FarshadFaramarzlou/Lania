@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="direction: rtl;text-align: right">{{ __('ورود کاربر') }}</div>

                    <div class="card-body" style="direction: rtl">
                        @if(\Illuminate\Support\Facades\Session::has('message'))
                            <div class="alert alert-success">{{\Illuminate\Support\Facades\Session::get('message')}}</div>
                        @endif


                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="phone"
                                       class="col-sm-4 col-form-label text-right text-sm-left text-lg-left text-xl-left text-md-left">{{ __('نام کاربری/موبایل') }}</label>

                                <div class="col-md-6">
                                    <input id="phone" type="phone" placeholder="09xxxxxxxxx"
                                           class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                           name="phone" value="{{ old('phone') }}" required autofocus>

                                    @if ($errors->has('phone'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password"
                                       class="col-sm-4 col-form-label text-right text-sm-left text-lg-left text-xl-left text-md-left">{{ __('رمز عبور/کد فعال سازی') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                           name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-0 text-right text-md-left">
                                <div class="col-12 col-lg-8 col-md-10 col-xl-8 col-sm-8">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('ورود') }}
                                    </button>
                                    <a href="{{route('register')}}" class="btn btn-primary">
                                        {{ __('ثبت نام') }}
                                    </a>

                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('فراموشی رمز عبور') }}
                                    </a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
