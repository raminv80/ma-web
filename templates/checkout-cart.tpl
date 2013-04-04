{block name=cart}
<div class="cart">
	<div class="cart-content">
	<div class="item-header">
		<div class="item-name grid_6"><b>Item</b></div>
		<div class="item-option grid_4"><b>Option</b></div>
		<div class="item-quantity grid_3"><b>Quantity</b></div>
		<div class="item-price grid_2"><b>Price</b></div>
	</div>
	<div class="clear"></div>
	{foreach key=key item=cartitem from=$cart}
	<div class="item-row">
		<div class="item-name grid_6">{$cartitem.name}</div>
		<div class="item-option grid_4">{$cartitem.option}</div>
		<div class="item-quantity grid_3" >X{$cartitem.quantity}</div>
		<div class="item-price grid_2">${$cartitem.price}</div>
	</div>
	<div class="clear"></div>
	{/foreach}
	</div>
	<div class="clear"></div>
	<div class="cart-total grid_3"><span class="cart-total-header">Total (Inc GST): </span>${$carttotal}</div>
	<div class="clear"></div>
</div>
{/block}