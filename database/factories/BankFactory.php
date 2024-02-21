<?php

namespace Database\Factories;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'location' => $this->faker->word(),
            'name' => $this->faker->name(),
            'swift' => $this->faker->word(),
            'iban' => $this->faker->word(),
            'address' => $this->faker->word(),
            'beneficiary_name' => $this->faker->word(),
            'beneficiary_address' => $this->faker->word(),
            'beneficiary_email' => $this->faker->word(),
        ];
    }
}
