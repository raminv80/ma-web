{block name=head}
<style type="text/css">
</style>
{/block}


{block name=body}
{* Define the function *}
{function name=render_product_list level=0 parenturl="" menu=0}
  {foreach $items as $item}
    <div class="item">
       <div>
       {include file='product_list_struct.tpl'} 
       </div>
     </div>
  {/foreach}  
{/function}
{* Define the function *}
{function name=render_category_list level=0 parenturl="" menu=0}
  {foreach $items as $item}
    <div class="item">
       <div>
       <a href="{$parenturl}/{$item.listing_url}"><img src="{if $item.listing_image neq ''}{$item.listing_image}{else}/images/no-image-available.jpg{/if}" alt="{$item.listing_name}" title="{$item.listing_name}" class="img-responsive"></a>
       <div class="prodname">{$item.listing_name}</div>
       {$item.listing_content1}
       <a href="{$parenturl}/{$item.listing_url}" class="grey btn">View {$item.listing_name} Products</a>
       </div>
     </div>
  {/foreach}  
{/function}
<div id="maincont">
  <div class="container">
    <div class="row">
      <div class="col-sm-12" id="breadcrumbs">
        {include file='breadcrumbs.tpl'} 
      </div>
      {if count($data.listings) > 0}
      <div class="col-sm-12">
        <h3>{$listing_name}</h3>
        <div id="subhead">{count($data.listings)} sub-categories found</div>
      </div>
      <div class="col-sm-12" id="prodcontainer">
        {assign var='count' value=0}
       {call name=render_category_list items=$data.listings parenturl=$REQUEST_URI}
      </div>
      {/if}
      <div class="col-sm-12">
        <h3>{$listing_name}</h3>
        <div id="subhead">{count($data.products)} products found</div>
      </div>
      <div class="col-sm-12" id="prodcontainer">
        {assign var='count' value=0}
       {call name=render_product_list items=$data.products parenturl=$REQUEST_URI}
      </div>
    </div>
  </div>
</div>

{/block}

{* Place additional javascript here so that it runs after General JS includes *}
{block name=tail}
{/block}
