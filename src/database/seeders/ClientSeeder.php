<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 50; $i++) {
            $phone = $faker->unique()->phoneNumber;
            $normalize = preg_replace('/\D/', '', $phone);

            Client::create([
                'phone' => $normalize,
                'name' => $faker->name(),
                'last_name' => $faker->lastName(),
                'email' => $faker->email()
            ]);
        }
    }
}
