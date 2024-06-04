<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // get a random customer id
        $customer = \App\Models\Customer::inRandomOrder()->first();
        return [
            'number' => rand(10, 9999),
            'customer_id' => $customer->id,
            'date' => $this->faker->date(),
            'due_date' => $this->faker->date(),
            'reference' => 'REF-1000' . rand(1000, 9999),
            'terms_and_conditions' => $this->faker->sentence,
            'sub_total' => $this->faker->randomFloat(2, 100, 1000),
            'discount' => $this->faker->randomFloat(2, 10, 100),
            'total' => $this->faker->randomFloat(2, 100, 1000),
        ];
    }
}
