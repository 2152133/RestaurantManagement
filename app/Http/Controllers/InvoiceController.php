<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;
use App\Meal;
use App\Order;
use App\Http\Resources\Invoice as InvoiceResource;

class InvoiceController extends Controller
{
    public function getPending(){
        // Get pending invoices
        $invoices = Invoice::where('state', 'pending')->orderBy('id', 'asc')->paginate(5);
        // Return collection of invoices as a resource
        return InvoiceResource::collection($invoices);
    }

    public function getPaid(){
        // Get pending invoices
        $invoices = Invoice::where('state', 'paid')->orderBy('id', 'asc')->paginate(5);
        // Return collection of invoices as a resource
        return InvoiceResource::collection($invoices);
    }

    public function getFiltered(Request $request) {
        $invoices = new Invoice;
        $queries = [];
        $columns = [
            'state', 'created_at', 'responsible_waiter_id'
        ];

        foreach ($columns as $column) {
            if($request->has($column)) {
                $invoices = $invoices->where($column, $request[$column]);
                $queries[$column] = $request[$column];
            }
        }

        return InvoiceResource::collection($invoices->paginate(5))->appends($queries);
    }

    public function declareInvoiceAsPaid(Request $request){
        try{
            $requestInvoice = json_decode($request->invoice);

        
            if(empty($requestInvoice->nif) || empty($requestInvoice->name)){
                abort(400, 'Client nif and name required');
            }

            $invoice = Invoice::findOrFail($requestInvoice->id);
            
            $invoice->state = "paid";
            $invoice->nif = $requestInvoice->nif;
            $invoice->name = $requestInvoice->name;
            
            $meal = Meal::findOrFail($invoice->meal->id);
            $meal->state = "paid";

            if($invoice->save() && $meal->save())
            {
                return new InvoiceResource($invoice);
            }
        } catch (Exception $e) {
            Debugbar::addThrowable($e);
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
