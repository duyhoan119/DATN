<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    { 
        return [ 
            // 'name'=> $this->faker->name(),
            // 'category_id' => $this->faker->unique(),
            // 'sku' => rand(0, 1),
            // 'import_price' => $this->faker->numberBetween($min = 100000, $max = 1000000000),
            // 'price' => $this->faker->numberBetween($min = 100000, $max = 1000000000),
            // 'quantity' => rand(0, 4),
            // 'description' => $this->faker->text(200),
            // 'status' => rand(0, 1),
            // 'warranty_date' => $this->faker->text(20),
        ];
    }
}
