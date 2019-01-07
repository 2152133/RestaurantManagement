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
    // Delete one item
    Route::delete('/item/{id}', 'ItemController@destroy')->middleware('admin');
    // Create an item
    Route::post('/item', 'ItemController@store')->middleware('admin');
    
    // Get all users
    Route::get('users', 'UserControllerAPI@index')->middleware('admin');
    
    // Get blocked/not blocked users
    Route::get('users/blocked/{status}', 'UserControllerAPI@getBlocked')->middleware('admin');
    // Get soft deleted/not soft deleted users
    Route::get('users/deleted/{status}', 'UserControllerAPI@getDeleted')->middleware('admin');
    // Block a user
    Route::patch('user/block/{id}', 'UserControllerAPI@block')->middleware('admin');
    // Unblock a user
    Route::patch('user/unblock/{id}', 'UserControllerAPI@unblock')->middleware('admin');
    // Update an item
    Route::patch('/item/{id}', 'ItemController@update')->middleware('admin');
    
    // Register user
    Route::post('/register', 'UserControllerAPI@store')->middleware('admin');
    // Delete a user
    Route::delete('user/{id}', 'UserControllerAPI@destroy')->middleware('admin');
    // Update a user profile (as manager)
    Route::put('user/{id}', 'UserControllerAPI@updateAsManager')->middleware('admin');

//-----------------------------Orders-------------------------------------
    // List all confirmed orders
    Route::get('/orders/confirmed', 'OrderController@allConfirmed')->middleware('cook');
    // List orders from a cook
    Route::get('/orders/inPreparation/fromCook/{responsibleCookId}', 'OrderController@inPreparationWhereUser')->middleware('cook');
    // Patch order by order id
    Route::patch('/orders/{orderId}/assignTo/{userId}', 'OrderController@assignOrderToCook')->middleware('cook');
    // Patch order by order id
    Route::patch('/orders/{orderId}/preparedBy/{userId}', 'OrderController@declareOrderAsPrepared')->middleware('cook');

//-----------------------------Invoices------------------------------------

    // Get all pending invoices
    Route::get('/invoices/pending', 'InvoiceController@getPending')->middleware('cashier');
    // Get all paid invoices
    Route::get('/invoices/paid', 'InvoiceController@getPaid')->middleware('cashier');
    // Declare a invoice as paid
    Route::patch('/invoice/declarePaid', 'InvoiceController@declareInvoiceAsPaid')->middleware('cashier');

    // Declare a invoice as not paid
    Route::patch('/invoice/{id}/declareNotPaid', 'InvoiceController@declareInvoiceAsNotPaid')->middleware('admin');

    //Get filtered invoices
    Route::get('/invoices/filtered', 'InvoiceController@getFiltered')->middleware('admin');
    
//-----------------------------Meals---------------------------------------

    //Get filtered meals
    Route::get('/meals/filtered', 'MealController@getFiltered')->middleware('admin');
    
    //Get active and terminated meals
    Route::get('/meals/activeAndTerminated ', 'MealController@getActiveAndTerminated')->middleware('admin');
    //Get all active meals
    Route::get('/meals/all', 'MealController@all')->middleware('waiter');
    // Get all waiter's meals
    Route::get('/meals/waiterMeals/{responsibleWaiterId}', 'MealController@waiterMeals')->middleware('waiter');
    //For a meal from a waiter, get it's orders
    Route::get('/meals/{mealId}/confirmedOrders', 'OrderController@getConfirmedOrdersForMeal')->middleware('waiter');
    //Get all meals
    Route::get('/meals', 'MealController@index')->middleware('admin');

    //Get tables without active meals
    Route::get('/meals/tablesWithoutActiveMeals', 'MealController@getTablesWitoutActiveMeals')->middleware('waiter');
    //Create meal
    Route::post('/meals/createMealOnTable/{table_number}/onWaiter/{waiter_id}', 'MealController@createMeal')->middleware('waiter');
    //Add an order to a meal (create order)
    Route::post('/meal/addOrder/{meal_id}/{item_id}', 'OrderController@addOrderToMeal')->middleware('waiter');

    //Get prepared orders of Meal
    Route::get('/meals/{mealId}/preparedOrders', 'OrderController@getPreparedOrdersForMeal')->middleware('waiter');

    //Mark a prepared order as delivered
    Route::put('/meals/{mealId}/markPreparedOrderAsDelivered', 'OrderController@markPreparedOrderAsDelivered')->middleware('waiter');

    //Get orders for active meals
    Route::get('/meals/{mealId}/mealDetails', 'OrderController@getAllMealDetails')->middleware('waiter');

    //Get all orders for meal
    Route::get('/meals/{mealId}/allOrders', 'OrderController@getAllOrdersForMeal')->middleware('waiter');

    //Get not delivered orders of meal
    Route::get('/meals/{mealId}/notDeliveredOrders', 'OrderController@getNotDeliveredOrdersOfMeal')->middleware('waiter');

    //Terminate meal
    Route::put('/meals/{mealId}/terminate', 'MealController@terminateMeal')->middleware('waiter');

    // Declare a meal as not paid
    Route::patch('/meals/{id}/declareNotPaid', 'MealController@declareMealAsNotPaid')->middleware('waiter');

//--------------------------Restaurant tables------------------------------------------
    //Get all restaurant tables
    Route::get('/restaurantTables/all', 'RestaurantTableController@all')->middleware('admin');

    //Get all restaurant tables
    Route::get('/tables/all', 'RestaurantTableController@all')->middleware('admin');

    //Create a new restaurant table
    Route::post('/tables/{id}', 'RestaurantTableController@create')->middleware('admin');

    //Update a restaurant table
    Route::patch('/tables/{id}', 'RestaurantTableController@update')->middleware('admin');

    //Delete restaurant table by id
    Route::delete('/tables/{id}', 'RestaurantTableController@delete')->middleware('admin');

//-----------------------------Users---------------------------------------------------

    // Get email availability
    Route::get('users/emailavailable', 'UserControllerAPI@emailAvailable');
    // Get the logged user
    Route::get('users/me', 'UserControllerAPI@myProfile');
    // Get a user by id
    Route::get('user/{id}', 'UserControllerAPI@show')->middleware('admin');
    
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

    // Statistcs US39
    Route::get('/ordersHandledCook/{id}/dates/{dates}', 'Statistics@getAVGNumberOfOrdersHandledOnGivenDatesForEachCook')->middleware('admin');
    Route::get('/ordersHandledWaiter/{id}/dates/{dates}', 'Statistics@getAVGNumberOfOrdersHandledOnGivenDatesForEachWaiter')->middleware('admin');
    Route::get('/mealsHandledWaiter/{id}/dates/{dates}', 'Statistics@getAVGNumberOfMealsHandledOnGivenDatesForEachWaiter')->middleware('admin');
    // Statistics US40
    Route::get('/totalMeals/{dates}', 'Statistics@getTotalMealsFromGivenMonth')->middleware('admin');
    Route::get('/totalOrders/{dates}', 'Statistics@getTotalOrdersFromGivenMonth')->middleware('admin');
    Route::get('/timeToHandleMeal/{dates}', 'Statistics@getAVGTimeToHandleEachMealOnGivenMonth')->middleware('admin');
    Route::get('/timeToHandleOrder/{dates}', 'Statistics@getAVGTimeToHandleEachOrderOnGivenMonth')->middleware('admin');
});
    // List all items
Route::get('/items', 'ItemController@index');
Route::get('/items/all', 'ItemController@all');
// Login
Route::post('/login', 'AuthController@login')->name('login');
// List one item
Route::get('/item/{id}', 'ItemController@show');