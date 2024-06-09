<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <!-- <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css'> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <!--<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
          integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="/css/Footer-with-button-logo.css">

    <!--Map-->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
          integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
          crossorigin=""/>

    @yield('styles')

</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Center Of Navbar -->
                <!--
                <ul class="navbar-nav mr-auto text-center">
                    <div class="col-lg-12 text-center" style="direction: rtl">
                        <ul class="tabs intab">

                            <li class="tab-link  link_kind_1" onclick="show_user_class(1)" data-tab="tab-1">
                                <i class="fa fa-cutlery"></i>
                                سنتی و محلی
                            </li>
                            <li class="tab-link  link_kind_2" onclick="show_user_class(2)" data-tab="tab-1">
                                <i class="fa fa-cutlery"></i>
                                کافه
                            </li>
                            <li class="tab-link  link_kind_3" onclick="show_user_class(3)" data-tab="tab-1">
                                <i class="fa fa-cutlery"></i>
                                صبحانه، عصرانه و شبانه
                            </li>
                            <li class="tab-link  link_kind_4" onclick="show_user_class(4)" data-tab="tab-1">
                                <i class="fa fa-cutlery"></i>
                                فست فود
                            </li>
                            <li class="tab-link  link_kind_5" onclick="show_user_class(5)" data-tab="tab-1">
                                <i class="fa fa-cutlery"></i>
                                میوه
                            </li>
                            <li class="tab-link  link_kind_6" onclick="show_user_class(6)" data-tab="tab-1">
                                <i class="fa fa-cutlery"></i>
                                شیرینی
                            </li>
                            <li class="tab-link  link_kind_7" onclick="show_user_class(7)" data-tab="tab-1">
                                <i class="fa fa-cutlery"></i>
                                سوپرمارکت
                            </li>

                        </ul>
                    </div>
                </ul>
                -->
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('ورود') }}</a>
                        </li>
                        <li class="nav-item">
                            @if (Route::has('register'))
                                <a class="nav-link" href="{{ route('register') }}">{{ __('ثبت نام') }}</a>
                            @endif
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right text-right" style="direction: rtl"
                                 aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('profile') }}"><i class="fa fa-user-circle"></i>
                                    {{ __('حساب کاربری') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('basket') }}"><i
                                            class="fa fa-shopping-cart"></i>
                                    {{ __('سبد خرید') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out"></i>
                                    {{ __('خروج') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">


        @yield('content')

    </div>
</div>
<footer id="myFooter">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <div id="mapid"></div>
            </div>
            <div class="col-sm-2">
                <h5>{{__('تازه ها')}}</h5>
                <ul>
                    <li><a href="#">{{__('افتتاح سیستم آنلاین لانیا')}}</a></li>
                    <li><a href="#">{{__('پیک رایگان')}}</a></li>
                </ul>
            </div>
            <div class="col-sm-4">
                <h5>{{__('ارتباط با ما')}}</h5>
                <ul>
                    <li>
                        <i class="fas fa-phone-square" style="color: #00aced;padding: 2px"></i>
                        <span style="color: #00aced;padding: 3px">{{__('تلفن سفارش: 09353302945')}}</span>
                    </li>
                    <li>
                        <i class="fab fa-telegram-plane" style="color: #00aced;padding: 2px"></i>
                        <span style="color: #00aced;padding: 3px">{{__('پشتیبانی تلگرام: 09353302945')}}</span>
                    </li>
                    <li>
                        <i class="fas fa-map-marked-alt" style="color: #00aced;padding: 2px"></i>
                        <span style="color: #00aced;padding: 3px">{{__('آدرس: زنجان منظریه نبش خ نسیم هشتم شرقی')}}
                            <br>{{__('(روبروی آپارتمان های الغدیر)')}}</span>
                    </li>


                </ul>
            </div>
            <!--
            <div class="col-sm-2">
                <h5>پشتیباتی</h5>
                <ul>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Help desk</a></li>
                    <li><a href="#">Forums</a></li>
                </ul>
            </div>-->
            <div class="col-sm-3">
                <div class="social-networks">
                    <a href="https://www.instagram.com/lania_online" class="instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="robot"><i class="fas fa-robot"></i></a>
                    <a href="https://bit.ly/2Td0NNU" class="telegram"><i class="fab fa-telegram-plane"></i></a>
                </div>
                <div class="text-center">
                    <script src="https://www.zarinpal.com/webservice/TrustCode" type="text/javascript"></script>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <p>
            {{__('طراحی و پیاده سازی شده با ')}}<i class="fa fa-heart" style="color: red"></i>{{__(' در لانیا')}}
            <br>
            {{__('© حقوق کلیه نوشته‌ها و مطالب این سایت برای لانبا محفوظ است.')}}
        </p>
    </div>
</footer>
</body>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js"
        integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em"
        crossorigin="anonymous"></script>

<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js'></script>

<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"
        integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
        crossorigin=""></script>

<script>
    var mymap = L.map('mapid').setView([36.6789641, 48.4820785], 11);
    var marker = L.marker([36.6693632, 48.5364052]).addTo(mymap);
    marker.bindPopup("فست فود لانبا").openPopup();


    /*
    mymap.locate({setView: true,timeout: 10000, maxZoom: 11});

    function onLocationFound(e) {
        var radius = e.accuracy / 2;

        L.marker(e.latlng).addTo(mymap)
            .bindPopup("You are within " + radius + " meters from this point").openPopup();

        L.circle(e.latlng, radius).addTo(mymap);
    }

    mymap.on('locationfound', onLocationFound);


    function onLocationError(e) {
        alert(e.message);
    }

    mymap.on('locationerror', onLocationError);
*/

    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoiZmFyc2hhZGZhcmFtYXJ6bG91IiwiYSI6ImNqcGF4NGlqdjAzYTMzcW5yc3VlNjVlbHAifQ.iNe1q0mhsMXaAbZUfeY-hw', {
        maxZoom: 18,
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
            '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
            'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        id: 'mapbox.streets'
    }).addTo(mymap);


</script>


@yield('scripts');
</html>
