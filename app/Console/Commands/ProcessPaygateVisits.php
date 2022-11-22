<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessPaygateVisits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paygate_visits:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process paygate visits and send marketing push notifications if relevant';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public static function sendPush( $user, $special = false ){
        /*
        if( !in_array( $user->locale, [ 'en' ] ) )
            return;
        */
        
        if( $special )
            $push_array = trans( 'pushes_monetary.special_offers', [], $user->locale );
        else
            $push_array = trans( 'pushes_monetary.reminders', [], $user->locale );

        shuffle( $push_array );

        foreach( $push_array as $pattern ){
            $title = $pattern[ 'title' ];
            $body = $pattern[ 'body' ];

            \App\Helper\sendPushNotification( $user->notification_key, $title, $body );
            return;
        }

        return;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        while( true ){
            // Напоминания о пейгейте
            foreach( \App\PaygateVisit::where( 'processed', false )->get() as $pv ){
                $now = \Carbon::now();
                $last = \Carbon::parse( $pv->last_ping_time );
                if( $now->diffInSeconds( $last ) >= 10 ){


                    // Ищем пользователя или анонима с этим random_string
                    if( $target = \App\User::where( 'random_string', $pv->random_string )->first() ){
                        $this->info( 'Sent to user #' . $target->id );
                        self::sendPush( $target );
                    }
                    elseif( $target = \App\Anonymous::where( 'random_string', $pv->random_string )->first() ){
                        $this->info( 'Sent to anonymous #' . $target->id );
                        self::sendPush( $target );
                    }

                    $this->info( 'PV #' . $pv->id . ' processed' );
                    $pv->processed = true;
                    $pv->save();
                }
            }

            // Пуши о спецпредложениях
            foreach( \App\PaygateVisit::where( 'processed_special', false )->where( 'created_at', '<', \Carbon::now()->subMinutes( env( 'SPECIAL_OFFER_DELAY_MINS', 10 ) ) )->get() as $pv ){
                // Ищем пользователя или анонима с этим random_string
                if( $target = \App\User::where( 'random_string', $pv->random_string )->first() ){
                    ProcessPaygateVisits::sendPush( $target, true );
                }
                elseif( $target = \App\Anonymous::where( 'random_string', $pv->random_string )->first() ){
                    ProcessPaygateVisits::sendPush( $target, true );
                }

                $this->info( 'PV #' . $pv->id . ' processed' );
                $pv->processed_special = true;
                $pv->save();
            }
            sleep( 1 );
        }
    }
}
