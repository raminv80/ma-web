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
		<div class="row" style="margin: 40px; background-color: rgb(238, 238, 238);">
			<div style="display:inline;" class="col-md-6">Product</div>
			<div style="display:inline;" class="col-md-2">Unit Price</div>
			<div style="display:inline;" class="col-md-1">Qty</div>
			<div style="display:inline;" class="col-md-2">Subtotal</div>
			<div style="display:inline;" class="col-md-1"></div>
		</div>
		
		<form class="form-horizontal" id="shopping-cart-form" accept-charset="UTF-8" action="" method="post">
			<div class="row" style="margin: 40px;" id="products-container"> 
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
					<div style="display:inline;" class="col-md-2">${$item.cartitem_product_price}</div>
					<div style="display:inline;" class="col-md-1"><input type="text" value="{$item.cartitem_quantity}" name="qty[{$item.cartitem_id}]" id="quantity" class="unsigned-int gt-zero" style="width: 30px; text-align: right;"></div>
					
					<div style="display:inline;text-align:right;" class="col-md-2" id="subtotal-{$item.cartitem_id}">{$item.cartitem_subtotal}</div>
					<div style="display:inline;" class="col-md-1"><a href="javascript:void(0)" onclick="deleteItem('{$item.cartitem_id}');"><span class="label label-danger">Delete</span></a></div>
				</div>		
				{/foreach} 
			</div>
		</form>
		<div class="row pull-right"><a class="btn-success btn" onclick="updateCart();">Update</a></div>
	</div>
	
<script type="text/javascript" src="/includes/js/shopping-cart.js"></script>

{/block}
