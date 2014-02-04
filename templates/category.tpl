{block name=body}
{* Define the function *} 
{function name=render_products level=0 parentUrl=''} 
	<div class="row">
	{foreach from=$items key=k item=it}
		<div class="col-xs-3 image">
		{foreach from=$it.gallery key=k item=g}
			{if $g.gallery_ishero == 1}
			<img src="{$g.gallery_link}" alt="{$g.gallery_alt_tag}" title="{$g.gallery_title}" />
			<span class="caption">{$g.gallery_caption}</span>
			{/if}
		{/foreach}
		</div>
		<div class="col-xs-9">
		<div class="col-xs-12">{$it.product_name}</div>
		<div class="col-xs-12">{$it.product_price}</div>
		<div class="col-xs-12">{$it.product_description}</div>
		<div class="col-xs-12"><a href="{$parentUrl}{$it.product_url}-{$it.product_id}">Click Here</a></div>
		</div>
	{/foreach} 
	</div>
{/function}

{function name=render_categories level=0 parentUrl=''} 
	<div class="row">
	{foreach from=$items key=k item=cat}
		<div class="col-xs-3 image">
			<img src="{$cat.listing_image}" alt="{$cat.listing_title}" title="{$cat.listing_title}" />
		</div>
		<div class="col-xs-9">
		<div class="col-xs-12">{$cat.listing_title}</div>
		<div class="col-xs-12"><a href="{$parentUrl}{$cat.listing_url}">Click Here</a></div>
		</div>
		{call name=render_products items=$cat.products parentUrl=$parentUrl+"/"+$cat.listing_url}
	{/foreach} 
	</div>
{/function}

	<header>
		<div id="headout" class="headerbg">
				<div id="videobox">
					<div class="container">
						<div class="row">
							<div class="col-xs-12">
					  			{include file='breadcrumbs.tpl'}
					  			<h3 class="toptitle">{$listing_title}</h3>
				  			</div>
						</div>
					</div>
				</div>
			</div>
	</header>
	<div class="container">
		<div class="row">
			<div class="col-xs-12"><h3>Products in {$listing_title}</h3></div>
			{call name=render_products items=$data.products parentUrl="./{$listing_url}/"}
			{call name=render_categories items=$data.listings parentUrl="./{$listing_url}/"}
		</div>
	</div>
{/block}
