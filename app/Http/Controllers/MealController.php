<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Order;
use App\Http\Resources\Meal as MealResource;
use App\Invoice;
use App\Meal;
use Illuminate\Support\Facades\DB;
use App\InvoiceItems;

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

        return (MealResource::collection($meals->paginate(5))->appends($queries))->response()->setStatusCode(200);
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
        return (MealResource::collection($meals))->response()->setStatusCode(200);
    }

    public function createMeal($table_number, $responsible_waiter_id)
    {
        $meal = Meal::create([
                'state' => 'active',
                'table_number' => $table_number,
                'start' => date('Y-m-d H:i:s'),
                'end' => null,
                'responsible_waiter_id' => $responsible_waiter_id,
                'total_price_preview' => '0',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
        ]);
        return new MealResource($meal);
    }

    public function waiterMeals($user_id)
    {
        $waiterMeals = DB::table('meals')
                    ->where('meals.responsible_waiter_id', '=', $user_id)
                    ->where('meals.state', '=', 'active')
                    ->paginate(5);
        return response()->json($waiterMeals, 201);
    }

    public function getTablesWitoutActiveMeals()
    {
        $tablesWithActiveMeals = DB::table('restaurant_tables')
                                ->whereNotIn('table_number', function($q){
                                    $q->select('table_number')
                                    ->from('meals')
                                    ->where('state', '=', 'active');
                                })
                                ->select('table_number')
                                ->get();
        return response()->json($tablesWithActiveMeals, 200);
    }

    public function terminateMeal($meal_id)
    {

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

        $counterTotalPrice = 0;

        foreach ($deliveredMealOrdersPrice as $order) {
            $counterTotalPrice += $order->price;
        }

        Invoice::create([
            'state' => 'pending',
            'meal_id' => $meal_id,
            'nif' => null,
            'name' => null,
            'date' => date('Y-m-d'),
            'total_price' => $counterTotalPrice,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        //Ir buscar o invoice ID que acabamos de inserir
        $createdInvoiceID = DB::table('invoices')
            ->where('meal_id', '=', $meal_id)
            ->select('id')
            ->get();

        foreach($ordersOfMealDelivered as $order){

            $priceOfItem = DB::table('items')
            ->where('id', '=', $order->item_id)
            ->select('price')
            ->get();

            $invoiceItemIfExists = DB::table('invoice_items')
            ->where('invoice_id', '=', $createdInvoiceID->pluck("id")[0])
            ->where('item_id', '=', $order->item_id)
            ->get();

            //dd($invoiceItemIfExists->pluck("items"));

            if($invoiceItemIfExists->isEmpty()){

                InvoiceItems::create([
                    'invoice_id' => $createdInvoiceID->pluck("id")[0],
                    'item_id' => $order->item_id,
                    'quantity' => 1,
                    'unit_price' => $priceOfItem->pluck("price")[0],
                    'sub_total_price' => $priceOfItem->pluck("price")[0]
                ]);
            }
            else{
                

                DB::table('invoice_items')
                ->where('invoice_id', '=', $invoiceItemIfExists->pluck("invoice_id")[0])
                ->update([
                    'quantity' => $invoiceItemIfExists->pluck("quantity")[0] + 1,
                    'sub_total_price' => (($invoiceItemIfExists->pluck("quantity")[0]) + 1) *  ($invoiceItemIfExists->pluck("unit_price")[0])
                ]);                
            }
        }

        DB::table('meals')
            ->where('id', '=', $meal_id)
            ->update(['end' => date('Y-m-d H:i:s'),
                'state' => 'terminated',
                'total_price_preview' => $counterTotalPrice
            ]);
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
