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


    public function show($id)
    {
        $invoice = \App\Models\Invoice::with('customer')->find($id);
        $invoiceItems = \App\Models\InvoiceItem::with('product')->where('invoice_id', $id)->get();
        return response()->json([
            'invoice' => $invoice,
            'invoice_items' => $invoiceItems
        ], 200);
    }

    public function removeItem($id)
    {
        $invoiceItem = \App\Models\InvoiceItem::find($id);
        $invoiceItem->delete();
        return response()->json([
            'message' => 'Item removed successfully'
        ], 200);
    }

    public function update(Request $request, $id)
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

        $invoice = \App\Models\Invoice::find($id);
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

        \App\Models\InvoiceItem::where('invoice_id', $id)->delete();

        foreach (json_decode($request->invoice_items) as $item) {
            $invoiceItem = new \App\Models\InvoiceItem();
            $invoiceItem->invoice_id = $invoice->id;
            $invoiceItem->product_id = $item->product_id;
            $invoiceItem->quantity = $item->quantity;
            $invoiceItem->unit_price = $item->unit_price;
            $invoiceItem->save();
        }

        return response()->json([
            'message' => 'Invoice updated successfully'
        ], 200);
    }

    public function destroy($id)
    {
        $invoice = \App\Models\Invoice::find($id);
        $invoice->delete();
        return response()->json([
            'success' => true, // to handle the response in the frontend to show a success message 'Invoice deleted successfully
            'message' => 'Invoice deleted successfully'
        ], 200);
    }
}
