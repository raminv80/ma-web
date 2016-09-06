{block name=product_struct}
<div class="col-sm-6 col-md-4" itemscope itemtype="http://schema.org/Product">
  <div class="products">
    <a href="/{$item.product_url}" title="Click to view details">
      <div class="prodimg">
        <img src="{$item.general_details.image}" alt="{$item.product_name} image" title="{$item.product_name} image" class="img-responsive" />
      </div>
      <div class="prodinfo">
        <div class="prodname1">{$item.product_name}</div>
        <div class="prodprice">${$item.general_details.price.min|number_format:0:'.':','}{if $item.general_details.price.min neq $item.general_details.price.max} - ${$item.general_details.price.max|number_format:0:'.':','}{/if}</div>
      </div>
    </a>
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