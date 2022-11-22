@if( $s = $ri->subscription )
    <p>
        <span class='tag'>product id</span> <span style="color:black;font-size:15px"></span> {{ $s->product_id }}<br/>
        <span class='tag'>auto renew status</span>
        @if( $ri->auto_renew_status )
            <span class='badge badge-success'>yes</span><br/>
        @else
            <span class='badge badge-danger'>NOT AUTORENEWING</span><br/>
        @endif
        @if( $inapp = \App\InAppPurchase::where( 'original_transaction_id', $ri->original_transaction_id )->orderBy( 'expires_date', 'DESC' )->first() )
            <span class='tag'>expired at</span> {{ $inapp->expires_date }}<br/>
        @endif
        @if( $ri->expiration_intent )
            <span class='tag'>expiration intent</span> {{ \App\RenewalInfo::getExpirationIntentInfo( $ri->expiration_intent ) }}<br/>
        @endif
        <span class='tag'>billing retry?</span>
        @if( $ri->is_in_billing_retry_period )
            <span class='badge badge-danger'>IN BILLING RETRY</span><br/>
        @else
            no<br/>
        @endif
    </p>
@endif