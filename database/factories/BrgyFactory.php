<?php

namespace Database\Factories;

use App\Models\Brgy;
use Illuminate\Database\Eloquent\Factories\Factory;

class BrgyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Brgy::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'brgyName' => 'SAN FRANCISCO'
        ];
    }
}
