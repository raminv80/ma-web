{block name=product_struct}
<div itemscope itemtype="http://schema.org/Product">
	<meta itemprop="brand" content="{$item.brand.0.listing_name}">
	<meta itemprop="description" content="{$item.product_content1|strip_tags}">
	<a href="{if $parenturl neq ''}{$parenturl}/{$item.product_url}{else}/{$item.cache_url}{/if}" itemprop="url">
		<img itemprop='image' src="{if $item.gallery.0.gallery_link neq ''}{$item.gallery.0.gallery_link}?height=276{else}/images/no-image-available.jpg{/if}" alt="{$item.gallery.0.gallery_alt_tag}" title="{$item.gallery.0.gallery_title}" class="img-responsive">
	</a>
	<div class="prodname" itemprop="name">
		<a href="{if $parenturl neq ''}{$parenturl}/{$item.product_url}{else}/{$item.cache_url}{/if}">{$item.product_name}</a>
	</div>
	<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="prodprice">
		{if $item.product_instock eq 1}
			<meta itemprop="availability" href="http://schema.org/InStock" />
		{else}
			<meta itemprop="availability" href="http://schema.org/OutOfStock" />
		{/if}
		
		<div class="prodfullprice">
			{if $item.product_specialprice > 0.0}
				<span class="prodold">${$item.product_price|number_format:2:'.':','}</span>
				<span class="prodsave">SAVE ${($item.product_price-$item.product_specialprice)|number_format:2:'.':','}</span>
			{/if}
			<span itemprop="price">{if $item.product_specialprice > 0.0}${$item.product_specialprice|number_format:2:'.':','}{else}${$item.product_price|number_format:2:'.':','}{/if}</span>
		</div>
	</div>
	<a href="{if $parenturl neq ''}{$parenturl}/{$item.product_url}{else}/{$item.cache_url}{/if}" class="grey btn">View product details</a>
	<form class="form-horizontal product-form" id="product-form{$item.product_object_id}" data-attr-id="product-form" role="form" accept-charset="UTF-8" action="" method="post">
		<input type="hidden" value="ADDTOCART" name="action" id="action" /> 
		<input type="hidden" name="formToken" id="formToken" value="{$token}" /> 
		<input type="hidden" value="{$item.product_object_id}" name="product_id" id="product_id" /> 
		<input type="hidden" value="{$listing_id}" name="listing_id" /> 
		<input type="hidden" id="quantity" name="quantity" value="1" /> 
		<a href="javascript:void(0)" class="buy btn" onclick="addCart('product-form{$item.product_object_id}');">
			<img src="/images/add.png" alt="add button" />
			<img class="img-onhover" src="/images/cart.png" alt="cart" /> Add to cart
		</a> 
	</form>
</div>
{/block}
