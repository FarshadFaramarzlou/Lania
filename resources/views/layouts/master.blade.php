<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">


    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css'>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">


    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>


    <!--Map-->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
          integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
          crossorigin=""/>

@yield('styles')
<!--Leaflet Map -->

    <style rel="stylesheet">
        ul.tabs.intab {
            margin: 35px 0 20px;
            padding: 0px;
            list-style: none;
        }

        ul.tabs.intab li {
            background: #FFF;
            box-shadow: 0 3px 10px #ebebeb;
            border-radius: 50px;
            height: 30px;
            line-height: 30px;
            color: #222;
            display: inline-block;
            padding: 0 10px;
            margin: 0 8px;
            cursor: pointer;
        }

        ul.tabs.intab li.current {
            background: #fe3b5b;
            box-shadow: 0 3px 10px #ffb7c3;
            color: #fff;
        }

        .tab-content.intab {
            display: none;
        }

        .tab-content.intab.current {
            display: inherit;
        }

        #mapid {
            height: 180px;
        }


    </style>

</head>
<body>


<div class="flex-center position-ref full-height ">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endauth
        </div>
    @endif
</div>

<div class="container">


    <!-- Just an image
    <div class="col-lg-12">
        <a class="" href="#">
            <img class="col-lg-12" src="../img/banner.jpg" alt="">
        </a>
    </div>-->
    <!-- TESTIMONIALS -->
    <div class="pull-right col-lg-9 col-md-9 col-sm-7 col-xs-12">
        @yield('middle')
    </div>

    <div class="col-lg-3 col-md-3 col-sm-5 col-xs-12">
        @yield('leftSide')
    </div>


</div>

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


@yield('scripts')
</html>