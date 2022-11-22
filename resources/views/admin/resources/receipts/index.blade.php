@extends( 'admin.layout' )

@section( 'content' )
    <h3 class='buffer-0 inline-block'>Receipts, {{ $receipts_count }} total</h3>

    <hr/>
        @forelse( $receipts as $r )
            <?php
                $user = $r->user;
            ?>
            <div class='row'>
                <div class='col-lg-3'>
                    @include( 'admin.include.snippets.user', [ 'user' => $user ] )
                </div>
                <div class='col-lg-9'>
                    <span class='tag'>Type</span> {{ $r->receipt_type }}<br/>
                    <span class='tag'>Bundle ID</span> {{ $r->bundle_id }}<br/>
                    <span class='tag'>App Version</span> {{ $r->app_version }}<br/>
                    <span class='tag'>Contains Purchases</span> {{ $r->inAppPurchases()->count() }}

                    @if( $lp = $r->inAppPurchases()->orderBy( 'purchase_date', 'DESC' )->first() )
                        @if( $s = $lp->subscription )
                            <p>
                                <h5>Latest Purchase</h5>
                                @include( 'admin.include.snippets.inapp', [ 'id' => $s->product_id, 'name' => $s->name, 'purchase_date' => $lp->purchase_date, 'expires_date' => $lp->expires_date, 'is_trial_period' => $s->has_trial ? $lp->is_trial_period : null, 'valid' => $lp->valid, 'transaction_id' => $lp->transaction_id, 'original_transaction_id' => $lp->original_transaction_id, 'refunded' => $lp->refunded ] )
                            </p>
                        @endif
                    @endif

                    <h5>Renewal information</h5>
                    @forelse( $r->renewalInfo as $ri )
                        @include( 'admin.include.snippets.renewal_info', [ 'ri' => $ri ] )
                    @empty
                        No renewal information registered
                    @endif

                    <br/>
                    <a href="{{ route( 'admin.receipts.show', $r->id ) }}" class="btn btn-primary">Details</a> <span class='btn btn-danger btn-delete cursor' action-href="{{ route( 'admin.receipts.delete.do', $r->id ) }}"><i class='fa fa-trash right-buffer-10'></i>Delete</span>
                </div>
            </div>
            <hr/>
        @empty
            <p>No receipts at the moment</p>
        @endforelse

    <div class='row text-center'>
        <div class='col-xs-12'>
            {{ $receipts->appends( Input::except( 'page' ) )->links() }}
        </div>
    </div>

    @include( 'admin.include.modals.delete' )
@stop