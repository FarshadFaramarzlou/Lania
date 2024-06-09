@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="/css/owl.theme.default.css">
    <link rel='stylesheet' href='/css/owl.carousel.css'>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

@endsection
@section('content')
    <form action="{{route('do.order')}}" method="post" id="order_basket">
        @csrf
        <div class="pull-right col-lg-9 col-md-9 col-sm-7 col-xs-12">
            @if(Session::has('warning'))
                <div class="alert alert-danger alert-dismissible fade show text-right" role="alert">
                    {{Session::get('warning')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
                @if(Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade show text-right" role="alert">
                        {{Session::get('success')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
            <div class="row card-header"
                 style="direction: rtl;text-align: right;background-color: gray;border-radius: 2px">
                <span>{{ __('سبد خرید') }}</span>
            </div>
            <div class="card-body">
                @inject('myBasket', '\Illuminate\Support\Facades\Session')

                @if($myBasket::has('basket'))
                    <ul class="col-lg-12 mb-0">
                        @foreach($products as $product)
                            <li class="col-lg-12 pull-right text-right"
                                style="margin-bottom: 15px;border-radius: 2px;display:block;border: #7b8a8b 1px solid;padding: 7px">
                                <div>
                                    <div class="col-lg-3">
                                        <a href=""
                                           target="_blank">
                                            <img height="115"
                                                 alt="{{$product['item']->name}}"
                                                 src="@if($product['item']->img != null){{$product['item']->img}}@endif">
                                        </a>
                                        <a href="{{route('product.removeFromBasket',['id'=>$product['item']->id])}}">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </div>
                                    <div class="col-lg-5">
                                        <a href="#" target="_blank">
                                            <h3 class="">{{$product['item']->name}}</h3>
                                        </a>
                                        <p>
                                            فروشنده: لانیا
                                        </p>
                                        <p class="">مخلفات: پنیر پیتزا- قارچ</p>
                                        <!--
                                        <div class="">
                                        <span class="c-checkout__variant-title">رنگ :
                                        </span>
                                            <span class="c-checkout__variant-value">
                                            مشکی
                                            <div class="col-lg-3" style="background-color:#212121">

                                            </div>
                                        </span>
                                        </div>
                                        -->
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="">
                                            <div class="col-lg-5 text-center" style="padding: 12px;margin: 0px">
                                                <label for="count-0">تعداد</label>
                                                <div class="num">
                                                    <a href="{{route('product.addToBasket',['id'=>$product['item']->id])}}">
                                                        <i class="fa fa-plus-circle green" aria-hidden="true"></i>
                                                    </a>
                                                    <span>{{$product['qty']}}</span>
                                                    <a href="{{route('product.dropFromBasket',['id'=>$product['item']->id])}}">
                                                        <i class="fa fa-minus-circle red" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <div class="">{{$product['item']->price}}
                                                    تومان
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <ul class="col-lg-12 mb-0 text-right">
                        <h5>سبد خرید شما خالی است! برای انتخاب غذا <a class="btn btn-success" href="{{route('index')}}">اینجا</a> کلیک کنید</h5>
                    </ul>
                @endif


            </div>

            @if(Auth::check())
                <div class="row card-header"
                     style="direction: rtl;text-align: right;background-color: gray;border-radius: 2px">
                    <span>{{ __('خریدار') }}</span>
                </div>
                <div class="card-body text-right" style="direction: rtl">
                    <div class="form-group">
                        <span style="font-size: larger"><b>{{__('گیرنده: ')}}</b>{{is_null(Auth::user()->name)?'مشخصات کاربری تکمیل نیست.':Auth::user()->name}}</span>
                    </div>
                    <div class="form-group">
                        <span style="font-size: larger"><b>{{__('تلفن تماس: ')}}</b>{{is_null(Auth::user()->phone)?'تلفن تماس وارد نشده است.':Auth::user()->phone}}</span>
                    </div>

                    <div class="form-group">
                        <span style="font-size: larger"><b>{{__('آدرس: ')}}</b>{{is_null(Auth::user()->address)?'تکمیل نشده':Auth::user()->address}}</span>

                    </div>
                </div>

                <div class="row card-header"
                     style="direction: rtl;text-align: right;background-color: gray;border-radius: 2px">
                    <span>{{ __('نحوه پرداخت') }}</span>
                </div>
                <div class="card-body">
                    <div class="row form-group text-right" style="direction: rtl">
                        <div class="col-4">
                            <input id="cash" name="pay_type" type="radio" checked="checked"
                                   value="cash"><span>{{__('درب منزل (پول نقد)')}}</span>
                        </div>
                        <div class="col-4">
                            <input id="pos" name="pay_type" type="radio"
                                   value="pos"><span>{{__('درب منزل (کارت خوان)')}}</span>
                        </div>
                        <div class="col-4">
                            <input id="zarrin" name="pay_type" type="radio"
                                   value="zarrin"><span>{{__('پرداخت آنلاین(درگاه زرین پال)')}}</span>
                        </div>

                    </div>
                </div>

                <div class="row card-header"
                     style="direction: rtl;text-align: right;background-color: gray;border-radius: 2px">
                    <span>{{ __('نحوه تحویل سفارش') }}</span>
                </div>
                <div class="card-body">
                    <div class="row form-group text-right" style="direction: rtl">
                        <div class="col-4">
                            <input id="peyk" name="tahvil_type" type="radio" checked="checked"
                                   value="peyk"><span>{{__('پیک موتوری(خرید بالای 40 تومان رایگان)')}}</span>
                        </div>
                        <div class="col-4">
                            <input id="mySelf" name="tahvil_type" type="radio"
                                   value="mySelf"><span>{{__('خودم میام میبرم')}}</span>
                        </div>

                    </div>
                </div>
            @endif

        </div>
        <div class="col-lg-3 col-md-3 col-sm-5 col-xs-12">
            <div class="cart">
                <div class="title"><i class="fa fa-shopping-cart" aria-hidden="true"></i><h4>سبد خرید</h4>

                    @inject('myBasket', '\Illuminate\Support\Facades\Session')
                    <span class="badge">{{$myBasket::has('basket')?$myBasket::get('basket')->totalQty:''}}</span>

                </div>


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
                                <span id="pprice">۰</span> تومان
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

                                <span id="majmookol">{{Session::has('basket') ? Session::get('basket')->totalPrice:'0'}}</span>

                                تومان
                            </div>
                        </div>
                    </li>


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


                </ul>
            </div>


        </div>
    </form>

@endsection
@section('scripts')

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

