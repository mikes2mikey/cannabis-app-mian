<?php

namespace Database\Seeders;

use App\Models\MembershipPlan;
use Illuminate\Database\Seeder;

class MembershipPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic Membership',
                'description' => 'Perfect for casual cannabis enthusiasts. Includes basic tracking features and community access.',
                'price' => 99.99,
                'duration_days' => 30,
                'is_active' => true,
                'is_recurring' => true, // Monthly recurring subscription
            ],
            [
                'name' => 'Premium Membership',
                'description' => 'Enhanced features for serious growers. Includes detailed analytics, priority support, and advanced tracking tools.',
                'price' => 199.99,
                'duration_days' => 90,
                'is_active' => true,
                'is_recurring' => false, // One-time payment
            ],
            [
                'name' => 'Annual Membership',
                'description' => 'Our best value option. Full access to all premium features for an entire year with significant savings.',
                'price' => 599.99,
                'duration_days' => 365,
                'is_active' => true,
                'is_recurring' => true, // Yearly recurring subscription
            ],
        ];

        foreach ($plans as $plan) {
            MembershipPlan::updateOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }
    }
} 