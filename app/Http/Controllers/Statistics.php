<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        return ($arrayOfDatesANdAVG)->response()->setStatusCode(200);
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
        return ($arrayOfDatesANdAVG)->response()->setStatusCode(200);
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
        return ($arrayOfDatesANdAVG)->response()->setStatusCode(200);
    }

    // US40
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
        return esponse()->json($arrayOfDatesANdAVG, 200);
    }

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
        return esponse()->json($arrayOfDatesANdAVG, 200);
    }
    
}
