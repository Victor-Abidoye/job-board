<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\JobCategory;
use App\Models\JobListing;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobListing>
 */
class JobListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'category_id' => JobCategory::factory(),
            'title' => fake()->jobTitle(),
            'description' => fake()->sentence(20),
            'type' => fake()->randomElement(['full-time', 'part-time', 'contract']),
            'location' => fake()->country(),
            'salary_min' => fake()->numberBetween(100, 200),
            'salary_max' => fn(array $attributes) =>
            fake()->numberBetween($attributes['salary_min'], 7000),
            'is_remote' => fake()->boolean(),
            'status' => fake()->randomElement(['draft', 'published', 'closed']),
            'expires_at' => fake()->dateTimeBetween('now', '+3 months')
        ];
    }
}
