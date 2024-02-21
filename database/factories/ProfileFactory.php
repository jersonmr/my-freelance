<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Profile::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'username' => $this->faker->userName(),
            'about' => $this->faker->text(),
            'avatar' => $this->faker->text(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->word(),
            'city' => $this->faker->city(),
            'state' => $this->faker->word(),
            'zip' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            'notifications' => $this->faker->boolean(),
        ];
    }
}
