{block name=product_struct}
<div class="col-sm-6 col-md-4" itemscope itemtype="http://schema.org/Product">
	<div class="prod">
	<a href="/{$item.product_url}">
		<img src="{$item.general_details.image}?width=770&height=492&crop=1" alt="{$item.product_name} image" class="img-responsive prodimg" title="{$item.product_name} image">
	</a>
	<div class="prod-labels">
		<div class="prod-label btn btn-white">New</div>
		<div class="prod-label btn btn-red">Sale</div>
	</div>
	<div class="prod-info">
		<div class="prod-name">
			<a href="/{$item.product_url}">{$item.product_name}</a>
		</div>
		<div class="prod-price">${$item.general_details.price.min|number_format:0:'.':','}{if $item.general_details.price.min neq $item.general_details.price.max} - ${$item.general_details.price.max|number_format:0:'.':','}{/if}</div>
		<div class="colours">Available colours
			<div class="colourbox">
				<img src="/images/colour-red.png" alt="Red" title="Red" />
				<img src="/images/colour-black.png" alt="Red" title="Black" />
			</div>
		</div>
		<div class="prod-wishlist"><img src="/images/prod-wishlist.png" alt="Wishlist"></div>
	</div>
	</div>
</div>
<script type="application/ld+json">
{
  "@context": "http://schema.org/",
  "@type": "Product",
  "name": "{$item.product_name|strip_tags}",
  "image": "{$DOMAIN}{$item.general_details.image}",
  "description": "{$item.product_meta_description}",
  "offers": {
    {if $item.general_details.price.min eq $item.general_details.price.max}
	"@type": "Offer",
    "priceCurrency": "AUD",
    "price": "{$item.general_details.price.min}",
    "availability": "InStock"
	{else}
	"@type": "AggregateOffer",
    "highPrice": "${$item.general_details.price.max}",
    "lowPrice": "${$item.general_details.price.min}",
	{/if}
  }
}
</script>
{/block}