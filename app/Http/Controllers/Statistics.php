<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Meal;

class Statistics extends Controller
{
    // US39
    public function getAVGNumberOfOrdersHandledOnGivenDatesForEachCook(Request $request, $id, $dates) {
        $arrayOfDatesANdAVG = array();
        $datesToCompare = explode(',', $dates);

        foreach ($datesToCompare as $date) {
            try {
                $totalOrdersFromGivenDay = 
                    DB::table('orders')
                        ->whereDate('start', '=', Carbon::parse($date))
                        ->count();
                $numberOfOrdersFromGivenDayHandledByGivenCook =     
                    DB::table('orders')
                        ->where('responsible_cook_id', '=', $id)
                        ->whereDate('start', '=', Carbon::parse($date))
                        ->count();
                if ($totalOrdersFromGivenDay > 0) 
                    array_push($arrayOfDatesANdAVG, ['date' => $date, 'AVG Orders' => ($numberOfOrdersFromGivenDayHandledByGivenCook/$totalOrdersFromGivenDay)*100]);
                else
                    array_push($arrayOfDatesANdAVG, ['date' => $date, 'AVG Orders' => $totalOrdersFromGivenDay]);
            }catch (\Exception $e){
                return response()->json(['error' => 'Invalid date format.'], 500);
            }
        }
        return response()->json($arrayOfDatesANdAVG, 200);
    }

    public function getAVGNumberOfOrdersHandledOnGivenDatesForEachWaiter(Request $request, $id, $dates) {
        $arrayOfDatesANdAVG = array();
        $datesToCompare = explode(',', $dates);

        foreach ($datesToCompare as $date) {
            try {
                $totalOrdersFromGivenDay = 
                    DB::table('orders')
                        ->whereDate('start', Carbon::parse($date))
                        ->count();
                $numberOfOrdersFromGivenDayHandledByGivenWaiter = 
                    DB::table('orders')
                        ->join('meals', 'orders.meal_id', '=', 'meals.id')
                        ->where('meals.responsible_waiter_id', '=', $id)
                        ->whereDate('orders.start', Carbon::parse($date))
                        ->count();
                if ($totalOrdersFromGivenDay > 0) 
                    array_push($arrayOfDatesANdAVG, ['date' => $date, 'AVG Orders' => ($numberOfOrdersFromGivenDayHandledByGivenWaiter/$totalOrdersFromGivenDay)*100]);
                else
                    array_push($arrayOfDatesANdAVG, ['date' => $date, 'AVG Orders' => $totalOrdersFromGivenDay]);
            }catch (\Exception $e){
                return response()->json(['error' => 'Invalid date format.'], 500);
            }
        }
        return response()->json($arrayOfDatesANdAVG, 200);
    }

    public function getAVGNumberOfMealsHandledOnGivenDatesForEachWaiter(Request $request, $id, $dates) {
        $arrayOfDatesANdAVG = array();
        $datesToCompare = explode(',', $dates);

        foreach ($datesToCompare as $date) {
            try {
                $totalMealsFromGivenDay = 
                    DB::table('meals')
                        ->whereDate('start', Carbon::parse($date))
                        ->count();
                $numberOfMealsFromGivenDayHandledByGivenWaiter = 
                    DB::table('meals')
                        ->where('responsible_waiter_id', '=', $id)
                        ->whereDate('start', Carbon::parse($date))
                        ->count(); 
                if ($totalMealsFromGivenDay > 0) 
                    array_push($arrayOfDatesANdAVG, ['date' => $date, 'AVG Meals' => ($numberOfMealsFromGivenDayHandledByGivenWaiter/$totalMealsFromGivenDay)*100]);
                else
                    array_push($arrayOfDatesANdAVG, ['date' => $date, 'AVG Meals' => $totalMealsFromGivenDay]);
            }catch (\Exception $e){
                return response()->json(['error' => 'Invalid date format.'], 500);
            }
        }
        return response()->json($arrayOfDatesANdAVG, 200);
    }

