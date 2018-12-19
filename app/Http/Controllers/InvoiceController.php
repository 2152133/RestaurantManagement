<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;
use App\Meal;
use App\Http\Resources\Invoice as InvoiceResource;

class InvoiceController extends Controller
{
    public function getPending(){
        // Get pending invoices
        $invoices = Invoice::where('state', 'pending')->orderBy('created_at', 'asc')->paginate(5);

        // Return collection of invoices as a resource
        return InvoiceResource::collection($invoices);
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
}
