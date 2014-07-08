{block name=product_struct}
<div itemscope itemtype="http://schema.org/Product">
<meta itemprop="brand" content="{$item.brand.0.listing_name}">
<meta itemprop="description" content="{$item.product_content1|strip_tags}">
<a href="{$parenturl}/{$item.product_url}" itemprop="url"><img itemprop='image' src="{if $item.gallery.0.gallery_link neq ''}{$item.gallery.0.gallery_link}{else}/images/no-image-available.jpg{/if}" alt="{$item.gallery.0.gallery_alt_tag}" title="{$item.gallery.0.gallery_title}" class="img-responsive"></a>
<div class="prodname" itemprop="name">{$item.product_name}</div>
<div class="prodbrand" itemprop='productID'>{$item.product_uid}</div>{if $item.product_specialprice > 0.0}<div class="prodfullprice">
	<span>${$item.product_price|number_format:2:'.':','}</span>
	<div class="prodsave">SAVE ${($item.product_price-$item.product_specialprice)|number_format:2:'.':','}</div>
</div>{else}
<div class="prodfullprice"></div>{/if}
<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="prodprice">{if $item.product_instock eq 1}<meta itemprop="availability" href="http://schema.org/InStock" />{else}<meta itemprop="availability" href="http://schema.org/OutOfStock" />{/if}<span itemprop="price">{if $item.product_specialprice > 0.0}${$item.product_specialprice|number_format:2:'.':','}{else}${$item.product_price|number_format:2:'.':','}{/if}</span></div>
<a href="{$parenturl}/{$item.product_url}" class="grey btn">View Product Details</a>
<br />
<form class="form-horizontal product-form" id="product-form{$count}" data-attr-id="product-form" role="form" accept-charset="UTF-8" action="" method="post">
	<input type="hidden" value="ADDTOCART" name="action" id="action" /> <input type="hidden" name="formToken" id="formToken" value="{$token}" /> <input type="hidden" value="{$item.product_id}" name="product_id" id="product_id" /> <input type="hidden" id="quantity" name="quantity" value="1" /> <a
		href="javascript:void(0)" class="buy btn" onclick="addCart('product-form{$count++}');"><img src="/images/add.png" alt="add button" /> Add to cart</a>
</form>
</div>
{/block}
