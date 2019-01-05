<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Meal;
use App\Invoice;
use App\Order;
use App\Http\Resources\Meal as MealResource;
use App\Http\Resources\Order as OrderResource;
use Illuminate\Support\Facades\DB;

class MealController extends Controller
{

    public function getFiltered(Request $request) {
        $meals = new Meal;
        $queries = [];
        $columns = [
            'state', 'created_at', 'responsible_waiter_id'
        ];

        foreach ($columns as $column) {
            if($request->has($column)) {
                $meals = $meals->where($column, $request[$column]);
                $queries[$column] = $request[$column];
            }
        }

        return MealResource::collection($meals->paginate(5))->appends($queries);
    }

    public function getActiveAndTerminated(Request $request) {
        return (MealResource::collection(Meal::where('state', ['active', 'terminated'])->paginate(5)))->response()->setStatusCode(200);
    }

    public function index() {
        return (MealResource::collection(Meal::orderBy('created_at', 'desc')->paginate(5)))->response()->setStatusCode(200);
    }

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
            'start' => date('Y-m-d H:i:s'),
            'end' => null,
            'responsible_waiter_id' => $responsible_waiter_id,
            'total_price_preview' => '0',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
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

    public function terminateMeal($meal_id){
       
        $ordersOfMealNotDelivered = DB::table('orders')
                            ->where('orders.meal_id', '=', $meal_id)
                            ->where('orders.state', '!=', 'delivered')
                            ->get();

        $deliveredMealOrdersPrice = DB::table('orders')
                            ->join('items', 'orders.item_id', '=', 'items.id')
                            ->where('orders.meal_id', '=', $meal_id)
                            ->where('orders.state', '=', 'delivered')
                            ->select('items.price')
                            ->get();
        
        $ordersOfMealDelivered = DB::table('orders')
                            ->where('orders.meal_id', '=', $meal_id)
                            ->where('orders.state', '=', 'delivered')
                            ->get();       
        $counter = 0;

        foreach ($deliveredMealOrdersPrice as $order) {
            $counter += $order->price;
        }
        

        foreach ($ordersOfMealNotDelivered as $order) {
            DB::table('orders')
            ->where('id', '=', $order->id)
            ->update(['end' => date('Y-m-d H:i:s'),
                      'state' => 'not delivered']);
        }

        DB::table('meals')
        ->where('id', '=', $meal_id)
        ->update(['end' => date('Y-m-d H:i:s'),
                  'state' => 'terminated',
                  'total_price_preview' => $counter]);

        Invoice::create([
            'state' => 'pending',
            'meal_id' => $meal_id,
            'nif' => null,
            'name' => null,
            'date' => date('Y-m-d'),
            'total_price' => $counter,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        foreach ($ordersOfMealDelivered as $order) {
            //criar um invoive item com estes dados
            /*
            InvoiceItem::create([
                'invoice_id' =>   ,
                'item_id' => $order->item_id,
                'unit_price'
            ])
            */
        }
    }


    public function declareMealAsNotPaid($mealId){
        try{
            $meal = Meal::findOrFail($mealId);
            
            $invoice = Invoice::findOrFail($meal->invoice->id);

            $orders = Order::where('meal_id', $meal->id)->get();
            
            $invoice->state = "not paid";

            $meal->state = "not paid";

            foreach ($orders as $order) {
                if($order->state != 'delivered'){
                    $order->state = 'not delivered';
                }
                $order->save();
            }

            if($invoice->save() && $meal->save())
            {
                return new MealResource($meal);
            }
        } catch (Exception $e) {
            Debugbar::addThrowable($e);
        }
    }
}
