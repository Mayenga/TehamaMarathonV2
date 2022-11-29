<?php

use Illuminate\Support\Facades\Route;
use app\Http\Controllers\TodoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/payment', function () {
    return view('payments.payments');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/customers', 'App\Http\Controllers\CustomersController@list')->name('customers');
});

Route::get('/registercustomer', 'App\Http\Controllers\CustomersController@index')->name('registercustomer');
Route::post('/storecustomer', 'App\Http\Controllers\CustomersController@store')->name('storecustomer');
Route::get('/payments', function () {
    return view('payments.index');
});
Route::get('/search', function () {
    return view('payments.search');
});

Route::get('/terms', function () {
    return view('terms.terms');
});

require __DIR__.'/auth.php';
