{block name=product_struct}
<div class="col-sm-6 col-lg-4 prodout show-all type-{$item.product_associate1} range-{$item.price_range} {if $item.general_details.sale.flag eq 1}range-sale{/if} {foreach $item.general_details.materials as $matk => $matv}material-{$matk} {/foreach} {foreach $item.general_details.has_attributes as $attr}{foreach $attr as $valid => $valval} attrval-{$valid}{/foreach}{/foreach}">
	<div class="prod">
	<a href="/{$item.product_url}">
		<img src="{if $item.general_details.image}{$item.general_details.image}{else}/images/no-image-available.png{/if}?width=770&height=492&crop=1" alt="{$item.product_name} image" class="img-responsive prodimg" title="{$item.product_name} image">
	</a>
	<div class="prod-labels">
        {if $item.general_details.limitedstock.flag eq 1}
        <div class="prod-label btn btn-white">Limited stock</div>
        {/if}
        {if $item.general_details.new.flag eq 1}
		<div class="prod-label btn btn-white">New</div>
        {/if}
        {if $item.general_details.sale.flag eq 1}
		<div class="prod-label btn btn-red">Sale</div>
        {/if}
	</div>
	<div class="prod-info">
		<div class="prod-name">
			<a href="/{$item.product_url}">{$item.product_name}</a>
		</div>
		<div class="prod-price">${$item.general_details.price.min|number_format:0:'.':','}{if $item.general_details.price.min neq $item.general_details.price.max} - ${$item.general_details.price.max|number_format:0:'.':','}{/if}</div>
    	{if $item.general_details.has_attributes.2}	
        <div class="colours">Available colours
			<div class="colourbox">
            {foreach $item.general_details.has_attributes.2 as $colour}  
              <img src="{$colour.values.attr_value_image}?height=22&width=22" alt="{$colour.values.attr_value_name}" title="{$colour.values.attr_value_name}" />
            {/foreach}
			</div>
		</div>
        {/if}
		<div class="prod-wishlist"><img src="/images/prod-wishlist.png" alt="Wishlist"></div>
	</div>
	</div>
</div>
{* COMMENTED OUT ============== REMEMBER TO MOVE IT ================
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
====================================================== *}
{/block}