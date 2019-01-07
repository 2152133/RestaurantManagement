<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;
use App\Meal;
use App\Order;
use App\Http\Resources\Invoice as InvoiceResource;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Validator;

class InvoiceController extends Controller
{
    public function getPending(){
        // Get pending invoices
        $invoices = Invoice::where('state', 'pending')->orderBy('id', 'asc')->paginate(5);
        // Return collection of invoices as a resource
        return (InvoiceResource::collection($invoices))->response()->setStatusCode(200);
    }

    public function getPaid(){
        // Get pending invoices
        $invoices = Invoice::where('state', 'paid')->orderBy('id', 'asc')->paginate(5);
        // Return collection of invoices as a resource
        return (InvoiceResource::collection($invoices))->response()->setStatusCode(200);
    }

    public function getFiltered(Request $request) {
        $invoices = new Invoice;
        $queries = [];
        $columns = [
            'state', 'date', 'responsible_waiter_id'
        ];

        foreach ($columns as $column) {
            if($request->has($column)) {
                if ($column == 'start') {
                    try {
                        $invoices = $invoices->whereDate($column, Carbon::parse($request[$column]));
                    }catch (\Exception $e){
                        return response()->json(['error' => 'Invalid date format.'], 500);
                    }
                }
                elseif ($column == 'responsible_waiter_id') {
                        $invoices = $invoices->join('meals', 'invoices.meal_id', '=', 'meals.id')
                        ->where('meals.responsible_waiter_id', $request[$column]);
                }
                else {
                    $invoices = $invoices->where($column, $request[$column]);
                }
                $queries[$column] = $request[$column];
            }
        }
        return response()->json(InvoiceResource::collection($invoices->paginate(5))->appends($queries), 200);
    }

    public function declareInvoiceAsPaid(Request $request){

        $requestInvoice = json_decode($request->invoice, true);
        
        $rules = [
            'nif' => 'required|numeric|digits:9',
            'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/'
        ];

        $validator = Validator::make($requestInvoice, $rules);
    
        if ($validator->passes()) {
            $invoice = Invoice::findOrFail($requestInvoice['id']);
        
            $invoice->state = "paid";
            $invoice->nif = $requestInvoice['nif'];
            $invoice->name = $requestInvoice['name'];
            
            $meal = Meal::findOrFail($invoice->meal->id);
            $meal->state = "paid";

            if($invoice->save() && $meal->save())
            {
                return (new InvoiceResource($invoice))->response()->setStatusCode(200);
            }
        } else {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
    }

    public function declareInvoiceAsNotPaid($invoiceId){
        try{
            $invoice = Invoice::findOrFail($invoiceId);
            
            $invoice->state = "not paid";

            
            $meal = Meal::findOrFail($invoice->meal->id);
            $meal->state = "not paid";

            $orders = Order::where('meal_id', $meal->id)->get();

            foreach ($orders as $order) {
                if($order->state != 'delivered'){
                    $order->state = 'not delivered';
                }
                $order->save();
            }

            if($invoice->save() && $meal->save())
            {
                return new InvoiceResource($invoice);
            }
        } catch (Exception $e) {
            Debugbar::addThrowable($e);
        }
    }
}
