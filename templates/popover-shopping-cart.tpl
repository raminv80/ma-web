<div id='cart-info' class='cart-info'>
	<div class='cart-product-summary'>
		<table width='100%' cellspacing='0' cellpadding='0' border='0'>
			<tbody>
			{foreach from=$productsOnCart item=item}
				<tr>
					<td><img src='{if $item.gallery.0.gallery_link neq ''}{$item.gallery.0.gallery_link}{else}/images/no-image-available.jpg{/if}?height=60' alt="{$item.gallery.0.gallery_alt_tag}" title="{$item.gallery.0.gallery_title}" class='cart-overview-image'></td>
					<td class='cart-product-summary-details'>
						<a href="{$item.url}"><strong>{$item.cartitem_product_name}</strong></a>
						<br>Qty: {$item.cartitem_quantity}</td>
					<td class='cart-product-summary-price'>${$item.cartitem_subtotal|number_format:2:'.':','}</td>
				</tr>
			{/foreach}
			</tbody>
		</table>
	</div>
	<div class='cart-total-price-2'>
		<table width='100%' cellspacing='0' cellpadding='0'>
			<tbody>
				<tr>
					<td width='120px'>Subtotal</td>
					<td align='right'><div class='nav-subtotal'><strong>${$subtotal|number_format:2:'.':','}</strong></div></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class='cont-shopping'>
		<!-- <a href='javascript:void(0)' onclick='closemenu();' class='btn btn-blue pull-left'>Continue shopping</a> -->
		<a href='/shopping-cart' class='btn btn-blue pull-right'>Checkout</a>
		<div class='clear'></div>
	</div>
</div>

<script>
	function closemenu(){
		$("#shop-cart-btn").hide();
	}
</script>