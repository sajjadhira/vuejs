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
        return response()->json([
            'message' => 'store'
        ], 200);
    }
}
