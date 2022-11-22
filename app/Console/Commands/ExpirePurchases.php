<?php

namespace App\Console\Commands;

use App\InAppPurchase;
use Illuminate\Console\Command;

class ExpirePurchases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purchases:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks the expiration date for all of the valid purchases and expires them in case they are no longer valid';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach( InAppPurchase::where( 'valid', true )->where( 'non_consumable_id', null )->get() as $inapp ){
            if( $inapp->refunded ){
                $inapp->valid = false;
                $inapp->save();
                continue;
            }

            $expires_date = \Carbon::parse( $inapp->expires_date );
            $now_date = \Carbon::now( 'UTC' );

            if( $now_date > $expires_date ){
                $user = $inapp->receipt->user;
                $subscription = $inapp->subscription;

                $inapp->valid = false;
                $inapp->save();
                \Log::info( 'Inapp #' . $inapp->id . ' is no longer valid' );

                \App\Helper\reportAmplitudeEvent( 'Subscription Period End', $user->id,
                    [   'subscription_id' => $subscription->product_id ],
                    [   'active_subscription_id' => 'expired',
                        'on_trial' => false ] );
            }
        }
    }
}
