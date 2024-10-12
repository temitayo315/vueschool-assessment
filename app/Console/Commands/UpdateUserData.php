<?php

namespace App\Console\Commands;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Console\Command;

class UpdateUserData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:update-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user data with random firstname, lastname, and timezone';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $faker = Faker::create();
        $timezones = ['Europe/Amsterdam', 'America/Los_Angeles', 'Asia/Tokyo'];

        $users = User::all(); // Fetch all users

        foreach ($users as $user) {
            $user->firstname = $faker->firstName;
            $user->lastname = $faker->lastName;
            $user->timezone = $faker->randomElement($timezones);
            $user->save();

            // Log the updated user info
            $this->info("User [{$user->id}] updated: firstname: {$user->firstname}, lastname: {$user->lastname}, timezone: '{$user->timezone}'");
        }

        $this->info('User data updated successfully!');

    }
}