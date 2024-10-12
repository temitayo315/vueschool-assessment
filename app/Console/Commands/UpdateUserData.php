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
        $batchSize = 1000;

        // Array to hold batched user updates
        $batches = [];
        $users = User::all();

        foreach ($users as $user) {
            // Generate random attributes
            $newFirstname = $faker->firstName;
            $newLastname = $faker->lastName;
            $newTimezone = $faker->randomElement($timezones);

            // Check if the user's attributes have changed
            if ($user->firstname !== $newFirstname || $user->lastname !== $newLastname || $user->timezone !== $newTimezone) {
                // Update user with new values
                $user->firstname = $newFirstname;
                $user->lastname = $newLastname;
                $user->timezone = $newTimezone;
                $user->save();

                $batches[] = [
                    'email' => $user->email,
                    'name' => $user->firstname . ' ' . $user->lastname,
                    'time_zone' => $user->timezone,
                ];
                
                $this->info("[{$user->id}] firstname: {$user->firstname}, lastname: {$user->lastname}, timezone: '{$user->timezone}'");

                // If the batch size limit is reached, simulate the API call
                if (count($batches) === $batchSize) {
                    $this->sendBatch($batches);
                    $batches = []; // Reset batch
                }
            }
        }
        
        if (count($batches) > 0) {
            $this->sendBatch($batches);
        }

        $this->info('User data updated and batched successfully!');
    }

    // Simulate sending a batch API request
    protected function sendBatch(array $batch)
    {
        // Log the batch API request structure
        $this->info("Simulating API call with " . count($batch) . " updates.");
        $this->info(json_encode(['batches' => [['subscribers' => $batch]]], JSON_PRETTY_PRINT));
    }
}