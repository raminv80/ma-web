{block name=body}


{function name=render_menu level=0 menu=0}
	{foreach $items as $item}
		{if $level lt 1}
			{if $item.url eq 'our-menu'}
				{call name=render_menu items=$item.subs level=$level+1 parenturl=$parenturl|cat:"/"|cat:$item.url menu=1}
			{/if}
		{else}
			{if $menu eq 1}
				{if $item.category eq 1 and $item.listings eq 1}
				<a href="/our-menu#{$item.url}" id="{$item.url}" class="button1{if $item.url eq $listing_parent.listing_url} selected{/if}">{$item.title}</a>
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
					  			{include file='breadcrumbs_products.tpl'}
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
		  		<a href="/our-menu#favourites" id="favourites" class="button1">Customer Favourites</a>
		  		{call name=render_menu items=$menuitems}
		  		<a href="/our-menu#whats-new"id="whats-new" class="button1">What's New</a>
	  		</div>
	  		
	  		<div class="row-fluid proddet">
  				<div class="span3">
					<div class="row-fluid" id="big-image">
						<img src="{$listing_image}" alt="{$listing_title}" />
						<div class="prodtopbox">
							<div class="prodtoptitle">{$listing_title}</div>
							<div class="prodtopcat">{$listing_parent.listing_title}</div>
						</div>
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
  				<div class="span8 locationinfo">
	  				<h4 class="locationtitle">{$listing_title}</h4>
	  					{$listing_content2}
					<div class="row-fluid tags1">
						{if $listing_flag1 eq 1}<img title="New" src="/images/new.png" alt="" />{/if}
						{if $listing_flag2 eq 1}<img title="Award winning" src="/images/award.png" alt="Award winning product" />{/if}
						{if $listing_flag3 eq 1}<img title="Customer Favourite" src="/images/fav.png" alt="Favourite product" />{/if}
	  				</div>
	  					
	  				<div class="row-fluid prodshare">
		  				<span class='st_sharethis_hcount' displayText='ShareThis'></span>
		  				<span class='st_facebook_hcount' displayText='Facebook'></span>
	  					<span class='st_twitter_hcount' displayText='Tweet'></span>
	  				</div>
	  					
	  					
	  			</div>	  			  		
	  		</div>
  		</div>
 	</div>
 	
 	 <div id="whitebox1">
	  	<div class="container producttry">
		  	<div class="row-fluid">
			  	<div class="span12">
			  		<h3 class="title">Are you ready to try this product?</h3>
			  		<p><a href="/our-locations">Find your Nearest Location</a></p>
			  		
			  		<h3 class="title love">Tell us why you love this product</h3>

			  	</div>
		  	</div>
	  	</div>
	  </div>
	  	
	<script src="/includes/js/jquery.isotope.min.js"></script>
    <script src="/includes/js/jquery.ba-bbq.min.js"></script>    
    <script src="/includes/js/jquery.tipTip.minified.js"></script>  
    <!-- <script src="/includes/js/menu.js"></script> -->  

	{include file='signup.tpl'} {include file='footer.tpl'}
{/block}
