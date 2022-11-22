@extends( 'admin.layout' )

@section( 'content' )
<h3 class='buffer-0 bottom-buffer'>{{ $cohort->name }} LTV</h3>
<div class='row'>
	<div class='col-lg-4'>
		<span class='tag'>Cohort name</span>
		{{ $cohort->name }}<br/>
		<span class='tag'>Created at</span>
		{{ $cohort->created_at }}<br/>
		<span class='tag'>Number of users</span>
		{{ $cohort->users()->count() }}<br/>
		<span class='tag'>Total value</span>
		${{ @$stats[ 'purchases' ][ 'all' ][ 'value' ] }}<br/>
		<span class='tag'>Cohort user LTV</span>
		${{ @round( $stats[ 'purchases' ][ 'all' ][ 'value' ] / $cohort->users()->count(), 2 ) }}<br/>
	</div>
	<div class='col-lg-8'>
		@if( @$stats[ 'trials' ][ 'subscriptions' ] )
			<div class='bottom-buffer'>
				<h4>Trial starts</h4>
				@foreach( $stats[ 'trials' ][ 'subscriptions' ] as $name => $count )
					<span class='tag'>Product</span> {{ $name }} <span class='tag'>Trials started</span> {{ $count }}<br/>
				@endforeach
			</div>
		@endif

		<div class='bottom-buffer'>
			<h4>Purchases</h4>
			@foreach( @$stats[ 'purchases' ][ 'subscriptions' ] as $name => $info )
				<span class='tag'>Product</span> {{ $name }} <span class='tag'>subscriptions purchased</span> {{ $info[ 'number' ] }} <span class='tag'>total value</span> ${{ $info[ 'value' ] }}<br/>
			@endforeach
		</div>

		@if( @$stats[ 'conversions' ][ 'trial_to_purchase' ] )
			<div class='bottom-buffer'>
				<h4>Trial conversions</h4>
				@foreach( $stats[ 'conversions' ][ 'trial_to_purchase' ] as $name => $cnv )
					<span class='tag'>Product</span> {{ $name }} <span class='tag'>conversion to purchase</span> {{ $cnv }}<br/>
				@endforeach
			</div>
		@endif
	</div>
</div>

<div>
	<span class='cursor' onclick="$(this).fadeOut( function(){ $('#breakdowns').fadeIn(); } );"><u>show breakdowns</u></span>
</div>

<div id='breakdowns' style="display:none;">
	<div class='row top-buffer'>
		<div class='col-lg-12'>
			<h4>All purchases breakdown (${{ $stats[ 'purchases' ][ 'all' ][ 'value' ] }})</h4>
			@foreach( $stats[ 'breakdowns' ][ 'all' ] as $number => $info )
				<?php $percentage = round( ( $info[ 'number' ] / $stats[ 'purchases' ][ 'all' ][ 'number' ] ) * 100, 0 ); ?>
				<span class='min-w-100'><span class='tag'>{{ $percentage }}%</span> of purchases belong to <span class='tag'>{{ \App\Helper\addOrdinalNumberSuffix( $number ) }}</span> purchase (<span class='tag'>${{ $info[ 'value' ] }}</span>)</span>
				<div class="progress inline-block w-100p" style="background-color:#fff">
					<div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">{{ $info[ 'number' ] }}</div>
				</div>
			@endforeach
		</div>	
	</div>

	@foreach( $stats[ 'breakdowns' ][ 'subscriptions' ] as $subscription => $breakdown )
	<div class='row top-buffer'>
		<div class='col-lg-12'>
			<h4>{{ $subscription }} breakdown (${{ $stats[ 'purchases' ][ 'subscriptions' ][ $subscription ][ 'value' ] }})</h4>
			@foreach( $breakdown as $number => $info )
				<?php $percentage = round( ( $info[ 'number' ] / $stats[ 'purchases' ][ 'subscriptions' ][ $subscription ][ 'number' ] ) * 100, 0 ); ?>
				<span class='min-w-100'><span class='tag'>{{ $percentage }}%</span> of purchases belong to <span class='tag'>{{ \App\Helper\addOrdinalNumberSuffix( $number ) }}</span> purchase (<span class='tag'>${{ $info[ 'value' ] }}</span>)</span>
				<div class="progress inline-block w-100p" style="background-color:#fff">
					<div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">{{ $info[ 'number' ] }}</div>
				</div>
			@endforeach
		</div>	
	</div>
	@endforeach
	<br/><br/><br/>
</div>

@stop