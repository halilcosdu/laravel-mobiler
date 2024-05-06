<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class DeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'is_blocked' => $this->faker->boolean,
            'is_premium' => $this->faker->boolean,
            'timezone' => $this->faker->timezone,
            'os_type' => $this->faker->word,
            'os_version' => $this->faker->word,
            'device_name' => $this->faker->word,
            'device_type' => $this->faker->word,
            'app_version' => $this->faker->word,
            'client_device_code' => $this->faker->word,
            'language_code' => $this->faker->word,
            'country_code' => $this->faker->word,
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone_number' => $this->faker->phoneNumber,
        ];
    }
}
