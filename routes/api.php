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

//-----------------------------Orders-------------------------------------
    // List all confirmed orders
    Route::get('/orders/confirmed', 'OrderController@allConfirmed');
    // List orders from a cook
    Route::get('/orders/inPreparation/fromCook/{responsibleCookId}', 'OrderController@inPreparationWhereUser');
    // Patch order by order id
    Route::patch('/orders/{orderId}/assignTo/{userId}', 'OrderController@assignOrderToCook');
    // Patch order by order id
    Route::patch('/orders/{orderId}/preparedBy/{userId}', 'OrderController@declareOrderAsPrepared');


//-----------------------------Invoices------------------------------------

    // Get all pending invoices
    Route::get('/invoices/pending', 'InvoiceController@getPending');
    // Get all paid invoices
    Route::get('/invoices/paid', 'InvoiceController@getPaid');
    // Declare a invoice as paid
    Route::patch('/invoice/declarePaid', 'InvoiceController@declareInvoiceAsPaid');

    // Declare a invoice as not paid
    Route::patch('/invoice/{id}/declareNotPaid', 'InvoiceController@declareInvoiceAsNotPaid');

    //Get filtered invoices
    Route::get('/invoices/filtered', 'InvoiceController@getFiltered');
    


//-----------------------------Meals---------------------------------------

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
    //Get all meals
    Route::get('/meals', 'MealController@index');

    //Get tables without active meals
    Route::get('/meals/tablesWithoutActiveMeals', 'MealController@getTablesWitoutActiveMeals');
    //Create meal
    Route::post('/meals/createMealOnTable/{table_number}/onWaiter/{waiter_id}', 'MealController@createMeal');
    //Add an order to a meal (create order)
    Route::post('/meal/addOrder/{meal_id}/{item_id}', 'OrderController@addOrderToMeal');

    //Get prepared orders of Meal
    Route::get('/meals/{mealId}/preparedOrders', 'OrderController@getPreparedOrdersForMeal');

    //Mark a prepared order as delivered
    Route::put('/meals/{mealId}/markPreparedOrderAsDelivered', 'OrderController@markPreparedOrderAsDelivered');

    //Get orders for active meals
    Route::get('/meals/{mealId}/mealDetails', 'OrderController@getAllMealDetails');

    //Get all orders for meal
    Route::get('/meals/{mealId}/allOrders', 'OrderController@getAllOrdersForMeal');

    //Get not delivered orders of meal
    Route::get('/meals/{mealId}/notDeliveredOrders', 'OrderController@getNotDeliveredOrdersOfMeal');

    //Terminate meal
    Route::put('/meals/{mealId}/terminate', 'MealController@terminateMeal');

    // Declare a meal as not paid
    Route::patch('/meals/{id}/declareNotPaid', 'MealController@declareMealAsNotPaid');

//--------------------------Restaurant tables------------------------------------------
    //Get all restaurant tables
    Route::get('/restaurantTables/all', 'RestaurantTableController@all');

    //Get all restaurant tables
    Route::get('/tables/all', 'RestaurantTableController@all');

    //Create a new restaurant table
    Route::post('/tables/{id}', 'RestaurantTableController@create');

    //Update a restaurant table
    Route::patch('/tables/{id}', 'RestaurantTableController@update');

    //Delete restaurant table by id
    Route::delete('/tables/{id}', 'RestaurantTableController@delete');



//-----------------------------Users---------------------------------------------------

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

    
    // Statistcs US39
    Route::get('/ordersHandledCook/{id}/dates/{dates}', 'Statistics@getAVGNumberOfOrdersHandledOnGivenDatesForEachCook');
    Route::get('/ordersHandledWaiter/{id}/dates/{dates}', 'Statistics@getAVGNumberOfOrdersHandledOnGivenDatesForEachWaiter');
    Route::get('/mealsHandledWaiter/{id}/dates/{dates}', 'Statistics@getAVGNumberOfMealsHandledOnGivenDatesForEachWaiter');
    // Statistics US40
    Route::get('/totalMeals/{dates}', 'Statistics@getTotalMealsFromGivenMonth');
    Route::get('/totalOrders/{dates}', 'Statistics@getTotalOrdersFromGivenMonth');
    Route::get('/timeToHandleMeal/{dates}', 'Statistics@getAVGTimeToHandleEachMealOnGivenMonth');
    Route::get('/timeToHandleOrder/{dates}', 'Statistics@getAVGTimeToHandleEachOrderOnGivenMonth');

    // List all items
Route::get('/items', 'ItemController@index');
Route::get('/items/all', 'ItemController@all');
// Login
Route::post('/login', 'AuthController@login')->name('login');
// List one item
Route::get('/item/{id}', 'ItemController@show');