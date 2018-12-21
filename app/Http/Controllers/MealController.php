<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Meal;
use App\Http\Resources\Meal as MealResource;
use App\Http\Resources\Order as OrderResource;
use Illuminate\Support\Facades\DB;

class MealController extends Controller
{
    public function all()
    {
        // Get meals
        $meals = Meal::where('state', 'active')->orderBy('created_at', 'asc')->paginate(5);

        // Return collection of orders as a resource
        return MealResource::collection($meals);
    }

    public function waiterMeals($user_id){
        $waiterMeals = DB::table('meals')
                    ->where('meals.responsible_waiter_id', '=', $user_id)
                    ->where('meals.state', '=', 'active')
                    ->paginate(5);
        return $waiterMeals;
    }

    
}
