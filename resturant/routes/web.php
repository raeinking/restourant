<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;
use App\Http\Controllers\UserController;

// Route::get('/', function () {
//     // return view('home', [userController::class, 'fetchUser', 'loading' => $loading]);
//     Route::get('/home', [DataController::class, 'fetchData']);
//     // Route::get('/home', [DataController::class, 'fetchData']);
// });

Route::get('/', [DataController::class, 'fetchData']);

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
