<h5>Search Ads Attribution record</h5>
<span class='tag'>Conversion</span> {{ $sai->getAttribute( 'iad-conversion-date' ) ?? 'not set' }} (<span class='tag'>type</span> {{ $sai->getAttribute( 'iad-conversion-type' ) ?? 'not set' }}, <span class='tag'>click</span> {{ $sai->getAttribute( 'iad-click-date' ) ?? 'not set' }})<br/>
<span class='tag'>Country/region</span> {{ $sai->getAttribute( 'iad-country-or-region' ) ?? 'not set' }}<br/>
<span class='tag'>Campaign</span> {{ $sai->getAttribute( 'iad-campaign-name' ) ?? 'not set' }} (<span class='tag'>id</span> {{ $sai->getAttribute( 'iad-campaign-id' ) ?? 'not set' }})<br/>
<span class='tag'>Ad group</span> {{ $sai->getAttribute( 'iad-adgroup-name' ) ?? 'not set' }} (<span class='tag'>id</span> {{ $sai->getAttribute( 'iad-adgroup-id' ) ?? 'not set' }})<br/>
<span class='tag'>Keyword</span> {{ $sai->getAttribute( 'iad-keyword' ) ?? 'not set' }} (<span class='tag'>id</span> {{ $sai->getAttribute( 'iad-keyword-id' ) ?? 'not set' }}, <span class='tag'>matchtype</span> {{ $sai->getAttribute( 'iad-keyword-matchtype' ) ?? 'not set' }})<br/>
<span class='tag'>Creative set</span> {{ $sai->getAttribute( 'iad-creativeset-name' ) ?? 'not set' }} (<span class='tag'>id</span> {{ $sai->getAttribute( 'iad-creativeset-id') ?? 'not set' }})<br/>
<span class='tag'>Line item</span> {{ $sai->getAttribute( 'iad-lineitem-name' ) ?? 'not set' }} (<span class='tag'>id</span> {{ $sai->getAttribute( 'iad-lineitem-id' ) ?? 'not set' }})
<hr/>