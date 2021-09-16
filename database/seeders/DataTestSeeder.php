<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Client;
use App\Models\Expense;
use App\Models\Service;
use App\Models\ServiceOrder;
use Illuminate\Database\Seeder;

class DataTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::factory(10)->create(['user_id' => 1]);
        Client::factory(10)->create(['user_id' => 1]);
        Service::factory(10)->create(['user_id' => 1]);

        for ($i=0; $i < 100; $i++) {
            $cityId = rand(1, 10);
            $clientId = rand(1, 10);
            $serviceId = rand(1, 10);

            ServiceOrder::factory(10)->create(['city_id' => $cityId, 'client_id' => $clientId, 'service_id' => $serviceId]);
        }

        Expense::factory(1000)->create();
    }
}
