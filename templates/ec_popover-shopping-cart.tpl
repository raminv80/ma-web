<div id='cart-info' class='cart-info'>
	<div class='cart-product-summary'>
		{foreach from=$productsOnCart item=item}
		<div class="row cartitem">
			<div class="col-sm-3">
				<img src="{if $item.gallery.0.gallery_link neq ''}{$item.gallery.0.gallery_link}{else}/images/no-image-available.jpg{/if}?height=38&width=60&crop=1" alt="{$item.gallery.0.gallery_alt_tag}" title="{$item.gallery.0.gallery_title}" class="cart-overview-image">
			</div>
			<div class="col-sm-8 col-sm-offset-1" style="padding-left: 5px; padding-right: 5px; margin-left: 0;">
				<div class="row">
					<div class="col-sm-12" style="padding-left: 5px; padding-right: 5px;">
						<strong>{$item.cartitem_product_name}</strong>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6" style="font-size: 12px;font-weight: 500; padding-left: 5px; padding-right: 5px;">
							Qty: {$item.cartitem_quantity}
					</div>
					<div class="col-sm-6 text-right" style="font-size: 15px;font-weight: bold;color: #555555; padding-left: 5px; padding-right: 5px;">
							${$item.cartitem_subtotal|number_format:2:'.':','}
					</div>
				</div>
			</div>
		</div>
		{/foreach}
		<div class="row">
		<div class="col-sm-12">
			<div style="border-bottom: 2px solid #E7E7E7;color: #555555;border-top: 2px solid #E7E7E7;">
				<div class="row" style="margin:1px 0; background-color: #F2F2F2; padding: 12px 0;font-size: 14px;">
					<div class="col-sm-6"> <strong>Subtotal</strong> </div>
					<div class="col-sm-6" style="font-size: 20px;font-weight:600;text-align:right;">
						${$subtotal|number_format:2:'.':','}
					</div>
				</div>
			</div>
		</div>
		</div>
		<div class="row" style="padding:10px 0;"> <div class="col-sm-12"> </div> </div>
		<div class="row"> <div class="col-sm-12 text-right"> <a href="/shopping-cart" class="btn btn-red">Checkout</a> </div> </div>
	</div>
</div>

<script>
	function closemenu(){
		$("#shop-cart-btn").hide();
	}
</script>