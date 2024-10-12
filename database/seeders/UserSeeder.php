<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $timezones = ['CET', 'CST', 'GMT+1']; // Define the timezones

        foreach (range(1, 20) as $index) {
            User::create([
                'firstname' => $faker->firstName,
                'lastname'  => $faker->lastName,
                'email'     => $faker->unique()->safeEmail,
                'password'  => $faker->password,
                'timezone'  => $faker->randomElement($timezones),
            ]);
        }
    }
}