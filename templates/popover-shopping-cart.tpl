{* Define the function *}
{function name=render_popover_productlist}
	{foreach from=$items item=item}
		<div class='row' style='margin-top: 10px;'>
			<div style='display:inline;' class='col-md-6'>{$item.cartitem_product_name}
			{if $item.attributes } 
				<small>
				{foreach from=$item.attributes item=attr}
					- {$attr.cartitem_attr_attribute_name}: {$attr.cartitem_attr_attr_value_name} 
				{/foreach}
				</small>
			{/if}
			</div>
			<div style='display:inline; text-align:right;' class='col-md-6'>{$item.cartitem_quantity} x ${$item.cartitem_product_price}</div>
			
			
		</div>		
	{/foreach} 
{/function}

{if $productsOnCart}
	{call name=render_popover_productlist items=$productsOnCart}
{else}
	<div class='row' style='margin: 10px;' >No items.</div>
{/if}