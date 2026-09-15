<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone_number' => fake()->unique()->phoneNumber(),
            'whatsapp' => fake()->phoneNumber(),
            'password' => "1234567890",
            'profile_picture' => fake()->imageUrl(200, 200, 'users'),
            'is_seller' => false, 
            'status' => 'active',
        ];
    }

    public function seller(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_seller' => true,
        ]);
    }
}