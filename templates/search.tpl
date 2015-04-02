{block name=body}
	<header>
		<div id="headout" class="headerbg">
				<div id="videobox">
					<div class="container">
						<div class="row-fluid">
							<div class="span7">
					  			{include file='breadcrumbs.tpl'}
					  			<h3 class="toptitle">{$listing_name} [search.tpl]</h3>
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
				  			{if $results.product}
						  		{assign var=prodcount value=0}
						  		{assign var=prodpage value=1}
						  		{foreach $results.product as $item}
							  		{if $prodcount eq 2}{assign var=prodcount value=0}{/if}
										{assign var=prodcount value=$prodcount+1}
										<div class="result prod-page {if $prodpage eq 1}current{/if} prod-page{$prodpage}">
											<div id="img-mini-box">
												<div class="resultimage">
													{if $item.gallery.0.gallery_link}
														<a href="/{$item.cache_url}">
															<img class="img-responsive" src="{$item.gallery.0.gallery_link}" alt="{if $item.gallery.0.gallery_alt_tag}{$item.gallery.0.gallery_alt_tag}{else}product-img{/if}" title="{if $item.gallery.0.gallery_title}{$item.gallery.0.gallery_title}{else}{$item.product_name}{/if}">
														</a>
													{/if}
												</div>
											</div>
								  		<div class="resulttitle"><a href="/{$item.cache_url}">{$item.product_name}</a></div>
								  		<div class="resulttext">{if $item.product_content1}{trimwords data=$item.product_content1|strip_tags maxwords=50}{/if}</div>
											<a href="/{$item.cache_url}" class="button1">View details</a>
											{if $item.tags}
												<div class="resulttags">Tags:
										  		{foreach $item.tags as $tag}
										  			<a href="/search?q={$tag.tag_value}">{$tag.tag_value}</a>
													{/foreach}
								  			</div>
								  		{/if}
							  		</div>
							  	{if $prodcount eq 2}{assign var=prodpage value=$prodpage+1}{/if}
								{/foreach}
							{/if}
				  	</div>
				 <div class="prod-resultpag">
					<div class="row">
			  		<div class="col-sm-12">
					  		<ul>
					  			{if count($results.product) gt 2}
					  				<li><small>Page:</small></li>
						  			<li class="current"><a href="javascript:void(0);" onclick="$('.prod-resultpag').find('li').removeClass('current');$(this).parent('li').addClass('current');$('.prod-page').removeClass('current');$('.prod-page1').addClass('current');">1</a></li>
							  		{assign var=prodcount value=0}
							  		{assign var=prodpage value=1}
							  		{foreach $results.product as $item}
								  		{if $prodcount eq 2}
								  			{assign var=prodpage value=$prodpage+1}
								  			<li><a href="javascript:void(0);" onclick="$('.prod-resultpag').find('li').removeClass('current');$(this).parent('li').addClass('current');$('.prod-page').removeClass('current');$('.prod-page{$prodpage}').addClass('current');">{$prodpage}</a></li>
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
			</div>
 			<div id="resultbox-page">
		  		<div class="row">
			  		<div class="col-sm-12">
			  		<h3>Your search for <strong>'{$term}'</strong> returned {count($results.listing)} page results:</h3>
				  			{if $results.listing}
				  				{assign var=count value=0}
						  		{assign var=page value=1}
						  		{foreach $results.listing as $item}
						  			{if $count eq 3}{assign var=count value=0}{/if}
									{assign var=count value=$count+1}
									<div class="result page {if $page eq 1}current{/if} page{$page}">
							  			<div class="resulttitle"><a href="/{$item.cache_url}">{$item.listing_name}</a></div>
							  			<div class="resulttext">{if $item.listing_meta_description}{trimwords data=$item.listing_meta_description maxwords=50}{/if}</div>
							  			<a href="/{$item.cache_url}" class="button1">View details</a>
							  			{if $item.tags}
								  			<div class="resulttags">Tags:
									  			{foreach $item.tags as $tag}
									  				<a href="/search?q={$tag.tag_value}">{$tag.tag_value}</a>
													{/foreach}
								  			</div> 
								  		{/if}
							  		</div>
							  		{if $count eq 3}{assign var=page value=$page+1}{/if}
								{/foreach}
							{/if}
				  		</div>
				  		<div class="resultpag">
					  		<div class="row">
				  				<div class="col-sm-12">
							  		<ul>
							  			{if count($results.listing) gt 3}
								  			<li><small>Page:</small></li>
								  			<li class="current"><a href="javascript:void(0);" onclick="$('.resultpag').find('li').removeClass('current');$(this).parent('li').addClass('current');$('.page').removeClass('current');$('.page1').addClass('current');">1</a></li>
									  		{assign var=count value=0}
									  		{assign var=page value=1}
									  		{foreach $results.listing as $item}
										  		{if $count eq 3}
										  			{assign var=page value=$page+1}
										  			<li><a href="javascript:void(0);" onclick="$('.resultpag').find('li').removeClass('current');$(this).parent('li').addClass('current');$('.page').removeClass('current');$('.page{$page}').addClass('current');">{$page}</a></li>
										  			{assign var=count value=0}
										  		{/if}							  		
										  		{assign var=count value=$count+1}
											{/foreach}
										{/if}
							  		</ul>
						  	</div>
				  		</div>
			  		</div>
		  		</div>
			</div> 
	  		
	  	</div>


{/block}
