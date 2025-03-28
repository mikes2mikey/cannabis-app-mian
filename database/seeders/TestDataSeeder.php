<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Plant;
use App\Models\GrowthLog;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        // Create test members
        $members = [];
        for ($i = 1; $i <= 5; $i++) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            
            $members[] = $user = User::create([
                'name' => "Test Member $i",
                'email' => "member$i@example.com",
                'password' => Hash::make('password'),
                'role' => 'member',
                'email_verified_at' => now(),
            ]);
            
            // Create user profile with faker data
            UserProfile::create([
                'user_id' => $user->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone_number' => $faker->phoneNumber,
                'id_number' => $faker->unique()->numerify('##########'),
                'date_of_birth' => $faker->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d'),
                'address' => $faker->streetAddress,
                'city' => $faker->city,
                'postal_code' => $faker->postcode,
            ]);
        }

        // Create plants and assign them to random members
        foreach ($members as $member) {
            for ($i = 1; $i <= 3; $i++) {
                $plant = Plant::create([
                    'strain' => "Test Strain $i",
                    'planting_date' => now()->subDays(rand(1, 90)),
                    'status' => ['growing', 'harvested'][rand(0, 1)],
                    'notes' => "Test notes for plant $i",
                ]);

                // Attach the member to the plant
                $plant->users()->attach($member->id);

                // Create growth logs for each plant
                for ($j = 1; $j <= 5; $j++) {
                    GrowthLog::create([
                        'plant_id' => $plant->id,
                        'date' => now()->subDays(rand(1, 90)),
                        'notes' => "Growth log entry $j",
                        'height' => rand(10, 100),
                        'phase' => ['seedling', 'vegetative', 'flowering', 'harvest'][rand(0, 3)],
                        'temperature' => rand(20, 30),
                        'humidity' => rand(40, 60),
                    ]);
                }
            }
        }
    }
}