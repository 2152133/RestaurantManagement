<?php

use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

// Update an item
Route::patch('/item/{id}', 'ItemController@update');


Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', 'AuthController@logout');
});

Route::post('/login', 'AuthController@login')->name('login');
Route::post('/register', 'UserControllerAPI@store');

// List one item
Route::get('/item/{id}', 'ItemController@show');

// Delete one item
Route::delete('/item/{id}', 'ItemController@destroy');

// Create an item
Route::post('/item', 'ItemController@store');

// List all items
Route::get('/items', 'ItemController@index');

// List all orders
Route::get('/orders/all', 'OrderController@all');

// List orders from a cook
Route::get('/orders/fromCook/{responsibleCookId}', 'OrderController@whereUser');

// Patch order by order id
Route::patch('/orders/{id}', 'OrderController@assignOrderToCook');

// Get all pending invoices
Route::get('/invoices/pending', 'InvoiceController@getPending');

// Declare a invoice as paid
Route::patch('/invoice/declarePaid', 'InvoiceController@declareInvoiceAsPaid');


Route::get('users', 'UserControllerAPI@index');
Route::get('users/emailavailable', 'UserControllerAPI@emailAvailable');
Route::middleware('auth:api')->get('users/me', 'UserControllerAPI@myProfile');

Route::get('user/{id}', 'UserControllerAPI@show');
Route::patch('user/{id}', 'UserControllerAPI@update');
Route::delete('user/{id}', 'UserControllerAPI@destroy');
Route::post('users', 'UserControllerAPI@store');
