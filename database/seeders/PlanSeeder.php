<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $freePlan = Plan::create([
            'name' => 'Free Plan',
            'stripe_price_id' => null,
            'price' => 0,
            'description' => '3 Months Free Plan',
        ]);

        $standardPlan = Plan::create([
            'name' => 'Standard Plan',
            'stripe_price_id' => null,
            'price' => 100,
            'description' => '3 Months Standard Plan',
        ]);

    }
}
