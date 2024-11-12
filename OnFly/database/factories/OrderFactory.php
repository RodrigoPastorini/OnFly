<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'destination' => $this->faker->city,
            'departure' => $this->faker->dateTimeBetween('now', '+1 month'),
            'return' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'status' => 'pending',
        ];
    }
}
