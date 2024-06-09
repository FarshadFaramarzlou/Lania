<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [
    'uses' => 'MainPage@index',
    'as' => 'index',
]);

Route::get('/basket', [
    'uses' => 'BasketController@index',
    'as' => 'basket',
]);

Route::post('/basket', [
    'uses' => 'BasketController@index',
    'as' => 'basket',
]);

Route::get('/add-to-basket/{id}', [
    'uses' => 'BasketController@getAddToBasket',
    'as' => 'product.addToBasket',
]);

Route::get('/add-to-basket/{id}/{basket_key}', [
    'uses' => 'BasketController@getAddToBasketBot',
    'as' => 'product.addToBasketBot',
]);

Route::get('/drop-from-basket/{id}', [
    'uses' => 'BasketController@getDropFromBasket',
    'as' => 'product.dropFromBasket',
]);

Route::get('/remove-from-basket/{id}', [
    'uses' => 'BasketController@getRemoveFromBasket',
    'as' => 'product.removeFromBasket',
]);

Auth::routes();

Route::get('/profile', [
    'uses' => 'HomeController@profile',
    'as' => 'profile'
]);

Route::post('/updateProfile/{user}', [
    'uses' => 'homeController@updateProfile',
    'as' => 'updateProfile'
]);


Route::get('/verify', 'verifyController@getVerify')->name('getVerify');
Route::post('/verify', 'verifyController@postVerify')->name('verify');


Route::get('/test', [
    'uses' => 'MainPage@getAgent',
    'as' => 'test',
]);


Route::post('/do_order', [
    'uses' => 'BasketController@saveBasket',
    'as' => 'do.order',
]);

Route::get('/webhook', 'TelegramController@webhook');


//payment
Route::get('/returnToLania', 'BasketController@ifOrderIsPayed');
//Route::post('shop', 'siteController@add_order');

