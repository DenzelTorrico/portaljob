<?php

namespace Database\Factories;

use App\Models\Jobs;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Applications>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'job_id' => Jobs::factory(),
            'cv' => $this->faker->word . '.pdf', // Assuming you are storing CV file names
            'message' => $this->faker->paragraph,
        ];
    }
}
