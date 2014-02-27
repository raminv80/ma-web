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
	{if $productsOnCart }
		<div class="row" style="margin-top: 40px; background-color: rgb(238, 238, 238);">
			<div style="display:inline;" class="col-md-6">Product</div>
			<div style="display:inline; text-align:right;" class="col-md-2">Unit Price</div>
			<div style="display:inline;" class="col-md-1">Qty</div>
			<div style="display:inline; text-align:right;" class="col-md-2">Subtotal</div>
			<div style="display:inline;" class="col-md-1"></div>
		</div>
		
		<form class="form-horizontal" id="shopping-cart-form" accept-charset="UTF-8" action="" method="post">
			<div class="row" style="margin-top: 20px; margin-bottom: 40px;" id="products-container"> 
				<input type="hidden" name="formToken" id="formToken" value="{$token}" />
				{foreach from=$productsOnCart item=item}
				<div class="row  product-item" style="margin-top: 10px;" id="{$item.cartitem_id}">
					<div style="display:inline;" class="col-md-6">{$item.cartitem_product_name}
					{if $item.attributes } 
						<small>
						{foreach from=$item.attributes item=attr}
							- {$attr.cartitem_attr_attribute_name}: {$attr.cartitem_attr_attr_value_name} 
						{/foreach}
						</small>
					{/if}
					</div>
					<div style="display:inline; text-align:right;" class="col-md-2">${$item.cartitem_product_price|number_format:2:".":","}</div>
					<div style="display:inline;" class="col-md-1"><input type="text" value="{$item.cartitem_quantity}" name="qty[{$item.cartitem_id}]" id="quantity" class="unsigned-int gt-zero" style="width: 30px; text-align: right;"></div>
					
					<div style="display:inline; text-align:right;" class="col-md-2" id="subtotal-{$item.cartitem_id}">${$item.cartitem_subtotal|number_format:2:".":","}</div>
					<div style="display:inline;" class="col-md-1"><a href="javascript:void(0)" onclick="deleteItem('{$item.cartitem_id}');"><span class="label label-danger">Delete</span></a></div>
				</div>		
				{/foreach} 
			</div>
			<div class="row">
				<div class="col-md-offset-8"><a class="btn-success btn btn-sm" onclick="updateCart();">Update</a></div>
			</div>
		</form>
		
		<div class="row" style="margin-top: 20px;">
			<div style="display:inline; text-align:right;" class="col-md-10">Subtotal</div>
			<div style="display:inline; text-align:right;" class="col-md-2" id="subtotal">${$cart.cart_subtotal|number_format:2:".":","}</div>
		</div>
		<div class="row" style="margin-top: 20px;">
			<div style="display:inline; text-align:right;" class="col-md-10">Discount {if $cart.cart_discount_code}<small>[Code: {$cart.cart_discount_code}]</small>{/if}</div>
			<div style="display:inline; text-align:right;" class="col-md-2" id="discount">-${$cart.cart_discount|number_format:2:".":","}</div>
		</div>
		<div class="row" style="margin-top: 20px;">
			<div style="display:inline; text-align:right;" class="col-md-10">Postage & Handling</div>
			<div style="display:inline; text-align:right;" class="col-md-2" id="shipping">${$cart.cart_shipping_fee|number_format:2:".":","}</div>
		</div>
		<div class="row" style="margin-top: 20px;">
			<div style="display:inline; text-align:right;font-weight: bold;" class="col-md-10">Total</div>
			<div style="display:inline; text-align:right;font-weight: bold;" class="col-md-2" id="total">${$cart.cart_total|number_format:2:".":","}</div>
		</div>
		<div class="row" style="margin-top: 20px;">
			<div class="pull-right">
				{if $allowGuest}
					{if !$user.id} <a class="btn-info btn btn-sm" href="javascript:void(0)" onclick="$('#login-modal').modal('show');" id="login-btn">Log In</a>{/if}
					<a class="btn-success btn btn-sm" href="/store/checkout" id="checkout-btn">Checkout {if !$user.id}as Guest{/if}</a>
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
