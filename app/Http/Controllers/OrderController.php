<?php

namespace App\Http\Controllers;

use App\Http\Resources\Order as OrderResource;
use App\Order;
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

    public function allConfirmed()
    {
        // Get orders
        $orders = Order::where('state', 'confirmed')
                            ->orderBy('created_at', 'asc')
                            ->paginate(5);

        // Return collection of orders as a resource
        return OrderResource::collection($orders);
    }

    public function inPreparationWhereUser($userId)
    {
        // Get orders
        $orders = Order::where('state', 'in preparation')
                            ->where('responsible_cook_id', $userId)
                            ->orderBy('created_at', 'asc')
                            ->paginate(5);

        // Return collection of orders as a resource
        return OrderResource::collection($orders);
    }

    public function assignOrderToCook(Request $request,$orderId)
    {
        try {
            $order = Order::findOrFail($orderId);

            $order->state = "in preparation";
            $order->responsible_cook_id = $request->userId;

            if ($order->save()) {
                return new OrderResource($order);
            }
        } catch (Exception $e) {
            Debugbar::addThrowable($e);
        }
    }

    public function declareOrderAsPrepared(Request $request, $orderId)
    {
        
        try {
            $order = Order::findOrFail($orderId);

            $order->state = "prepared";
            
            $order->responsible_cook_id = $request->userId;

            if ($order->save()) {
                return new OrderResource($order);
            }
        } catch (Exception $e) {
            Debugbar::addThrowable($e);
        }
    }

    public function getPendingOrdersForMeal($meal_id)
    {
        $pendingOrdersOfMeal = DB::table('orders')
            ->join('meals', 'meals.id', '=', 'orders.meal_id')
            ->where('orders.meal_id', '=', $meal_id)
            ->where('orders.state', '=', 'pending')
            ->select('orders.state', 'orders.id', 'orders.meal_id', 'orders.responsible_cook_id', 'orders.start', 'orders.end', 'orders.item_id', 'orders.created_at')
            ->paginate(10);
        return $pendingOrdersOfMeal;
    }

    public function getConfirmedOrdersForMeal($meal_id)
    {
        $confirmedOrdersOfMeal = DB::table('orders')
            ->join('meals', 'meals.id', '=', 'orders.meal_id')
            ->where('orders.meal_id', '=', $meal_id)
            ->where('orders.state', '=', 'confirmed')
            ->select('orders.state', 'orders.id', 'orders.meal_id', 'orders.responsible_cook_id', 'orders.start', 'orders.end', 'orders.item_id', 'orders.created_at')
            ->paginate(10);
        return $confirmedOrdersOfMeal;
    }

    public function addOrderToMeal($meal_number, $item_id)
    {
        Order::create([
            'state' => 'pending',
            'item_id' => $item_id,
            'meal_id' => $meal_number,
            'responsible_cook_id' => null,
            'start' => date('Y-m-d H:i:s'),
            'end' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $priceOfItem = DB::table('items')
            ->where('id', '=', $item_id)
            ->select('price')
            ->get();
        $p = $priceOfItem->pluck("price")[0];

        $current_meal_price = DB::table('meals')
            ->where('id', '=', $meal_number)
            ->select('total_price_preview')
            ->get();

        $cp = $current_meal_price->pluck("total_price_preview")[0];

        DB::table('meals')
            ->where('id', '=', $meal_number)
            ->update(['total_price_preview' => $p + $cp]);
    }

    public function deleteOrderUpTo5SecondsAfterCreation($order_id)
    {
        $orderToDelete = Order::findOrFail($order_id);
        $orderToDeleteDate = $orderToDelete->start;
        $current_date = date('Y-m-d H:i:s');
        if (strtotime($current_date) - strtotime($orderToDeleteDate) < 5) {
            $orderToDelete->delete();
        } else {
            return response()->json(['error' => 'Unable to delete order.'], 401);
        }
    }

    public function getPreparedOrdersForMeal($meal_id){
        $preparedOrdersOfMeal = DB::table('orders')
            ->join('meals', 'meals.id', '=', 'orders.meal_id')
            ->where('orders.meal_id', '=', $meal_id)
            ->where('orders.state', '=', 'prepared')
            ->select('orders.state', 'orders.id', 'orders.meal_id', 'orders.responsible_cook_id', 'orders.start', 'orders.end', 'orders.item_id', 'orders.created_at')
            ->paginate(10);
        return $preparedOrdersOfMeal;
    }

    public function markPreparedOrderAsDelivered($order_id){
        DB::table('orders')
            ->where('id', '=', $order_id)
            ->update(['state' => 'delivered',
                      'end' => date('Y-m-d H:i:s'),
                      'updated_at' => date('Y-m-d H:i:s')]);
    }

    public function getAllMealDetails($meal_id){
        $allOrders = DB::table('orders')
            ->join('meals', 'meals.id', '=', 'orders.meal_id')
            ->join('items', 'orders.item_id', '=', 'items.id')
            ->where('meals.id', '=', $meal_id)
            ->select('meals.table_number', 'meals.total_price_preview', 'items.name', 'items.price', 'orders.id', 'orders.meal_id')
            ->paginate(30);
        return $allOrders;
    }

    public function getAllOrdersForMeal($meal_id){
        $allOrders = DB::table('orders')
                    ->where('orders.meal_id', '=', $meal_id)
                    ->paginate(30);
        return $allOrders;
    }
}
