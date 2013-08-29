{block name=body}

{* Define the function *}
{function name=render_news parentclass="" parenturl="" count="0"}
	{foreach $items as $item}
		{foreach $item.listings as $l}
			{if $count eq 4}{assign var=count value=0}{/if}
			{assign var=count value=$count+1}
			<div class="span3 newsitem{if $count eq 1} first{/if}">
	  			<img src="{$l.listing_image}" alt="{$l.listing_title}" />
	  			<div class="newstop">
	  				<div class="newstitle">{$l.listing_title}</div>
	  				<div class="newstext">
		  				{$l.listing_content1}
	  				</div>
	  			</div>
	  			<a href="{$parenturl}/{$item.listing_url}/{$l.listing_url}" class="button3">Read More</a>
	  		</div>
		{/foreach}
		{if count($item.categories) > 0}
			{call name=render_news items=$item.categories parentclass=$parentclass|cat:" "|cat:$item.listing_url parenturl=$parenturl|cat:"/"|cat:$item.listing_url}
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
	  	<div class="container" id="menumainbox">
	  		<div class="row-fluid">
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
	  					
	  				<div class="row-fluid prodshare">
		  				<span class='st_sharethis_hcount' displayText='ShareThis'></span>
		  				<span class='st_facebook_hcount' displayText='Facebook'></span>
	  					<span class='st_twitter_hcount' displayText='Tweet'></span>
	  				</div>
	  					
	  					
	  			</div>
	  		</div>
  		</div>
 	</div>
	  	
	{include file='signup.tpl'} {include file='footer.tpl'}
{/block}
