<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Client;
use App\Models\Invoice;

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
            'client_id' => Client::factory(),
            'number' => $this->faker->word(),
            'subject' => $this->faker->word(),
            'due' => $this->faker->date(),
            'paid' => $this->faker->boolean(),
            'total' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
