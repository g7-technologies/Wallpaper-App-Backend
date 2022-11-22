<div class=''>
	<a href="{{ route( 'admin.users.show', $user->id ) }}">
	    {{ $user->name }}<br/>
	    @if( $user->zodiac )
	    	{{ \App\User::$zodiacs[ $user->zodiac ] }} {{ \App\User::$zodiac_names[ $user->zodiac ] }}
	    @endif
	    @if( $user->gender )
	    	{{ \App\User::$genders[ $user->gender ] }}<br/>
	    @endif
	    @if( $user->tester )
	    	<span class='badge badge-secondary'>tester</span><br/>
	    @endif
	</a>
</div>