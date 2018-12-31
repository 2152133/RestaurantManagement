<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        return $arrayOfDatesANdAVG;
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
        return $arrayOfDatesANdAVG;
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
        return $arrayOfDatesANdAVG;
    }

    public function getTotalOrdersFromGivenMonth(Request $request, $dates) {
        $arrayOfDatesANdAVG = array();
        $datesToCompare = explode(',', $dates);

        foreach ($datesToCompare as $date) {
            try {
                $totalOrdersFromGivenDay = 
                    DB::table('orders')
                        ->whereDate('start', Carbon::parse($date))
                        ->count();
                array_push($arrayOfDatesANdAVG, ['date' => $date, 'Total Orders' => $totalOrdersFromGivenDay]);
            }catch (\Exception $e){
                return response()->json(['error' => 'Invalid date format.'], 500);
            }
        }
        return $arrayOfDatesANdAVG;
    }
    
}
