<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bank;
use App\Models\Client;

class BankFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bank::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'location' => $this->faker->word(),
            'name' => $this->faker->name(),
            'swift' => $this->faker->word(),
            'number' => $this->faker->word(),
            'beneficiary_name' => $this->faker->word(),
            'beneficiary_address' => $this->faker->word(),
            'beneficiary_email' => $this->faker->word(),
        ];
    }
}
