{block name=product_struct}
<div itemscope itemtype="http://schema.org/Product">
	<div class="productin">
	<a href="{if $parenturl neq ''}{$parenturl}/{$item.product_url}{else}/{$item.cache_url}{/if}">
	<meta itemprop="brand" content="{$item.brand.0.listing_name}">
	<meta itemprop="description" content="{$item.product_content1|strip_tags}">
	<!--<a href="{if $parenturl neq ''}{$parenturl}/{$item.product_url}{else}/{$item.cache_url}{/if}" itemprop="url">
		<!-- <img itemprop='image' src="{if $item.gallery.0.gallery_link neq ''}{$item.gallery.0.gallery_link}?height=276{else}/images/no-image-available.jpg{/if}" alt="{$item.gallery.0.gallery_alt_tag}" title="{$item.gallery.0.gallery_title}" class="img-responsive"> -->
	<!--	<img itemprop='image' src="{exist_image image=$item.gallery.0.gallery_link|cat:'?height=276' default='/images/no-image-available.jpg?height=180'}" alt="{$item.gallery.0.gallery_alt_tag}" title="{$item.gallery.0.gallery_title}" class="img-responsive">
	</a> -->
	<div class="prodimg">
		<img itemprop='image' src="{exist_image image=$item.gallery.0.gallery_link|cat:'?width=235&height=235' default='/images/no-image-available.jpg?width=235&height=235'}" alt="{$item.gallery.0.gallery_alt_tag}" title="{$item.gallery.0.gallery_title}" class="img-responsive" />
	</div>

	<div class="prodinfo">
		<div class="row">
			<div class="col-sm-12">
				<div class="prodcat pull-left">{if $parent_name}{$parent_name}{else}{$item.parent.listing_name}{/if}</div>
			</div>
			<div class="col-sm-12">
				<div class="prodname1 pull-left" itemprop="name">{$item.product_name}</div>
			</div>
			<div class="col-sm-12">
			{if $parenturl|strstr:"/ergonomic-accessories/" || $item.cache_url|strstr:"/ergonomic-accessories/" || $parenturl|strstr:"/specials" || $item.cache_url|strstr:"/specials" || $parenturl|strstr:"/sit-to-stand/mid-duty-electric-height-adjustable-desks" || $item.cache_url|strstr:"/sit-to-stand/mid-duty-electric-height-adjustable-desks"}
				<div class="prodprice1 pull-left" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
				{if $item.product_instock eq 1}
				<meta itemprop="availability" href="http://schema.org/InStock" />
				{else}
				<meta itemprop="availability" href="http://schema.org/OutOfStock" />
				{/if}
					<div class="prodfullprice">
					<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="prodprice">
					{if $item.product_specialprice > 0.0}
						<span><s>${$item.product_price|number_format:2:'.':','}</s></span>
						<span itemprop="price" class="prodsave">${$item.product_specialprice|number_format:2:'.':','}</span>
					{else}
						<span itemprop="price">${$item.product_price|number_format:2:'.':','}</span>
					{/if}
					</div>
					</div>
				</div>
				<img src="/images/cart.png" align="Cart" class="cartimg pull-right" />
			{else}
				&nbsp;
			{/if}
			</div>
			
		</div>
	</div>
	{if $item.product_specialprice > 0.0}
		<img src="/images/sale.png" alt="Sale" id="saleimg" />
	{/if}
	<div class="prodhover">
		<div class="circle">
			View product
		</div>
	</div>
	</a>
	</div>
</div>
{/block}