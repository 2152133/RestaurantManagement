<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\RestaurantTable;
class RestaurantTableController extends Controller
{
    public function all()
    {
        // Get meals
        $restaurantTables = DB::table('restaurant_tables')
                            ->get();

        // Return collection of orders as a resource
        return $restaurantTables;
    }

}
