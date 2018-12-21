<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Meal;
use App\Http\Resources\Order as OrderResource;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        // Get orders
        $orders = Order::where('state', 'confirmed')->orderBy('created_at', 'asc')->paginate(5);

        // Return collection of orders as a resource
        return OrderResource::collection($orders);
    }


    public function whereUser($user)
    {
        // Get orders
        $orders = Order::where('state', 'in preparation')->where('responsible_cook_id', $user)->orderBy('created_at', 'asc')->paginate(3);
        
        // Return collection of orders as a resource
        return OrderResource::collection($orders);
    }

    public function assignOrderToCook(Request $request, $orderID){
        try{
            $requestOrder = json_decode($request->order);
        
            $order = Order::findOrFail($requestOrder->id);
            
            $order->state = "in preparation";
            $order->responsible_cook_id = $request->user;

            if($order->save())
            {
                return new OrderResource($order);
            }
        } catch (Exception $e) {
            Debugbar::addThrowable($e);
        }
    }

    public function getPendingOrdersForMeal($meal_id){
        $pendingOrdersOfMeal = DB::table('orders')
                                ->join('meals', 'meals.id', '=', 'orders.meal_id')
                                ->where('orders.meal_id', '=', $meal_id)
                                ->where('orders.state', '=', 'pending')
                                ->paginate(10);
        return $pendingOrdersOfMeal;
    }

    public function getConfirmedOrdersForMeal($meal_id){
        $confirmedOrdersOfMeal = DB::table('orders')
                                ->join('meals', 'meals.id', '=', 'orders.meal_id')
                                ->where('orders.meal_id', '=', $meal_id)
                                ->where('orders.state', '=', 'confirmed')
                                ->paginate(10);
        return $confirmedOrdersOfMeal;
    }
}
