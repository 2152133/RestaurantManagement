<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;
use App\Http\Resources\Invoice as InvoiceResource;

class InvoiceController extends Controller
{
    public function getPending(){
        // Get pending invoices
        $invoices = Invoice::where('state', 'not paid')->orderBy('created_at', 'asc')->paginate(5);

        // Return collection of orders as a resource
        return InvoiceResource::collection($invoices);
    }
}
