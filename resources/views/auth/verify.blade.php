@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card text-right">
                    <div class="card-header">{{ __('تایید شماره موبایل') }}</div>

                    <div class="card-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('یک کد جدید به ایمیل شما ارسال شد.') }}
                            </div>
                        @endif


                        <form method="POST" action="{{ route('verify') }}">
                            @csrf

                            <div class="form-group row" style="direction: rtl">
                                <label for="code"
                                       class="col-sm-3 col-form-label text-md-right">{{ __('کد فعال سازی') }}</label>

                                <div class="col-md-6">
                                    <input id="code" type="code"
                                           class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}"
                                           name="code" value="{{ old('code') }}" required autofocus>

                                    @if ($errors->has('code'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('code') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-4" style="direction: rtl">
                                <div class="col-md-8 offset-md-1">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('تایید') }}
                                    </button>

                                    <a href="{{ route('login') }}">{{ __('درخواست کد جدید') }}</a>.

                                </div>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
