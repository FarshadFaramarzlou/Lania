@extends('layouts.app')

@section('menu')
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
@endsection
