{block name=body}
{* Define the function *} 
{function name=render_products level=0 parentUrl=''} 
	<div class="row">
	{foreach from=$items key=k item=it}
		<div class="col-xs-3 image">
			<img src="{$it.gallery.0.gallery_link}" alt="{$g.gallery_alt_tag}" title="{$g.gallery_title}" style="width:90px; height:90px;"/>
			<span class="caption">{$g.gallery_caption}</span>
		</div>
		<div class="col-xs-9">
		<div class="col-xs-12">{$it.product_name}</div>
		<div class="col-xs-12">from ${$it.product_price}</div>
		<div class="col-xs-12">{$it.product_description}</div>
		<div class="col-xs-12"><a href="{$parentUrl}{$it.product_url}-{$it.product_id}" class="btn btn-info">View product</a></div>
		</div>
	{/foreach} 
	</div>
{/function}

{function name=render_categories level=0 parentUrl=''} 
	<div class="row">
	{foreach from=$items key=k item=cat}
		<div class="col-xs-3 image">
			<img src="{$cat.listing_image}" alt="{$cat.listing_title}" title="{$cat.listing_title}" style="width:90px; height:90px;"/>
		</div>
		<div class="col-xs-9">
		<div class="col-xs-12">{$cat.listing_title}</div>
		<div class="col-xs-12"><a href="{$parentUrl}{$cat.listing_url}" class="btn btn-default">View Category</a></div>
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
			<div class="row" style="height:50px;"></div>
			{call name=render_categories items=$data.listings parentUrl="./{$listing_url}/"}
		</div>
	</div>
{/block}
