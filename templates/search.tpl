{block name=body}
	<header>
		<div id="headout" class="headerbg">
				<div id="videobox">
					<div class="container">
						<div class="row-fluid">
							<div class="span7">
					  			{include file='breadcrumbs.tpl'}
					  			<h3 class="toptitle">{$product_name}</h3>
				  			</div>
						</div>
					</div>
				</div>
			</div>
	</header>
	  	<div class="container" id="resultbox">
	  		
	  		<div class="row">
		  		<div class="col-sm-9">
			  		<div class="row page current" id="page1">
			  			{if $results}
					  		{assign var=count value=0}
					  		{assign var=page value=1}
					  		{foreach $results as $item}
						  		{if $count eq 3}{assign var=count value=0}{/if}
								{assign var=count value=$count+1}
								<div class="row result">
						  			<h4 class="resulttitle">{$item.product_name}</h4>
						  			<div class="resulttext">{$item.product_description}</div>
						  			<!-- <div class="resulttags"><a href="#">#tag1</a>, <a href="#">#tag2</a>, <a href="#">#tag3</a></div> -->
						  			<a href="{$item.cache_url}" class="button1">View Page</a>
						  		</div>
						  		{if $count eq 3}{assign var=page value=$page+1}</div><div class="row page" id="page{$page}">{/if}
							{/foreach}
						{else}
							<div class="row result">
						  		<h4 class="resulttitle">No results found</h4>
						  	</div>
						{/if}
			  		</div>
			  		<div class="row resultpag">
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


{/block}
