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
				<a href="{$parenturl}#{$item.category_name|strtolower}" id="{$item.category_name|strtolower}" class="button1">{$item.category_name}</a>
				{call name=render_menu items=$item.subs level=$level+1 parenturl=$parenturl menu=$menu}
				{/if}
			{/if}
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
					  			<h3 class="toptitle">{$listing_parent.listing_title}</h3>
					  			<div class="toptext">
					  				{$listing_parent.listing_content1}
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
	  		
	  		<div class="row-fluid">
	  			<div class="span7 loc all" latitude="{$location_latitude}" longitude="{$location_longitude}" title="{$listing_title}" pin="{$location_pin}">
	  				<div class="row-fluid">
	  					<div class="span5">
	  						<div class="row-fluid" id="big-image">
	  							<img src="{$listing_image}" alt="{$listing_title}"/>
	  						</div>
	  						<div class="row-fluid small-images">
								<div class="span3">
		  							<a href="{$listing_image}"><img src="{$listing_image}" alt="{$listing_title}"/></a>
		  						</div>
							{foreach $gallery as $item}
								<div class="span3">
		  							<a href="{$item.gallery_link}"><img src="{$item.gallery_link}" alt="{$item.gallery_file}"/></a>
		  						</div>
							{/foreach}
	  						</div>
	  					</div>
	  					<div class="span7 locationinfo">
	  					<h4 class="locationtitle">{$listing_title}</h4>
	  						{if $listing_flag1 eq 1}<div class="new"></div>{/if}
	  						{$listing_content1}
							{$listing_content2}
							{$listing_content3}
	  					</div>
	  				</div>					  					  				
	  			</div>
	  			<div class="span5">
	  				<div class="map" id="map"></div>
	  			</div>
	  		</div>
	  		<div class="row-fluid">
	  		<div class="span4">
		  			<img src="/images/menu.png" alt="" />
		  			<div class="quicktitle">View Our Menu</div>
		  			<div class="quicktext">{$listing_parent.listing_content2}</div>
		  			<a href="/our-menu" class="button">View Menu</a>
		  		</div>
		  		<div class="span4">
		  			<img src="/images/vipquick.png" alt="" />
		  			<div class="quicktitle">VIP Customer Program</div>
		  			<div class="quicktext">{$listing_parent.listing_content3}</div>
		  			<a href="/community/vip-customer-program" class="button">View Details</a>
		  	  		</div>
		  		<div class="span4">
		  			<img src="/images/careers.png" alt="" />
		  			<div class="quicktitle">Careers</div>
		  			<div class="quicktext">{$listing_parent.listing_content4}</div>
		  			<a href="/community/careers" class="button">View Details</a>
		  		</div>
	  		</div>
	  	</div>
	  </div>  
	  
	  <div id="whitebox1">
	  	<div class="container">
		  	<div class="row-fluid">
			  	<div class="span7">
			  		<h3>Introduce Cocolat to your neighbourhood</h3>
			  		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam ut lorem vitae turpis commodo porttitor in in massa. Nulla vehicula ultricies ornare. Aenean accumsan et risus ac mattis.</p>
			  		 <p>Morbi id lacus tempor, dignissim sapien vel, varius elit. Etiam nec neque quis urna fermentum condimentum. Vestibulum ornare odio luctus <a href="#">turpis dapibus</a></p>
			  	</div>
			  	<div class="span5">
			  		<img src="/images/cocoa.png" alt="" />
			  	</div>
		  	</div>
	  	</div>
	  </div>

	<script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
	<script src="/includes/js/jquery.ba-bbq.min.js"></script>
    <script src="/includes/js/location-menu.js"></script>  

	{include file='signup.tpl'} {include file='footer.tpl'}
{/block}
