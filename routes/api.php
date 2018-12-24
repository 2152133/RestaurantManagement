<?php

use Illuminate\Http\Request;
use App\Http\Controllers\RestaurantTableController;


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
    // get auth user
    Route::get('/user', function (Request $request) {return $request->user();});


    



    Route::post('/logout', 'AuthController@logout');
});
Route::post('/register', 'UserControllerAPI@store');

// List all items
Route::get('/items', 'ItemController@index');
Route::get('/items/all', 'ItemController@all');

Route::post('/login', 'AuthController@login')->name('login');

// List one item
Route::get('/item/{id}', 'ItemController@show');

// Delete one item
Route::delete('/item/{id}', 'ItemController@destroy');

// Create an item
Route::post('/item', 'ItemController@store');

// List all orders
Route::get('/orders/all', 'OrderController@all');

// List orders from a cook
Route::get('/orders/fromCook/{responsibleCookId}', 'OrderController@whereUser');

// Patch order by order id
Route::patch('/orders/{id}', 'OrderController@assignOrderToCook');

// Get all pending invoices
Route::get('/invoices/pending', 'InvoiceController@getPending');

// Get all paid invoices
Route::get('/invoices/paid', 'InvoiceController@getPaid');

// Declare a invoice as paid
Route::patch('/invoice/declarePaid', 'InvoiceController@declareInvoiceAsPaid');

//Get all meals
Route::get('/meals/all', 'MealController@all');

// Get all waiter's meals
Route::get('/meals/waiterMeals/{responsibleWaiterId}', 'MealController@waiterMeals');

//For a meal from a waiter, get it's orders
Route::get('/meals/{mealId}/confirmedOrders', 'OrderController@getConfirmedOrdersForMeal');

//For a meal from a waiter, get it's orders
Route::get('/meals/{mealId}/pendingOrders', 'OrderController@getPendingOrdersForMeal');

//Get all restaurant tables
Route::get('/restaurantTables/all', 'RestaurantTableController@all');

//Get tables without active meals
Route::get('/meals/tablesWithoutActiveMeals', 'MealController@getTablesWitoutActiveMeals');

//Create meal
Route::post('/meal/createMeal/{table_number}/{waiter_id}', 'MealController@createMeal');

//Add an order to a meal (create order)
Route::post('/meal/addOrder/{meal_id}/{item_id}', 'OrderController@addOrderToMeal');

// Declare a invoice as paid
Route::get('users', 'UserControllerAPI@index');
Route::get('users/emailavailable', 'UserControllerAPI@emailAvailable');
Route::middleware('auth:api')->get('users/me', 'UserControllerAPI@myProfile');

Route::get('user/{id}', 'UserControllerAPI@show');
Route::patch('user/{id}', 'UserControllerAPI@update');
Route::put('user/{id}', 'UserControllerAPI@updateAsManager');
Route::delete('user/{id}', 'UserControllerAPI@destroy');
Route::post('users', 'UserControllerAPI@store');
Route::get('managers', 'UserControllerAPI@getManagers');

Route::patch('shift/{id}', 'UserControllerAPI@startEndShift');
