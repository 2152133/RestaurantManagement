<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Meal;
use App\Http\Resources\Meal as MealResource;
use App\Http\Resources\Order as OrderResource;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MealController extends Controller
{
    public function all()
    {
        // Get meals
        $meals = Meal::where('state', 'active')->orderBy('created_at', 'asc')->paginate(5);

        // Return collection of orders as a resource
        return MealResource::collection($meals);
    }

    public function createMeal($table_number, $responsible_waiter_id){
        Meal::create([
            'state' => 'active',
            'table_number' => $table_number,
            'start' => Carbon::now(),
            'end' => null,
            'responsible_waiter_id' => $responsible_waiter_id,
            'total_price_preview' => '0',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }

    public function waiterMeals($user_id){
        $waiterMeals = DB::table('meals')
                    ->where('meals.responsible_waiter_id', '=', $user_id)
                    ->where('meals.state', '=', 'active')
                    ->paginate(5);
        return $waiterMeals;
    }

    public function getTablesWitoutActiveMeals(){
        $tablesWithActiveMeals = DB::table('restaurant_tables')
                                ->whereNotIn('table_number', function($q){
                                    $q->select('table_number')
                                    ->from('meals')
                                    ->where('state', '=', 'active');
                                })
                                ->select('table_number')
                                ->get();
        return $tablesWithActiveMeals;
    }
}
