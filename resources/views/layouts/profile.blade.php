@extends('layouts.app')
@section('styles')
    <style>
        .avatar-upload {
            position: relative;
            max-width: 205px;
            margin: 50px auto;
        }

        .avatar-upload .avatar-edit {
            position: absolute;
            right: 12px;
            z-index: 1;
            top: 10px;
        }

        .avatar-upload .avatar-edit input {
            display: none;
        }

        .avatar-upload .avatar-edit input + label {
            display: inline-block;
            width: 34px;
            height: 34px;
            margin-bottom: 0;
            border-radius: 100%;
            background: #FFFFFF;
            border: 1px solid transparent;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
            cursor: pointer;
            font-weight: normal;
            transition: all 0.2s ease-in-out;
        }

        .avatar-upload .avatar-edit input + label:after {
            content: "\f040";
            font-family: 'FontAwesome';
            color: #757575;
            position: absolute;
            top: 10px;
            left: 0;
            right: 0;
            text-align: center;
            margin: auto;
        }

        .avatar-upload .avatar-edit input + label:after body {
            background: whitesmoke;
            font-family: "Open Sans", sans-serif;
        }

        .avatar-upload .avatar-edit input + label:after .container {
            max-width: 960px;
            margin: 30px auto;
            padding: 20px;
        }

        .avatar-upload .avatar-edit input + label:after h1 {
            font-size: 20px;
            text-align: center;
            margin: 20px 0 20px;
        }

        .avatar-upload .avatar-edit input + label:after h1 small {
            display: block;
            font-size: 15px;
            padding-top: 8px;
            color: gray;
        }

        .avatar-upload .avatar-edit input + label:after .avatar-upload {
            position: relative;
            max-width: 205px;
            margin: 50px auto;
        }

        .avatar-upload .avatar-edit input + label:after .avatar-upload .avatar-edit {
            position: absolute;
            right: 12px;
            z-index: 1;
            top: 10px;
        }

        .avatar-upload .avatar-edit input + label:after .avatar-upload .avatar-edit input {
            display: none;
        }

        .avatar-upload .avatar-edit input + label:after .avatar-upload .avatar-edit input + label {
            display: inline-block;
            width: 34px;
            height: 34px;
            margin-bottom: 0;
            border-radius: 100%;
            background: #ffffff;
            border: 1px solid transparent;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
            cursor: pointer;
            font-weight: normal;
            transition: all 0.2s ease-in-out;
        }

        .avatar-upload .avatar-edit input + label:after .avatar-upload .avatar-edit input + label:hover {
            background: #f1f1f1;
            border-color: #d6d6d6;
        }

        .avatar-upload .avatar-edit input + label:after .avatar-upload .avatar-edit input + label:after {
            content: "\f040";
            font-family: "FontAwesome";
            color: #757575;
            position: absolute;
            top: 10px;
            left: 0;
            right: 0;
            text-align: center;
            margin: auto;
        }

        .avatar-upload .avatar-edit input + label:after .avatar-upload .avatar-preview {
            width: 192px;
            height: 192px;
            position: relative;
            border-radius: 100%;
            border: 6px solid #f8f8f8;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
        }

        .avatar-upload .avatar-edit input + label:after .avatar-upload .avatar-preview > div {
            width: 100%;
            height: 100%;
            border-radius: 100%;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }

        .avatar-upload .avatar-preview {
            width: 192px;
            height: 192px;
            position: relative;
            border-radius: 100%;
            border: 6px solid #F8F8F8;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
        }

        .avatar-upload .avatar-preview > div {
            width: 100%;
            height: 100%;
            border-radius: 100%;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }

    </style>

@section('content')
    <div class="container" style="direction: rtl">
        @if(Session::has('flash_message'))
            <div class="alert alert-success alert-dismissible fade show text-right" role="alert">
                {{Session::get('flash_message')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <form action="{{route('updateProfile',[Auth::user()->id])}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h3> ویرایش پروفایل</h3>

            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="input-group mb-4 ">
                            <div class="input-group-prepend" for="name">
                                <label class="input-group-text">نام و نام خانوادگی</label>
                            </div>
                            <input id="name" name="name" class="form-control" type="text"
                                   value="{{Auth::check()?Auth::user()->name:''}}">
                        </div>


                        <div class="input-group mb-4">
                            <div class="input-group-prepend" for="email">
                                <label class="input-group-text">ایمیل/نام کاربری</label>
                            </div>

                            <input id="phone" name="phone" class="form-control" type="tel"
                                   value="{{Auth::check()?Auth::user()->phone:''}}">
                        </div>


                        <div class="input-group mb-4">
                            <div class="input-group-prepend">
                                <label class="input-group-text{{ $errors->has('password') ? ' is-invalid' : '' }}" for="password">{{__('رمز عبور')}}</label>
                            </div>
                            <input id="password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" type="text"
                                   placeholder="{{__('مخفی')}}" value="{{ old('password')}}">
                        </div>


                        <div class="input-group mb-4">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="address">آدرس</label>
                            </div>
                            <input id="address" name="address" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" type="text"
                                   value="{{Auth::check()?Auth::user()->address:''}}">
                        </div>


                        <div class="input-group mb-4">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="tel">تلفن</label>
                            </div>
                            <input id="tel" name="tel" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" type="tel"
                                   value="{{Auth::check()?Auth::user()->phone:''}}">
                        </div>
                    </div>

                <!--<div class="col-md-4">
                        <div class="avatar-upload">
                            <div class="avatar-edit">
                                <input type="file" name="imageUpload" id="imageUpload" accept="image/jpeg"/>
                                <label for="imageUpload"></label>
                            </div>
                            <div class="avatar-preview">
                                <div id="imagePreview"
                                     style="background-image: url('{{asset('/uploads/avatars/default.jpg')}}')">
                            </div>
                        </div>
                    </div>
                </div>-->

                </div>
            </div>
            <div class="modal-footer">
                <a href="{{route('index')}}" class="btn btn-secondary" data-dismiss="modal">بستن</a>
                <button type="submit" class="btn btn-primary">ذخیره</button>
            </div>
        </form>
    </div>

@endsection