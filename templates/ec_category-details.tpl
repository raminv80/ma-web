{block name=head}
<style type="text/css">
</style>
{/block}


{block name=body}
{* Define the function *}
{function name=render_product_list level=0 parenturl="" menu=0}
  {foreach $items as $item}
    <div class="product">
       {include file='product_list_struct.tpl'}
     </div>
  {/foreach}
{/function}
{* Define the function *}
{function name=render_category_list level=0 parenturl="" menu=0}
  {foreach $items as $item}
    <div class="col-sm-3 col-xs-6 text-center">
       <div>
       <a href="{$parenturl}/{$item.listing_url}" title="View {$item.listing_name} Products"><img src="{if $item.listing_image neq ''}{$item.listing_image}{else}/images/no-image-available.png{/if}?width=600&height=600&crop=1" alt="{$item.listing_name}" title="{$item.listing_name}" class="img-responsive">
       	<div class="prodname">{$item.listing_name}</div>
       </a>
       <!-- {$item.listing_content1}
       <a href="{$parenturl}/{$item.listing_url}" class="grey btn">View {$item.listing_name} Products</a> -->
       </div>
     </div>
  {/foreach}
{/function}
<div id="maincont">
  <div class="container" id="prolist">
    <div class="row">
      <div class="col-sm-12 text-center" id="listtoptext">
				<h1>{$listing_name}</h1>
      </div>
      <div class="col-sm-12 col-md-8 col-md-offset-2 text-center" id="cattoptext">
      	{$listing_content1}
      </div>
      {if count($data.listings) > 0}
      <div id="categorycontainer">
        {assign var='count' value=0}
       {call name=render_category_list items=$data.listings parenturl=$REQUEST_URI}
      </div>
      {/if}
      <div class="col-sm-12" id="prodcontainer">
        {assign var='count' value=0}
       {call name=render_product_list items=$data.products parenturl=$REQUEST_URI parent_name=$listing_name}
      </div>
    </div>
  </div>
</div>

{/block}

{* Place additional javascript here so that it runs after General JS includes *}
{block name=tail}
{/block}
