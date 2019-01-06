<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Meal;
use App\Order;

class Statistics extends Controller
{
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

                $allMealIDs = 
                        Meal::select(DB::raw('(TIMEDIFF(meals.end, meals.start)) as total'))
                            ->distinct()
                            ->join('orders', 'orders.meal_id', '=', 'meals.id')
                            ->whereYear('meals.start', '=', Carbon::parse($date)->format('Y'))
                            ->whereMonth('meals.start', '=', Carbon::parse($date)->format('m'))
                            ->get();
                $totalMealIDs = $allMealIDs->count();
                $timeItTakesTOHandleAMeal = 0;
                for ($i=0; $i < $totalMealIDs; $i++) {
                    list($hours, $minutes, $seconds) = explode(':', (($allMealIDs[$i])->only('total')['total']), 3);
                    $timeItTakesTOHandleAMeal += ((int)$minutes * 60 + (int)$hours * 3600 + (int)$seconds);
                }
                if ($totalMealsFromGivenMonth > 0)
                    //array_push($arrayOfDatesANdAVG, ['date' => $date, 'AVG time to handle Meal' => gmdate("H:i:s", ($timeItTakesTOHandleAMeal/$totalMealsFromGivenMonth))]);
                    array_push($arrayOfDatesANdAVG, ['date' => $date, 'AVG time to handle Meal (minutes)' => (int)gmdate("i", ($timeItTakesTOHandleAMeal/$totalMealsFromGivenMonth))]);
                else
                    //array_push($arrayOfDatesANdAVG, ['date' => $date, 'AVG time to handle Meal' => gmdate("H:i:s", $totalMealsFromGivenMonth)]);
                    array_push($arrayOfDatesANdAVG, ['date' => $date, 'AVG time to handle Meal (minutes)' => (int)gmdate("i", $totalMealsFromGivenMonth)]);
            }catch (\Exception $e){
                return $e;
                return response()->json(['error' => 'Invalid date format.'], 500);
            }
        }
        return response()->json($arrayOfDatesANdAVG, 200);
    }

    // average time to handle each order (each month) by item
    public function getAVGTimeToHandleEachOrderOnGivenMonth(Request $request, $dates) {
        $arrayOfDatesANdAVG = array();
        $datesToCompare = explode(',', $dates);
        
        foreach ($datesToCompare as $date) {
            try {
                $allOrdersItemsWithTotalOccurencesAndTotalTime = 
                        Order::select(DB::raw('count(*) as totalOcurrences, orders.item_id as id, sum(TIMEDIFF(orders.end, orders.start)) as totalTime')) //, (TIMEDIFF(orders.end, orders.start)) as total
                            ->groupBy('orders.item_id')
                            ->orderBy('orders.item_id', 'ASC')
                            ->whereYear('orders.start', '=', Carbon::parse($date)->format('Y'))
                            ->whereMonth('orders.start', '=', Carbon::parse($date)->format('m'))
                            ->get();// 2017-12-02 -> total: 73517
                $arrayOfDatesAndAVG = array();
                foreach ($allOrdersItemsWithTotalOccurencesAndTotalTime as $ordersItemsWithTotalOccurencesAndTotalTime) {
                    $totalOcurrences = $ordersItemsWithTotalOccurencesAndTotalTime->only('totalOcurrences')['totalOcurrences'];
                    $totalTime = $ordersItemsWithTotalOccurencesAndTotalTime->only('totalTime')['totalTime'];
                    $id = $ordersItemsWithTotalOccurencesAndTotalTime->only('id')['id'];
                    if ($totalOcurrences > 0)
                        //array_push($arrayOfDatesAndAVG, ['date' => $date . ' item ' . $id, 'AVG time to handle Order' => gmdate("H:i:s", ($totalTime/$totalOcurrences))]);
                        array_push($arrayOfDatesAndAVG, ['date' => $date . ' item ' . $id, 'AVG time to handle Order (minutes)' => (int)gmdate("i", ($totalTime/$totalOcurrences))]);
                    else
                        //array_push($arrayOfDatesAndAVG, ['date' => $date . ' item ' . $id, 'AVG time to handle Order' => gmdate("H:i:s", $totalOcurrences)]);
                        array_push($arrayOfDatesAndAVG, ['date' => $date . ' item ' . $id, 'AVG time to handle Order (minutes)' => (int)gmdate("i", $totalOcurrences)]);
                }
            }catch (\Exception $e){
                return response()->json(['error' => 'Invalid date format.'], 500);
            }
        }
        //dd($arrayOfDatesAndAVG);
        return response()->json($arrayOfDatesAndAVG, 200);
    }
}
