<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


    protected $model = Project::class;

    public function definition()
    {
        return [
            'title' => fake()->sentence(2),
            'description' => fake()->sentences(3, true),
            'owner_id' => function() {
                return User::factory()->create()->id;
            }
        ];
    }
}
