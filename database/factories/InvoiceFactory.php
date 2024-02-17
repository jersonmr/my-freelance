<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\User;

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
            'subject' => $this->faker->word(),
            'due' => $this->faker->dateTime(),
            'payment_type' => $this->faker->word(),
            'items' => '{}',
            'tax' => $this->faker->numberBetween(-10000, 10000),
            'subtotal' => $this->faker->numberBetween(-10000, 10000),
            'total' => $this->faker->numberBetween(-10000, 10000),
            'paid_at' => $this->faker->dateTime(),
        ];
    }
}
