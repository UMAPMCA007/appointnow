<?php

namespace Database\Factories;

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
    public function definition(): array
    {
        return [
            'name' => "midhun chacko",
            'email' => "midhun@gmail.com",
            'email_verified_at' => now(),
            'password' => bcrypt("9544070825"),
            'remember_token' => Str::random(10),
            'code' => Str::random(10)
        ];
    }
}
