{block name=body}
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
	  	<div class="container" id="resultbox">
	  		
	  		<div class="row-fluid">
		  		<div class="span9">
			  		<div class="row-fluid page current" id="page1">
			  			{if count($results) gt 0}
					  		{assign var=count value=0}
					  		{assign var=page value=1}
					  		{foreach $results as $item}
						  		{if $count eq 3}{assign var=count value=0}{/if}
								{assign var=count value=$count+1}
								<div class="row-fluid result">
						  			<h4 class="resulttitle">{$item.listing_title}</h4>
						  			<div class="resulttext">{$item.listing_content1}</div>
						  			<!-- <div class="resulttags"><a href="#">#tag1</a>, <a href="#">#tag2</a>, <a href="#">#tag3</a></div> -->
						  			<a href="{$item.cache_url}" class="button1">View Page</a>
						  		</div>
						  		{if $count eq 3}{assign var=page value=$page+1}</div><div class="row-fluid page" id="page{$page}">{/if}
							{/foreach}
						{else}
							<div class="row-fluid result">
						  		<h4 class="resulttitle">No results found</h4>
						  	</div>
						{/if}
			  		</div>
			  		<div class="row-fluid resultpag">
				  		<ul>
				  			{if count($results) gt 3}
					  			<li class="current"><a href="javascript:void(0);" onclick="$('.page').removeClass('current');$('#page1').addClass('current');">1</a></li>
						  		{assign var=count value=0}
						  		{assign var=page value=1}
						  		{foreach $results as $item}
							  		{if $count eq 3}
							  			{assign var=page value=$page+1}
							  			<li><a href="javascript:void(0);" onclick="$('.resultpag').find('li').removeClass('current');$(this).parent('li').addClass('current');$('.page').removeClass('current');$('#page{$page}').addClass('current');">{$page}</a></li>
							  		{/if}
							  		{if $count eq 3}{assign var=count value=0}{/if}
							  		{assign var=count value=$count+1}
								{/foreach}
							{/if}
				  		</ul>
			  		</div>
		  		</div>
	  		</div>
	  		
	  	</div>
	  </div>  

	{include file='signup.tpl'} {include file='footer.tpl'}
{/block}
