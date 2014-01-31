{block name=body}
	<header>
		<div id="headout" class="headerbg">
				<div id="videobox">
					<div class="container">
						<div class="row-fluid">
							<div class="span7">
					  			{include file='breadcrumbs.tpl'}
					  			<h3 class="toptitle">{$product_name}</h3>
				  			</div>
						</div>
					</div>
				</div>
			</div>
	</header>
	<div class="container">
	{if $products }
		<div class="row" style="margin-top: 20px; background-color: rgb(238, 238, 238);">
			<div style="display:inline;" class="col-md-6">Product</div>
			<div style="display:inline; text-align:right;" class="col-md-2">Unit Price</div>
			<div style="display:inline;" class="col-md-1">Qty</div>
			<div style="display:inline; text-align:right;" class="col-md-2">Subtotal</div>
			<div style="display:inline;" class="col-md-1"></div>
		</div>
		
			<div class="row" style="margin-top: 20px; margin-bottom: 10px;" id="products-container"> 
				{foreach from=$products item=item}
				<div class="row" style="margin-top: 10px;" id="{$item.cartitem_id}">
					<div style="display:inline;" class="col-md-6">{$item.cartitem_product_name}
					{if $item.attributes } 
						<small>
						{foreach from=$item.attributes item=attr}
							- {$attr.cartitem_attr_attribute_name}: {$attr.cartitem_attr_attr_value_name} 
						{/foreach}
						</small>
					{/if}
					</div>
					<div style="display:inline; text-align:right;" class="col-md-2">${$item.cartitem_product_price}</div>
					<div style="display:inline; text-align:right;" class="col-md-1">{$item.cartitem_quantity}</div>
					
					<div style="display:inline; text-align:right;" class="col-md-2">${$item.cartitem_subtotal}</div>
					
				</div>		
				{/foreach} 
			</div>
			
		
		<div class="row" style="margin-top: 10px;">
			<div style="display:inline; text-align:right;" class="col-md-10">Subtotal</div>
			<div style="display:inline; text-align:right;" class="col-md-2" id="subtotal">${$cart.cart_subtotal}</div>
		</div>
		<div class="row" style="margin-top: 10px;">
			<div style="display:inline; text-align:right;" class="col-md-10">Discount</div>
			<div style="display:inline; text-align:right;" class="col-md-2" id="discount">${$cart.cart_discount}</div>
		</div>
		<div class="row" style="margin-top: 10px;">
			<div style="display:inline; text-align:right;" class="col-md-10">Postage & Handling</div>
			<div style="display:inline; text-align:right;" class="col-md-2" id="shipping">${$cart.cart_shipping_fee}</div>
		</div>
		<div class="row" style="margin-top: 10px;">
			<div style="display:inline; text-align:right;" class="col-md-10">Total</div>
			<div style="display:inline; text-align:right;" class="col-md-2" id="total">${$cart.cart_total}</div>
		</div>
	
		
	{else}
		<div class="row" style="margin: 60px; background-color: rgb(238, 238, 238);">
			<div style="display:inline;">Your shopping cart is empty.</div>
		</div>
	{/if}
	</div>
	
<script type="text/javascript" src="/includes/js/shopping-cart.js"></script>

{/block}
