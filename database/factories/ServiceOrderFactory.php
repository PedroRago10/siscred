<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Client;
use App\Models\ServiceOrder;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceOrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceOrder::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = $this->faker->dateTimeBetween($startDate = '-3 years', $endDate = 'now', $timezone = 'America/Sao_Paulo');
        $deadline = random_int(7, 30);
        $scheduledDays = random_int(0, 7);

        return [
            'code' => $this->faker->ean13,
            'user_id' => 1,
            'client_id' => Client::factory(),
            'city_id' => City::factory(),
            'service_id' => Service::factory(),
            'amount' => $this->faker->randomFloat(2, $min = 100, $max = 200),
            'displacement' => $this->faker->randomFloat(2, $min = 60, $max = 100),
            'published_at' => $date,
            'deadline' => $deadline,
            'scheduled_at' => date_add($date,date_interval_create_from_date_string($scheduledDays." days")),
            'inspection' => $this->faker->boolean(),
            'report' => $this->faker->boolean(),
            'delivered_at' => date_add($date,date_interval_create_from_date_string(random_int($scheduledDays, $deadline).' days')),
            'finished' => $this->faker->boolean()
        ];
    }
}
