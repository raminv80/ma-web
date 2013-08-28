{block name=body}
{* Define the function *}
{function name=render_menu level=0 menu=0}
	{foreach $items as $item}
		{if $level lt 1}
		
			{if $item.url eq 'our-locations'}
				{call name=render_menu items=$item.subs level=$level+1 parenturl=$parenturl|cat:"/"|cat:$item.url menu=2}
			{/if}
		{else}
			{if $menu eq 2}
				{if $item.category eq 1 and $item.listings eq 1}
				<a data-option-value=".{$item.category_name|strtolower}" id="{$item.category_name|strtolower}" class="button1">{$item.category_name}</a>
				{call name=render_menu items=$item.subs level=$level+1 parenturl=$parenturl menu=$menu}
				{/if}
			{/if}
		{/if}
	{/foreach}	
{/function}
{* Define the function *}
{function name=render_locations parentclass="" parenturl="" count="0"}
	{foreach $items as $item}
		{foreach $item.listings as $l}
		{if $count eq 2}{assign var=count value=0}{/if}
		{assign var=count value=$count+1}
		<div class="span5 loc all {$item.category_name|strtolower} {$parentclass|strtolower}{if $count eq 1} first{/if}" latitude="{$l.location_latitude}" longitude="{$l.location_longitude}" title="{$l.listing_title}" pin="{$l.location_pin}">
  			<img src="{$l.listing_image}" alt="{$l.listing_title}" />
  			<div class="loctop">
  				<div class="loctitle">{$l.listing_title}</div>
  				<div class="loctext">{$l.listing_content1}
  				</div>
  			</div>
  			{if $l.listing_flag1 eq 1}<div title="New" class="new"></div>{/if}
  			<a href="{$parenturl}/{$l.listing_url}"><div class="button3">Read More</div></a>
 		</div>
		{/foreach}
		{if count($item.categories) > 0}
			{call name=render_locations items=$item.categories parentclass=$parentclass|cat:" "|cat:$item.category_name parenturl=$parenturl|cat:"/"|cat:$item.listing_url count=$count}
		{/if}
	{/foreach}
{/function}

	<header>
		{include file='mobilemenu.tpl'}
		<div id="headout" class="headerbg">
				{include file='desktopmenu.tpl'}
				<div id="videobox">
					<div class="container">
						<div class="row-fluid">
							<div class="span7">
					  			{include file='breadcrumbs.tpl'}
					  			<h3 class="toptitle">{$listing_title}</h3>
					  			<div class="toptext">
					  				{$listing_content1}
					  			</div>
				  			</div>
						</div>
					</div>
				</div>
			</div>
	</header>
	<div id="orangebox">
	  	<div class="container" id="locationbox">
	  		
	  		<div class="row-fluid" id="statelist">
		  		<a data-option-value=".all" class="button1 selected">All</a>
		  		{call name=render_menu items=$menuitems}
	  		</div>
	  		
	  		<div class="row-fluid" id="locationboxin">
	  			<div class="span7">
	  				{call name=render_locations items=$data.categories parenturl="/"|cat:$listing_url}
	  			</div>
	  			<div class="span5" >
	  				<div id="map" class="map"></div>
	  			</div>
	  		</div>
	  		
	  	</div>
	  </div>  
	  
	  <div id="whitebox1">
	  	<div class="container">
		  	<div class="row-fluid">
			  	<div class="span7">
			  		{$listing_content5}
			  	</div>
			  	<div class="span5">
			  		<img src="/images/cocoa.png" alt="Cocoa spilling out of a shaker onto white bench" />
			  	</div>
		  	</div>
	  	</div>
	  </div>

	<script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
	<script src="/includes/js/jquery.ba-bbq.min.js"></script>
    <script src="/includes/js/location-menu.js"></script>  

	{include file='signup.tpl'} {include file='footer.tpl'}
{/block}
