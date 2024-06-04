<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/customers', function () {
    $customers = \App\Models\Customer::orderBy('id', 'desc')->get();
    return response()->json([
        'customers' => $customers
    ], 200);
});

Route::get('/products', function () {
    $items = \App\Models\Product::orderBy('id', 'desc')->get();
    return response()->json([
        'products' => $items
    ], 200);
});

Route::get('/invoices', [InvoiceController::class, 'index']);
Route::post('/invoices/store', [InvoiceController::class, 'store']);
