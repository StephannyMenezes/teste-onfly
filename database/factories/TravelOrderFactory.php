<?php

namespace Database\Factories;

use App\Enum\TravelOrderStatusEnum;
use App\Models\TravelOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TravelOrder>
 */
class TravelOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'requester_name' => fake()->name(),
            'destination' => fake()->city(),
            'status' => TravelOrderStatusEnum::REQUESTED->value,
            'notification_email' => null,
            'departure_date' => fake()->dateTimeBetween('now', '+1 month'),
            'return_date' => fake()->dateTimeBetween('+1 month', '+2 months'),
        ];
    }
}
