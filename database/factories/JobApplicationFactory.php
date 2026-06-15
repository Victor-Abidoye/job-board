<?php

namespace Database\Factories;

use App\Models\JobApplication;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobApplication>
 */
class JobApplicationFactory extends Factory
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
            'job_listing_id' => JobListing::factory(),
            'resume_path'=> fake()->filePath(),
            'cover_letter' => fake()->sentence(10),
            'status' => fake()->randomElement(['pending', 'reviewed', 'rejected', 'accepted'])
        ];
    }
}
