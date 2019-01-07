<?php

namespace App\Http\Controllers;

use App\Http\Resources\Order as OrderResource;
use App\Order;
use App\Item;
use App\Meal;
use Illuminate\Http\Request;
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
        
    }

    //--------------------------------------Cooks-------------------------------------------------
    public function allConfirmed()
    {
        // Get orders
        $orders = Order::where('state', 'confirmed')
                            ->orderBy('created_at', 'asc')
                            ->paginate(5);

        // Return collection of orders as a resource
        return (OrderResource::collection($orders))->response()->setStatusCode(200);
    }

    public function inPreparationWhereUser($userId)
    {
        // Get orders
        $orders = Order::where('state', 'in preparation')
                            ->where('responsible_cook_id', $userId)
                            ->orderBy('created_at', 'asc')
                            ->paginate(5);

        // Return collection of orders as a resource
        return (OrderResource::collection($orders))->response()->setStatusCode(200);
    }

    public function assignOrderToCook($orderId, $userId)
    {
        try {
            $order = Order::findOrFail($orderId);

            $order->state = "in preparation";
            $order->responsible_cook_id = $userId;

            if ($order->save()) {
                return (new OrderResource($order))->response()->setStatusCode(200);
            }
        } catch (Exception $e) {
            Debugbar::addThrowable($e);
        }
    }

    public function declareOrderAsPrepared($orderId, $userId)
    {
        
        try {
            $order = Order::findOrFail($orderId);

            $order->state = "prepared";
            
            $order->responsible_cook_id = $userId;

            if ($order->save()) {
                return (new OrderResource($order))->response()->setStatusCode(200);
            }
        } catch (Exception $e) {
            Debugbar::addThrowable($e);
        }
    }


    //------------------------------------Waiters----------------------------------------------

    public function getConfirmedOrdersForMeal($meal_id)
    {
        $confirmedOrdersOfMeal = Order::where('meal_id', $meal_id)
            ->where('state', 'confirmed')
            ->paginate(10);
        return (OrderResource::collection($confirmedOrdersOfMeal))->response()->setStatusCode(200);
    }

    public function addOrderToMeal($meal_number, $item_id)
    {
        Order::create([
            'state' => 'confirmed',
            'item_id' => $item_id,
            'meal_id' => $meal_number,
            'responsible_cook_id' => null,
            'start' => date('Y-m-d H:i:s'),
            'end' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $priceOfItem = Item::where('id', $item_id)
            ->select('price')
            ->get();
        $p = $priceOfItem->pluck("price")[0];
        
        $current_meal_price = Meal::where('id', $meal_number)
            ->select('total_price_preview')
            ->get();

        $cp = $current_meal_price->pluck("total_price_preview")[0];

        Meal::where('id', $meal_number)
            ->update(['total_price_preview' => $p + $cp]);
    }

    public function getPreparedOrdersForMeal($meal_id){
        $preparedOrdersOfMeal = Order::where('meal_id', $meal_id)
            ->where('state', 'prepared')
            ->paginate(10);
        return (OrderResource::collection($preparedOrdersOfMeal))->response()->setStatusCode(200);
    }

    public function markPreparedOrderAsDelivered($order_id){
        Order::where('id', '=', $order_id)
            ->update(['state' => 'delivered',
                      'end' => date('Y-m-d H:i:s'),
                      'updated_at' => date('Y-m-d H:i:s')]);
    }

    public function getAllMealDetails($meal_id){
        $allOrders = DB::join('meals', 'meals.id', '=', 'orders.meal_id')
            ->join('items', 'orders.item_id', '=', 'items.id')
            ->where('meals.id', '=', $meal_id)
            ->select('meals.table_number', 'meals.total_price_preview', 'items.name', 'items.price', 'orders.id', 'orders.meal_id')
            ->paginate(30);
        return response()->json($allOrders, 200);
    }

    public function getAllOrdersForMeal($meal_id){
        $allOrders = Order::where('meal_id', $meal_id)
                    ->paginate(30);
        return response()->json($allOrders, 200);
    }

    public function getNotDeliveredOrdersOfMeal($meal_id){
        $notDeliveredMealOrders = Order::where('meal_id', $meal_id)
                            ->where('state', '!=', 'delivered')
                            ->get();
        return $notDeliveredMealOrders;
    }
}
