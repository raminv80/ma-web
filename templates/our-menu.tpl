{block name=body}

{* Define the function *}
{function name=render_menu level=0 menu=0}
	{foreach $items as $item}
		{if $level lt 1}
			{if $item.url eq 'our-menu'}
				{call name=render_menu items=$item.subs level=$level+1 parenturl=$parenturl|cat:"/"|cat:$item.url menu=1}
			{/if}
		{else}
			{if $menu eq 1}
				{if $item.category eq 1 and $item.listings eq 1}
				<a data-option-value=".{$item.url}" id="{$item.url}" class="button1">{$item.title}</a>
				{call name=render_menu items=$item.subs level=$level+1 parenturl=$parenturl menu=$menu}
				{/if}
			{/if}
		{/if}
	{/foreach}	
{/function}
{* Define the function *}
{function name=render_mobile_menu level=0 menu=0}
	{foreach $items as $item}
		{if $level lt 1}
			{if $item.url eq 'our-menu'}
				{call name=render_mobile_menu items=$item.subs level=$level+1 parenturl=$parenturl|cat:"/"|cat:$item.url menu=1}
			{/if}
		{else}
			{if $menu eq 1}
				{if $item.category eq 1 and $item.listings eq 1}
				<option value="{$item.url}">{$item.title}</option>
				{call name=render_mobile_menu items=$item.subs level=$level+1 parenturl=$parenturl menu=$menu}
				{/if}
			{/if}
		{/if}
	{/foreach}	
{/function}

{* Define the function *}
{function name=render_products parentclass="" parenturl=""}
	{foreach $items as $item}
		{foreach $item.listings as $l}
		{* Size randomiser *}
		{assign var='class' value=''}
		{random in=1 out=10 assign="rand"}
		{if $rand gt 7 and $rand lte 9}
			{random in=1 out=10 assign="rand"}
			{if $rand lte 5}
				{assign var='class' value='tall'}
			{else}
				{assign var='class' value='wide'}
			{/if}
		{elseif $rand gt 9}
			{assign var='class' value='big'}
		{/if}
		<div class="portfolio-item{if $class neq ''} {$class}{/if} {$item.listing_url} {$parentclass}{if $l.listing_flag1 eq 1} whats-new{/if}{if $l.listing_flag3 eq 1} favourites{/if}">
			<a href="{$parenturl}/{$item.listing_url}/{$l.listing_url}" class="image"><img src="{$l.listing_image}" alt="{$l.listing_title}" /></a>
			<div class="tags">
				{if $l.listing_flag1 eq 1}<div title="New" class="new2"></div>{/if}
				{if $l.listing_flag2 eq 1}<div title="Award Winning" class="award2"></div>{/if}
				{if $l.listing_flag3 eq 1}<div title="Customer Favourite" class="fav2"></div>{/if}
			</div>
			<div class="mitemtop">
				<div class="mitemtitle"><a href="{$parenturl}/{$item.listing_url}/{$l.listing_url}">{$l.listing_title}</a></div>
				<div class="mitemcat">{$item.listing_title}</div>
			</div>
		</div>	
		{/foreach}
		{if count($item.categories) > 0}
			{call name=render_products items=$item.categories parentclass=$parentclass|cat:" "|cat:$item.listing_url parenturl=$parenturl|cat:"/"|cat:$item.listing_url}
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
	{if $potm[0].listing_content1 neq ""}
	<div id="potm">
	  	<div class="container">
		  <div class="row-fluid">
		  	<div class="span8">
		  		<h3 class="potm">Product Of The Month</h3>
		  		{$potm[0].listing_content1}
		  	</div>
		  	<div class="span4">
				<img src="{$potm[0].listing_image}" alt="{$potm[0].listing_title}">
		  	</div>
		  </div>
	  	</div>
	</div>
	<div id="potm2">
	  	<div class="container">
	  		<div class="row-fluid">
		  		<div id="potmbutton"><img src="/images/productmonth.png" alt="View product of the month" /></div>
	  		</div>
	  	</div>
	</div>
	{/if}
	<!-- Product List Section -->
	<div id="orangebox">
	  	<div class="container" id="menumainbox">
		  	<div class="row-fluid" id="menulist">
		  		<a data-option-value=".favourites" id="favourites" class="button1 selected">Customer Favourites</a>
		  		{call name=render_menu items=$menuitems}
		  		<a data-option-value=".whats-new"id="whats-new" class="button1">What's New</a>
	  		</div>
	  		<div class="row-fluid" id="menulistmobile">
	  			<select id="moblist">
					<option value="">Select a category</option>
  					<option value="favourites">Customer Favourties</option>
  					{call name=render_mobile_menu items=$menuitems}
  					<option value="whats-new">What's New</option>
	  			</select>
	  		</div>
	  		
	  		<div class="row-fluid" id="menubox">
		  		<div class="row-fluid">
		  			<div class="span12">
		  				<div id="up1"><img src="/images/up.png" alt="up" /></div>
		  			</div>
		  		</div>
		  		<div class="row-fluid">
		  			<div class="span12" id="count">
		  			</div>
		  		</div>
	  			  		
		  		<div id="menuout">
		  			<div id="menucontainer">
		  				<div class="span12">
			  				<div id="portfolio-wrapper">
			  					{call name=render_products items=$data.categories parenturl="/"|cat:$listing_url}
			  				</div>
		  				</div>
		  			</div><!--newscontainer-->
		  		</div><!--newsbox-->
		  		<div class="row-fluid">
		  			<div class="span12">
		  			<div id="down1"><img src="/images/down.png" alt="down" /></div>
		  			</div>
		  		</div>
	  		</div>
  		</div>
 	</div>
 	{if $listing_content2 neq ""}
 	 <div id="whitebox1">
	  	<div class="container">
		  	<div class="row-fluid">
			  	<div class="span7">
			  		{$listing_content2}
			  	</div>
			  	<div class="span5">
			  		<img src="/images/cocoa.png" alt="" />
			  	</div>
		  	</div>
	  	</div>
	  </div>
	  {/if}
	  	
	<script src="/includes/js/jquery.isotope.min.js"></script>
    <script src="/includes/js/jquery.ba-bbq.min.js"></script>    
    <script src="/includes/js/jquery.tipTip.minified.js"></script>  
    <script src="/includes/js/menu.js"></script>  

	{include file='signup.tpl'} {include file='footer.tpl'}
{/block}
