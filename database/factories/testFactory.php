<?php

namespace Database\Factories;

use App\Models\Customers;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class testFactory extends Factory
{
    protected $model = Customers::class;

    public function definition(): array
    {
        return [
            'customer_name' => $this->faker->sentence(15),
            'phone' => $this->faker->sentence(10),
        ];
    }
}