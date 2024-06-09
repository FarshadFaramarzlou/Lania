@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="/css/owl.theme.default.css">
    <link rel='stylesheet' href='/css/owl.carousel.css'>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

@endsection
@section('content')

    <div class="pull-right col-lg-9 col-md-9 col-sm-7 col-xs-12">
        <div class="text-right card-header">
            <p style="font-size: 24px">{{ __('پیتزا های تک نفره') }}</p>
        </div>
        <div class="card-body">
            <!--foods sugests-->
            <div class="row">
                <div class="owl-carousel owl-theme">
                    @foreach($foods[1]->foods as $food)
                        <div class="item text-right">
                            <img class="img-responsive" src="@if($food->img != null){{$food->img}}@endif">
                            <div class="caption">
                                <h5>{{$food->name}}</h5>
                                <div class="clearfix">
                                    <div class="pull-left price">{{$food->price}}</div>
                                    <a href="{{route('product.addToBasket',['id'=>$food->id])}}"
                                       class="btn btn-success pull-right">افزودن</a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
        <br>
        <br>

        <!-- drink sugests -->
        <div class="text-right card-header">
            <p style="font-size: 24px">{{ __('پیتزا های دو نفره') }}</p>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="owl-carousel owl-theme">
                    @foreach($foods[2]->foods as $food)
                        <div class="item text-right ">
                            <img class="img-responsive" src="@if($food->img != null){{$food->img}}@endif">
                            <div class="caption">
                                <div class="clearfix">
                                    <h5>{{$food->name}}</h5>
                                    <div class="pull-left price">{{$food->price}}</div>
                                    <a href="{{route('product.addToBasket',['id'=>$food->id])}}" class="btn btn-success pull-right" role="button">افزودن</a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
        <!-- drink sugests -->
        <div class="text-right card-header">
            <p style="font-size: 24px">{{__('نوشیدنی ها و سالادها')}}</p>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="owl-carousel owl-theme">
                    @foreach($foods[4]->foods as $food)
                        <div class="item text-right ">
                            <img class="img-responsive" src="@if($food->img != null){{$food->img}}@endif">
                            <div class="caption">
                                <div class="clearfix">
                                    <h5>{{$food->name}}</h5>
                                    <div class="pull-left price">{{$food->price}}</div>
                                    <a href="{{route('product.addToBasket',['id'=>$food->id])}}" class="btn btn-success pull-right" role="button">افزودن</a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-5 col-xs-12">
        <div class="cart">
            <div class="title"><a href="{{route('basket')}}" style="color: #FFFFFF"><i class="fa fa-shopping-cart"
                                                                                       aria-hidden="true"></i><h4>سبد
                        خرید</h4></a>

                @inject('myBasket', '\Illuminate\Support\Facades\Session')
                <span class="badge">{{$myBasket::has('basket')?$myBasket::get('basket')->totalQty:''}}</span>

            </div>

            @if($myBasket::has('basket'))
                <ul class="plus" id="pishfaktor">
                    @foreach($products as $product)
                        <li>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <input type="hidden"
                                       name="order_menuid_5117"
                                       id="inp5117"
                                       value="">
                                {{$product['item']->name}}
                                <div class="price"><span
                                            id="price5117">{{$product['item']->price}}</span>
                                    تومان
                                </div>
                            </div>


                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 num">
                                <a href="{{route('product.addToBasket',['id'=>$product['item']->id])}}"
                                   onclick="increase(5117)"><i
                                            class="fa fa-plus-circle green" aria-hidden="true"></i>
                                </a>
                                <span id="ted5117">{{$product['qty']}}</span>
                                <a href="{{route('product.dropFromBasket',['id'=>$product['item']->id])}}"
                                   onclick="decrease(5117)"><i
                                            class="fa fa-minus-circle red" aria-hidden="true"></i>
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
            <ul class="plus2">

                <li>
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">جمع مبلغ سفارش</div>
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                        <div class="prc">
                                            <span id="majmoo" class="badge"
                                                  style="display:none ">{{Session::has('basket') ? Session::get('basket')->totalPrice:'0'}}</span>
                            <span id="majmoo_show">{{Session::has('basket') ? Session::get('basket')->totalPrice:'0'}}</span>

                            تومان
                        </div>
                    </div>
                </li>
                <li class="red">
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">تخفیف (0%)</div>
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                        <div class="prc"><span id="total_takhfif">0</span> تومان</div>
                    </div>
                </li>
                <li>
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">هزینه ارسال</div>
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                        <div class="prc">
                            <span id="ptime" style="display:none"></span>
                            <span id="pprice">{{Session::has('basket') ? Session::get('basket')->totalPrice<30000?'4000':'0':'0'}}</span>
                            تومان
                        </div>
                    </div>
                </li>

                <li class="red" id="place_delivery_price_takhfif" style="display:none">
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">تخفیف ارسال</div>
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                        <div class="prc"><span id="total_delivery_price_takhfif">0</span> تومان</div>
                    </div>
                </li>


                <li>
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">مالیات بر ارزش افزوده</div>
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                        <div class="prc">
                            <span id="vat">0</span>
                            تومان
                        </div>
                    </div>
                </li>
                <li>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 free">هزینه ارسال بالا 30000
                        تومان
                        رایگان
                    </div>
                </li>
                <li class="bld">
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">مبلغ قابل پرداخت</div>
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                        <div class="prc">

                            <span id="majmookol">{{Session::has('basket') ? Session::get('basket')->totalPrice<30000?Session::get('basket')->totalPrice+4000:Session::get('basket')->totalPrice:'0'}}</span>

                            تومان
                        </div>
                    </div>
                </li>

                <form action="{{route('basket')}}" method="post" id="order_basket">
                    @csrf
                    <div id="customer-input" hidden>

                        <li style="direction: rtl;text-align: right">
                            <i class="fa fa-phone" aria-hidden="true" style="font-size:16px;color:red;margin: 5px"></i>تلفن
                            تماس:
                            <input name="phone" id="phone"
                                   style="border: 2px solid red;border-radius: 4px;margin-bottom: 7px"
                                   type="text"
                                   class="form-control bfh-phone" data-format="+98 (ddd) ddd-dddd">

                        </li>

                    </div>
                </form>
                <li>

                    <div id="completeOrderBtn"
                         class="col-lg-12 col-md-12 col-sm-12 col-xs-12 btn submit_btn_success"
                         style="background-color: #01AE1E;font-size: 13px; text-shadow:#FFFFFF; text-align:center"
                         onclick="{{Auth::check()?'submitForm()':'showCusInputs()'}}">
                        <span id="orderBtnName">{{Auth::check()?'ثبت نهایی و ارسال سفارش':'تکمیل خرید'}}</span>
                    </div>
                </li>
                <li>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 limit">حداقل سفارش ۲۰,۰۰۰ تومان
                    </div>
                </li>

                <li>

                </li>

            </ul>
        </div>


    </div>
@endsection
@section('scripts')


    <!-- owl-carousel -->
    <script src='/js/owl.carousel.js'></script>
    <script>
        $('.owl-carousel').owlCarousel({
            rtl: true,
            lazyLoad: true,
            loop: true,
            margin: 20,
            nav: true,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 2
                },
                1000: {
                    items: 4
                }
            }
        })
    </script>

    <!-- register order button -->
    <script>
        function showCusInputs() {
                    @unless(Auth::check())
            var customerInfoInputs = document.getElementById("customer-input");
            customerInfoInputs.removeAttribute('hidden');

            var orderBtn = document.getElementById("completeOrderBtn");
            orderBtn.style.backgroundColor = "#09AE1E";

            orderBtn.removeAttribute("onclick");
            orderBtn.setAttribute("onclick", "submitForm()");

            var btnName = document.getElementById("orderBtnName");
            btnName.textContent = "ثبت نهایی و ارسال سفارش";
            @endunless
        }

        function submitForm() {
            document.getElementById('order_basket').submit();
        }
    </script>
@endsection