    // total orders (each month)
    public function getTotalOrdersFromGivenMonth(Request $request, $dates) {
        $arrayOfDatesAndAVG = array();
        $datesToCompare = explode(',', $dates);

        foreach ($datesToCompare as $date) {
            try {
                $totalOrdersFromGivenMonth = 
                    DB::table('orders')
                        ->whereYear('start', '=', Carbon::parse($date)->format('Y'))
                        ->whereMonth('start', '=', Carbon::parse($date)->format('m'))
                        ->count();
                array_push($arrayOfDatesAndAVG, ['date' => $date, 'Total Orders' => $totalOrdersFromGivenMonth]);
            }catch (\Exception $e){
                return response()->json(['error' => 'Invalid date format.'], 500);
            }
        }
        return response()->json($arrayOfDatesAndAVG, 200);
    }
    
    // total meals (each month)
    public function getTotalMealsFromGivenMonth(Request $request, $dates) {
        $arrayOfDatesAndAVG = array();
        $datesToCompare = explode(',', $dates);

        foreach ($datesToCompare as $date) {
            try {
                $totalMealsFromGivenMonth = 
                    DB::table('meals')
                        ->whereYear('start', '=', Carbon::parse($date)->format('Y'))
                        ->whereMonth('start', '=', Carbon::parse($date)->format('m'))
                        ->count();
                array_push($arrayOfDatesAndAVG, ['date' => $date, 'Total Meals' => $totalMealsFromGivenMonth]);
            }catch (\Exception $e){
                return response()->json(['error' => 'Invalid date format.'], 500);
            }
        }
        return response()->json($arrayOfDatesAndAVG, 200);
    }

    // average time to handle each meals (each month)
    public function getAVGTimeToHandleEachMealOnGivenMonth(Request $request, $dates) {
        $arrayOfDatesANdAVG = array();
        $datesToCompare = explode(',', $dates);
        
        foreach ($datesToCompare as $date) {
            try {
                $mealIDs = 
                        Meal::select('id')
                            ->from('meals')
                            ->whereYear('meals.start', '=', Carbon::parse($date)->format('Y'))
                            ->whereMonth('meals.start', '=', Carbon::parse($date)->format('m'))
                            ->orderBy('id', 'ASC')
                            ->get();
                $totalMealsFromGivenMonth = $mealIDs->count();

                $timeItTakesTOHandleAMeal = 0;
                for ($i=0; $i < $totalMealsFromGivenMonth; $i++) { 
                    $aux = 
                        Meal::select(DB::raw('(TIMEDIFF(meals.end, meals.start)) as total'))
                            ->distinct()
                            ->join('orders', 'orders.meal_id', '=', 'meals.id')
                            //->where('meals.id', '=', $mealIDs[$i]->only('id'))
                            ->where('meals.id', '=', 1)
                            ->whereYear('meals.start', '=', Carbon::parse($date)->format('Y'))
                            ->whereMonth('meals.start', '=', Carbon::parse($date)->format('m'))
                            ->get();
                    if (($aux[0])->only('total')['total'] != null)
                    {
                        // get date diference in secounds
                        $aux = ($aux[0])->only('total')['total'];
                        list($hours, $minutes, $seconds) = explode(':', $aux, 3);
                        $timeItTakesTOHandleAMeal += (int)$minutes * 60 + (int)$hours * 3600 + (int)$seconds;
                    }
                }
                if ($totalMealsFromGivenMonth > 0)
                    array_push($arrayOfDatesANdAVG, ['date' => $date, 'AVG time to handle Meal' => gmdate("H:i:s", ($timeItTakesTOHandleAMeal/$totalMealsFromGivenMonth))]);
                else
                    array_push($arrayOfDatesANdAVG, ['date' => $date, 'AVG time to handle Meal' => gmdate("H:i:s", $totalMealsFromGivenMonth)]);
            }catch (\Exception $e){
                return response()->json(['error' => 'Invalid date format.'], 500);
            }
        }
        return response()->json($arrayOfDatesANdAVG, 200);
    }
    /* total orders in meal
    Meal::select(DB::raw('(meals.start - meals.end) as total'))
        ->join('orders', 'orders.meal_id', '=', 'meals.id')
        ->where('meals.id', '=', $id)
        ->whereYear('meals.start', '=', Carbon::parse($date)->format('Y'))
        ->whereMonth('meals.start', '=', Carbon::parse($date)->format('m'))
        ->get();
    */
}
