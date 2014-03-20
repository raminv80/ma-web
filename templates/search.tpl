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
	  	<div class="container" >
	  		
		  	<div id="resultbox-product">
		  		<div class="row">
			  		<div class="col-sm-12">
			  		<h3>Your search for <strong>'{$term}'</strong> returned {if $results.product}{count($results.product)}{else}0{/if} product results:</h3>
				  		<div class="prod-page current" id="prod-page1">
				  			{if $results.product}
						  		{assign var=prodcount value=0}
						  		{assign var=prodpage value=1}
						  		{foreach $results.product as $item}
							  		{if $prodcount eq 4}{assign var=prodcount value=0}{/if}
									{assign var=prodcount value=$prodcount+1}
									<div class="result">
										<div id="img-mini-box">
											<div class="resultimage">
												<img src="{$item.image.gallery_link}" alt="{$item.image.gallery_alt_tag}" title="{$item.image.gallery_title}">
											</div>
										</div>
										
							  			<div class="resulttitle">{$item.product_name}</div>
							  			<div class="resulttext">{trimwords data=$item.product_description maxwords=50}</div>
										<a href="/{$item.cache_url}" class="button1">View details</a>
										<div class="resulttags">Tags:
								  			{foreach $item.tags as $tag}
								  				<a href="/search?q={$tag.tag_value}">{$tag.tag_value}</a>
											{/foreach}
							  			</div>
							  			
							  		</div>
							  		{if $prodcount eq 4}{assign var=prodpage value=$prodpage+1}</div><div class="prod-page" id="prod-page{$prodpage}">{/if}
								{/foreach}
							{/if}
				  		</div>
				  		<div class="prod-resultpag">
					  		<ul>
					  			{if count($results.product) gt 4}
						  			<li class="current"><a href="javascript:void(0);" onclick="$('.prod-resultpag').find('li').removeClass('current');$(this).parent('li').addClass('current');$('.prod-page').removeClass('current');$('#prod-page1').addClass('current');">1</a></li>
							  		{assign var=prodcount value=0}
							  		{assign var=prodpage value=1}
							  		{foreach $results.product as $item}
								  		{if $prodcount eq 4}
								  			{assign var=prodpage value=$prodpage+1}
								  			<li><a href="javascript:void(0);" onclick="$('.prod-resultpag').find('li').removeClass('current');$(this).parent('li').addClass('current');$('.prod-page').removeClass('current');$('#prod-page{$prodpage}').addClass('current');">{$prodpage}</a></li>
								  			{assign var=prodcount value=0}
								  		{/if}							  		
								  		{assign var=prodcount value=$prodcount+1}
									{/foreach}
								{/if}
					  		</ul>
				  		</div>
			  		</div>
		  		</div>
			</div>
<!-- 			<div id="resultbox-page">
		  		<div class="row">
			  		<div class="col-sm-12">
			  		<h3>Your search for <strong>'{$term}'</strong> returned {count($results.listing)} page results:</h3>
				  		<div class="page current" id="page1">
				  			{if $results.listing}
				  				{assign var=count value=0}
						  		{assign var=page value=1}
						  		{foreach $results.listing as $item}
						  			{if $count eq 3}{assign var=count value=0}{/if}
									{assign var=count value=$count+1}
									<div class="result">
							  			<div class="resulttitle">{$item.listing_name}</div>
							  			<div class="resulttext">{trimwords data=$item.listing_meta_description maxwords=50}</div>
							  			<a href="/{$item.cache_url}" class="button1">View details</a>
							  			<div class="resulttags">Tags:
								  			{foreach $item.tags as $tag}
								  				<a href="/search?q={$tag.tag_value}">{$tag.tag_value}</a>
											{/foreach}
							  			</div> 
							  		</div>
							  		{if $count eq 3}{assign var=page value=$page+1}</div><div class="page" id="page{$page}">{/if}
								{/foreach}
							{/if}
				  		</div>
				  		<div class="resultpag">
					  		<ul>
					  			{if count($results.listing) gt 3}
						  			<li class="current"><a href="javascript:void(0);" onclick="$('.resultpag').find('li').removeClass('current');$(this).parent('li').addClass('current');$('.page').removeClass('current');$('#page1').addClass('current');">1</a></li>
							  		{assign var=count value=0}
							  		{assign var=page value=1}
							  		{foreach $results.listing as $item}
								  		{if $count eq 3}
								  			{assign var=page value=$page+1}
								  			<li><a href="javascript:void(0);" onclick="$('.resultpag').find('li').removeClass('current');$(this).parent('li').addClass('current');$('.page').removeClass('current');$('#page{$page}').addClass('current');">{$page}</a></li>
								  			{assign var=count value=0}
								  		{/if}							  		
								  		{assign var=count value=$count+1}
									{/foreach}
								{/if}
					  		</ul>
				  		</div>
			  		</div>
		  		</div>
			</div> -->
	  		
	  	</div>


{/block}
