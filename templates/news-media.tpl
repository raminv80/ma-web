{block name=body}

{* Define the function *}
{function name=render_news parenturl="" count="0"}
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
			{call name=render_news items=$item.categories parenturl=$parenturl|cat:"/"|cat:$item.listing_url}
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
	  		<div class="row-fluid" id="menubox">
	  			{assign var=cnt value=0}
	  			{foreach $data.categories as $item}
	  				{assign var=cnt value=count($item.listings)}
	  			{/foreach}
	  			{if $cnt gt 8}
		  		<div class="row-fluid">
		  			<div class="span12">
		  				<div id="up"><img src="/images/up.png" alt="up" /></div>
		  			</div>
		  		</div>
		  		{/if}
	  			  		
		  		<div id="newsbox">
			  		<div id="newscontainer">
				  		<div class="row-fluid">
					  		{call name=render_news items=$data.categories parenturl="/community"}
				  		</div>
				  	</div><!--newscontainer-->
		  		</div><!--newsbox-->
		  		{if $cnt gt 8}
		  		<div class="row-fluid">
		  			<div class="span12">
		  			<div id="down"><img src="/images/down.png" alt="down" /></div>
		  			</div>
		  		</div>
		  		{/if}
	  		</div>
  		</div>
 	</div>

	{include file='signup.tpl'} {include file='footer.tpl'}
{/block}
