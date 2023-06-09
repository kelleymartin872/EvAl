<?php

namespace Database\Factories;

use App\Models\Agency;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'agency_id' => $this->faker->randomElement([Agency::all()->random(), null]),
            'avatar' => $this->faker->image('public/storage', 1920, 1080, null, null, true),
            'name' => $this->faker->name(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'email' => $this->faker->unique()->safeEmail(),
            'email_is_verified' => $this->faker->boolean(),
            'phone_number' => $this->faker->phoneNumber(),
            'role' => $this->faker->randomElement(['administrator', 'moderator', 'user']),
            'last_seen' => $this->faker->dateTime(),
            
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
