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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// List all items
Route::get('items', 'ItemController@index');

// List one item
Route::get('item/{id}', 'ItemController@show');

// Create an item
Route::post('item', 'ItemController@store');

// Update an item
Route::patch('item/{id}', 'ItemController@store');

// Delete one item
Route::delete('item/{id}', 'ItemController@destroy');