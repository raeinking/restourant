<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('login');
});
Route::get('/createuser', function () {
    return view('rigister');
});

Route::get('/psforget', function () {
    return view('forgetPassword');
});

Route::get('/resetpassword', function () {
    return view('resetPassword');
});

Route::get('/invoice', function () {
    return view('invoice');
});
Route::get('/addProduct', function () {
    return view('addProduct');
});
Route::get('/products', function () {
    return view('products');
});
Route::get('/pos', function () {
    return view('pos');
});
