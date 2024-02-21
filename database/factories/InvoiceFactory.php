<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'client_id' => Client::factory(),
            'bank_id' => $this->faker->randomNumber(),
            'payment_gateway_id' => $this->faker->randomNumber(),
            'number' => $this->faker->word(),
            'project' => $this->faker->word(),
            'due' => $this->faker->date(),
            'currency' => $this->faker->word(),
            'payment_type' => $this->faker->word(),
            'items' => '{}',
            'tax' => $this->faker->numberBetween(-10000, 10000),
            'subtotal' => $this->faker->numberBetween(-10000, 10000),
            'total' => $this->faker->numberBetween(-10000, 10000),
            'paid' => $this->faker->boolean(),
        ];
    }
}
