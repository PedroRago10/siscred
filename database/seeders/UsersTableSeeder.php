<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'id' => 1,
            'name' => 'Dev',
            'email' => 'dev@bgengenharia.com',
            'password' => '123456',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
