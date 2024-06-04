<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceItem>
 */
class InvoiceItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $invoice = \App\Models\Invoice::inRandomOrder()->first();
        $product = \App\Models\Product::inRandomOrder()->first();
        $quantity = rand(1, 10);
        return [
            'invoice_id' => $invoice->id,
            'product_id' => $product->id,
            'unit_price' => $product->unit_price,
            'quantity' => $quantity,
        ];
    }
}
