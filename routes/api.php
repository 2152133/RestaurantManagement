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




Route::middleware('auth:api')->group(function () {
    // get auth user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::middleware('admin')->group(function () {
        // Delete one item
        Route::delete('/item/{id}', 'ItemController@destroy');

        // Create an item
        Route::post('/item', 'ItemController@store');
        
        // Get all users
        Route::get('users', 'UserControllerAPI@index');
        
        // Get blocked/not blocked users
        Route::get('users/blocked/{status}', 'UserControllerAPI@getBlocked');

        // Get soft deleted/not soft deleted users
        Route::get('users/deleted/{status}', 'UserControllerAPI@getDeleted');

        // Block a user
        Route::patch('user/block/{id}', 'UserControllerAPI@block');

        // Unblock a user
        Route::patch('user/unblock/{id}', 'UserControllerAPI@unblock');

        // Update an item
        Route::patch('/item/{id}', 'ItemController@update');
        
        // Register user
        Route::post('/register', 'UserControllerAPI@store');

        // Delete a user
        Route::delete('user/{id}', 'UserControllerAPI@destroy');

        // Update a user profile (as manager)
        Route::put('user/{id}', 'UserControllerAPI@updateAsManager');
    });
});

    // List all confirmed orders
    Route::get('/orders/confirmed', 'OrderController@allConfirmed');

    // List orders from a cook
    Route::get('/orders/inPreparation/fromCook/{responsibleCookId}', 'OrderController@inPreparationWhereUser');

    // Patch order by order id
    Route::patch('/orders/{orderId}/assignTo/{userId}', 'OrderController@assignOrderToCook');

    // Patch order by order id
    Route::patch('/orders/{orderId}/preparedBy/{userId}', 'OrderController@declareOrderAsPrepared');

    // Get all pending invoices
    Route::get('/invoices/pending', 'InvoiceController@getPending');

    // Get all paid invoices
    Route::get('/invoices/paid', 'InvoiceController@getPaid');

    // Declare a invoice as paid
    Route::patch('/invoice/declarePaid', 'InvoiceController@declareInvoiceAsPaid');

    //Get filtered meals
    Route::get('/meals/filtered', 'MealController@getFiltered');
    
    //Get active and terminated meals
    Route::get('/meals/activeAndTerminated ', 'MealController@getActiveAndTerminated');

    //Get all active meals
    Route::get('/meals/all', 'MealController@all');

    // Get all waiter's meals
    Route::get('/meals/waiterMeals/{responsibleWaiterId}', 'MealController@waiterMeals');

    //For a meal from a waiter, get it's orders
    Route::get('/meals/{mealId}/confirmedOrders', 'OrderController@getConfirmedOrdersForMeal');

    //For a meal from a waiter, get it's orders
    Route::get('/meals/{mealId}/pendingOrders', 'OrderController@getPendingOrdersForMeal');

    //Get all meals
    Route::get('/meals', 'MealController@index');

    //Get all restaurant tables
    Route::get('/restaurantTables/all', 'RestaurantTableController@all');

    //Get tables without active meals
    Route::get('/meals/tablesWithoutActiveMeals', 'MealController@getTablesWitoutActiveMeals');

    //Create meal
    Route::post('/meal/createMeal/{table_number}/{waiter_id}', 'MealController@createMeal');

    //Add an order to a meal (create order)
    Route::post('/meal/addOrder/{meal_id}/{item_id}', 'OrderController@addOrderToMeal');

    // Get email availability
    Route::get('users/emailavailable', 'UserControllerAPI@emailAvailable');

    // Get the logged user
    Route::get('users/me', 'UserControllerAPI@myProfile');

    // Get a user by id
    Route::get('user/{id}', 'UserControllerAPI@show');
    
    // Update a user profile (as user)
    Route::patch('user/{id}', 'UserControllerAPI@update');
    
    // Get all managers
    Route::get('managers', 'UserControllerAPI@getManagers');

    // Get all cooks
    Route::get('cooks', 'UserControllerAPI@getCooks');

    // Get all waiters
    Route::get('waiters', 'UserControllerAPI@getWaiters');

    // Get all cashiers
    Route::get('cashiers', 'UserControllerAPI@getCashiers');

    // Declare as start/ end shift
    Route::patch('shift/{id}', 'UserControllerAPI@startEndShift');

    // Logout
    Route::post('/logout', 'AuthController@logout');

    //Delete an order 5 seconds after creation
    Route::delete('/meal/deleteOrderOfMeal/{order_id}/delete', 'OrderController@deleteOrderUpTo5SecondsAfterCreation');

    //Get prepared orders of Meal
    Route::get('/meals/{mealId}/preparedOrders', 'OrderController@getPreparedOrdersForMeal');

    //Mark a prepared order as delivered
    Route::put('/meals/{mealId}/markPreparedOrderAsDelivered', 'OrderController@markPreparedOrderAsDelivered');

    //Get orders for active meals
    Route::get('/meals/{mealId}/mealDetails', 'OrderController@getAllMealDetails');

    //Get all orders for meal
    Route::get('/meals/{mealId}/allOrders', 'OrderController@getAllOrdersForMeal');

    //Terminate meal
    Route::put('/meals/{mealId}/terminate', 'MealController@terminateMeal');

    // Declare a invoice as paid
    Route::patch('/invoice/declarePaid', 'InvoiceController@declareInvoiceAsPaid');

    //Get all restaurant tables
    Route::get('/tables/all', 'RestaurantTableController@all');

    //Create a new restaurant table
    Route::post('/tables/{id}', 'RestaurantTableController@create');

    //Update a restaurant table
    Route::patch('/tables/{id}', 'RestaurantTableController@update');

    //Delete restaurant table by id
    Route::delete('/tables/{id}', 'RestaurantTableController@delete');
    
    //Get filtered meals
    Route::get('/invoices/filtered', 'InvoiceController@getFiltered');

    // Statistcs US39
    Route::get('/ordersHandledCook/{id}/dates/{dates}', 'Statistics@getAVGNumberOfOrdersHandledOnGivenDatesForEachCook');
    Route::get('/ordersHandledWaiter/{id}/dates/{dates}', 'Statistics@getAVGNumberOfOrdersHandledOnGivenDatesForEachWaiter');
    Route::get('/mealsHandledWaiter/{id}/dates/{dates}', 'Statistics@getAVGNumberOfMealsHandledOnGivenDatesForEachWaiter');

    // Statistics US40
    Route::get('/test/id/{id}/dates/{dates}', 'Statistics@test');

// List all items
Route::get('/items', 'ItemController@index');
Route::get('/items/all', 'ItemController@all');

// Login
Route::post('/login', 'AuthController@login')->name('login');

// List one item
Route::get('/item/{id}', 'ItemController@show');

