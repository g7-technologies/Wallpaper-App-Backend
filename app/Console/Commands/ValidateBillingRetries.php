<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Controllers\APIPaymentController;
use ReceiptValidator\iTunes\Validator as iTunesValidator;
use App\Receipt;
use App\RenewalInfo;

class ValidateBillingRetries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receipts:validate_billing_retries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks only the receipts in billing retry mode';

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
        if( \App::environment( [ 'local', 'staging' ] ) )
            $validator = new iTunesValidator( iTunesValidator::ENDPOINT_SANDBOX );
        else
            $validator = new iTunesValidator( iTunesValidator::ENDPOINT_PRODUCTION );
        $sharedSecret = env( 'APPSTORE_SHARED_SECRET', null );

        foreach( RenewalInfo::where( 'is_in_billing_retry_period', true )->get() as $renew_i ){
            $r = $renew_i->receipt;
            if( !$r )
                continue;

            try {
              $response = $validator->setSharedSecret( $sharedSecret )->setReceiptData( $r->receipt )->validate();
              if( $response->isValid() ){
                APIPaymentController::parseReceipt( $response, $r );
              }
            }
            catch( \Exception $e ){
            }
        }
    }
}
