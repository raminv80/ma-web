{block name=body}
<div class="container_16">
	<div class="grid_16">
		<h1>Locations</h1>
		<div class="grid_8 map-locations">
			{$content}
		</div>
		<div class="grid_8 store-addresses">
		{counter start=1 skip=1 assign="count"}
		{foreach key=key  item=loc from=$data.locations}
			{if $loc}
				<div class="funk-address{if $count == 2}{counter start=1 skip=1 assign='count'} last{else}{counter}{/if}">
					<div class="content">
						<p>{$loc.store_address}</p> 
						<p>P {$loc.store_phone}</p>  
						<p>F {$loc.store_fax}</p> 
						<p>E <a href="mailto:{$loc.store_email}">email us</a></p> 
						<p>{$loc.store_open}</p> 
					</div>
				</div>
			{/if}
		{/foreach}
		</div>
		<div class="clear">&nbsp;</div>
	</div>
	<!-- end .grid_16 -->
	<div class="clear">&nbsp;</div>
</div>
{/block}
