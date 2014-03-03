<div id='cart-info' class='cart-info'>
	<div class='cart-product-summary'>
		<table width='100%' cellspacing='0' cellpadding='0' border='0'>
			<tbody>
			{foreach from=$productsOnCart item=item}
				<tr>
					<td><img src='{$item.gallery.0.gallery_link}' alt="{$item.gallery.0.gallery_alt_tag}" title="{$item.gallery.0.gallery_title}" class='cart-overview-image'></td>
					<td class='cart-product-summary-details'><strong>{$item.cartitem_product_name}</strong>
						{if $attr.cartitem_attr_attribute_name}
						<br>{foreach from=$item.attributes item=attr}
								- {$attr.cartitem_attr_attribute_name}: {$attr.cartitem_attr_attr_value_name} 
							{/foreach}
						{/if}
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
					<td width='120px'>{$itemNumber} item(s) in cart<br> <a href='/store/shopping-cart'>View cart</a></td>
					<td align='right'>Subtotal</td>
					<td align='right'><div class='nav-subtotal'><strong>${$subtotal|number_format:2:'.':','}</strong></div></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class='cont-shopping'>
		<div class='cart-cont-shopping'>
			<a href='/store'>&laquo; Continue shopping</a>
		</div>
		<a href='/store/shopping-cart' class='btn btn-success pull-right'>Shopping Cart</a>
		<div class='clear'></div>
	</div>
</div>