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

Route::get('/', function () {
    return view('mainPage');
})->name('mainPage');

// Route::get('/verify/{remember_token}', 'VerifyController@verify')->name('verify');

// Route::get('/setPassword', function() {
//     return view('setPassword');
// })->name('setPassword');

Route::get('/verify/{remember_token}', function() {
        return view('setPassword');
    })->name('verify');

Route::patch('/setPassword', 'UserControllerAPI@setNewPassword')->name('setNewPassword');
