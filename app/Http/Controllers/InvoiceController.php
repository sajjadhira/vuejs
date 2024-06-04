<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('q')) {
            $invoices = \App\Models\Invoice::with('customer')
                ->where('id', 'like', '%' . $request->q . '%')
                ->orWhere('number', 'like', '%' . $request->q . '%')
                ->orWhere('total', 'like', '%' . $request->q . '%')
                ->orWhere('created_at', 'like', '%' . $request->q . '%')
                ->orderBy('id', 'desc')
                ->get();
            return response()->json([
                'invoices' => $invoices
            ], 200);
        }

        $invoices = \App\Models\Invoice::with('customer')->orderBy('id', 'desc')->get();
        return response()->json([
            'invoices' => $invoices
        ], 200);
    }


    public function store(Request $request)
    {
        $request->validate([
            'invoice_items' => 'required',
            'customer_id' => 'required',
            'date' => 'required',
            'due_date' => 'required',
            'number' => 'required',
            'reference' => 'required',
            'subtotal' => 'required',
            'discount' => 'required',
            'total' => 'required',
            'terms_and_conditions' => 'required',
        ]);

        $invoice = new \App\Models\Invoice();
        $invoice->customer_id = $request->customer_id;
        $invoice->date = $request->date;
        $invoice->due_date = $request->due_date;
        $invoice->number = $request->number;
        $invoice->reference = $request->reference;
        $invoice->sub_total = $request->subtotal;
        $invoice->discount = $request->discount;
        $invoice->total = $request->total;
        $invoice->terms_and_conditions = $request->terms_and_conditions;
        $invoice->save();

        foreach (json_decode($request->invoice_items) as $item) {
            $invoiceItem = new \App\Models\InvoiceItem();
            $invoiceItem->invoice_id = $invoice->id;
            $invoiceItem->product_id = $item->id;
            $invoiceItem->quantity = $item->quantity;
            $invoiceItem->unit_price = $item->unit_price;
            $invoiceItem->save();
        }

        return response()->json([
            'message' => 'Invoice created successfully'
        ], 201);
    }
}
