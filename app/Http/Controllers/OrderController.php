<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Http\Resources\Order as OrderResource;

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
}
