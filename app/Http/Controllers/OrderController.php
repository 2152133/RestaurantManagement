<?php

namespace App\Http\Controllers;

use App\Http\Resources\Order as OrderResource;
use App\Order;
use Carbon\Carbon;
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

    public function assignOrderToCook(Request $request, $orderID)
    {
        try {
            $requestOrder = json_decode($request->order);

            $order = Order::findOrFail($requestOrder->id);

            $order->state = "in preparation";
            $order->responsible_cook_id = $request->user;

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
            ->paginate(10);
        return $pendingOrdersOfMeal;
    }

    public function getConfirmedOrdersForMeal($meal_id)
    {
        $confirmedOrdersOfMeal = DB::table('orders')
            ->join('meals', 'meals.id', '=', 'orders.meal_id')
            ->where('orders.meal_id', '=', $meal_id)
            ->where('orders.state', '=', 'confirmed')
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
            'start' => Carbon::now(),
            'end' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $priceOfItem = DB::table('items')
            ->where('id', '=', $item_id)
            ->select('price')
            ->get();
        $p = $priceOfItem -> pluck("price")[0];

        $current_meal_price = DB::table('meals')
            ->where('id', '=', $meal_number)
            ->select('total_price_preview')
            ->get();

        $cp = $current_meal_price -> pluck("total_price_preview")[0];

        DB::table('meals')
            ->where('id', '=', $meal_number)
            ->update(['total_price_preview' => $p + $cp]);

    }
}
