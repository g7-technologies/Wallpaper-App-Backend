<?php

use Illuminate\Database\Seeder;

class SubscriptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subscriptions')->insert( [
            'name' => 'Monthly with trial',
            'product_id' => 'com.yourhoro.monthly_w_trial',
            'billing_period' => \App\Subscription::BILLING_MONTHLY,
            'has_trial' => true,
            'trial_period' => \App\Subscription::TRIAL_3_DAYS
        ] );
    }
}
