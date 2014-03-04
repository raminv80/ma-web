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
	{if $validation}
		{foreach $validation as $val_msg}
			<div class="alert alert-danger fade in">
				<button class="close" aria-hidden="true" data-dismiss="alert" type="button">&times;</button>
				<strong>{$val_msg}</strong>
			</div>
		{/foreach}
	{/if}
	{if $productsOnCart }
		<div class="row cart-table-header">
			<div class="col-md-6">Product</div>
			<div class="col-md-2 text-right">Unit Price</div>
			<div class="col-md-1">Qty</div>
			<div class="col-md-2 text-right">Subtotal</div>
			<div class="col-md-1"></div>
		</div>
		
		<form class="form-horizontal" id="shopping-cart-form" accept-charset="UTF-8" action="" method="post">
			<div class="row" id="products-container"> 
				<input type="hidden" name="formToken" id="formToken" value="{$token}" />
				{foreach from=$productsOnCart item=item}
				<div class="row  product-item" id="{$item.cartitem_id}">
					<div class="col-md-6">{$item.cartitem_product_name}
					{if $item.attributes } 
						<small>
						{foreach from=$item.attributes item=attr}
							- {$attr.cartitem_attr_attribute_name}: {$attr.cartitem_attr_attr_value_name} 
						{/foreach}
						</small>
					{/if}
					</div>
					<div class="col-md-2 text-right">${$item.cartitem_product_price|number_format:2:".":","}</div>
					<div class="col-md-1"><input type="text" value="{$item.cartitem_quantity}" name="qty[{$item.cartitem_id}]" id="quantity" class="unsigned-int gt-zero" style="width: 30px; text-align: right;"></div>
					
					<div class="col-md-2 text-right" id="subtotal-{$item.cartitem_id}">${$item.cartitem_subtotal|number_format:2:".":","}</div>
					<div class="col-md-1"><a href="javascript:void(0)" onclick="deleteItem('{$item.cartitem_id}');"><span class="label label-danger">Delete</span></a></div>
				</div>		
				{/foreach} 
			</div>
			<div class="row">
				<div class="col-md-offset-8"><a class="btn-success btn btn-sm" onclick="updateCart();">Update</a></div>
			</div>
		</form>
		
		<div class="row totals">
			<div class="col-md-10 text-right">Subtotal</div>
			<div class="col-md-2 text-right" id="subtotal">${$totals.subtotal|number_format:2:".":","}</div>
		</div>
		<div class="row totals">
			<div class="col-md-10 text-right">Discount {if $cart.cart_discount_code}<small>[Code: {$cart.cart_discount_code}]</small>{/if}</div>
			<div class="col-md-2 text-right" id="discount">-${$totals.discount|number_format:2:".":","}</div>
		</div>
		<div class="row totals">
			<div class="col-md-10 text-right"><b>Total</b></div>
			<div class="col-md-2 text-right" id="total"><b>${$totals.total|number_format:2:".":","}</b></div>
		</div>
		<div class="row totals">
			<div class="pull-right">
				{if $user.gname}
					<a class="btn-success btn btn-sm" href="/store/checkout" id="checkout-btn">Checkout</a>
				{else}
					<a class="btn-success btn btn-sm" href="javascript:void(0)" onclick="$('.redirect').val('http://'+(document.domain)+ '/store/checkout');$('#login-modal').modal('show');" id="checkout-btn">Checkout</a>
				{/if}
			</div>
		</div>
	
		<form class="form-horizontal" id="discount-form" accept-charset="UTF-8" action="/process/cart" method="post">
			<input type="hidden" value="applyDiscount" name="action" id="action" /> 
			<input type="hidden" name="formToken" id="formToken" value="{$token}" />
			<div class="row" style="margin-top: 20px; margin-bottom: 40px;"> 
				<div style="display:inline;">Enter discount code: </div>
				<div style="display:inline;"><input type="text" value="{if $post}{$post.discount_code}{/if}" name="discount_code" id="discount"></div>
				<div style="display:inline;"><a class="btn-primary btn btn-sm" onclick="$('#discount-form').submit();">Apply</a></div>
				{if $error}
				<div style="margin-left:10px; display:inline; color:#ff0000">{$error}</div>
		        {/if}
			</div>
		</form>	
	{else}
		<div class="row" style="margin: 60px; background-color: rgb(238, 238, 238);">
			<div style="display:inline;">Your shopping cart is empty.</div>
		</div>
	{/if}
	</div>
	

{/block}
