{if $products}
  {$count = 0}
  {foreach $products as $item}
  {$count = $count+1}
  {if $count gt 8}{break}{/if}
  <div class="autocomplete-item">
    <div class="autocomplete-img">
      <img class="img-responsive" src="{if $item.gallery.0.gallery_link}{$item.gallery.0.gallery_link}{else}/images/no-image-available.png{/if}?width=77&height=47&crop=1" alt="{$item.gallery.0.gallery_alt_tag}" title="{$item.gallery.0.gallery_title}">
      </div>
    <div class="autocomplete-description">
      <a href="/{$item.cache_url}" title="{$item.product_name}">{if $item.product_name}{$item.product_name}{/if}{if $item.variant_uid}{$item.variant_uid}{/if}</a>
    </div>
  </div>
  {/foreach} 
{/if}

