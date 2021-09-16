<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Expense::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = $this->faker->dateTimeBetween($startDate = '-3 years', $endDate = 'now', $timezone = 'America/Sao_Paulo');

        return [
            'user_id' => 1,
            'date' => date_format($date, 'Y-m-d'),
            'amount' => $this->faker->randomFloat(2, $min = 10, $max = 100),
            'description' => $this->faker->sentence($nbWords = 6, $variableNbWords = true)
        ];
    }
}
