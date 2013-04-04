{block name=article}
	<div class="food-item{if $count == 4} last{/if}">
		<form name="product-form_{$product.category_id}_{$product.product_id}"
			id="product-form_{$product.category_id}_{$product.product_id}">
			<input type="hidden" name="product_id" value="{$product.product_id}" />
			<input type="hidden" name="category_id"
				value="{$product.category_id}" />
			<div class="food-item-img">
				<a title="{$product.product_name}" href="{$product.product_image}"
					rel="lightbox"><img src="{$product.product_image}"> </a>
			</div>
			<div class="food-item-details">
				<h3 class="food-item-title">{$product.product_name}</h3>
				<div class="food-item-description">{$product.product_description}</div>
				{if !$product.show_price}
				<div class="food-item-price">${$product.product_price} Each</div>
				{/if}
				{if $product.hasattr} 
				<div class="food-item-option">
				<select id="attribute" name="attribute_id">
					{foreach key=key item=attribute from=$product.attributes}
					<option value="{$attribute.attribute_id}">{$attribute.attribute_name}
						{if $product.show_price}(${$attribute.attribute_price}){/if}</option>
					{/foreach}
				</select> 
				</div>
				{/if}
			</div>
			<div class="clear"></div>
			
			<div class="food-item-qty">
				<table cellpadding="0" cellspacing="0" border="0">
					<tr valign="top">
						<td><input placeholder="Qty" name="quantity" value="1" id="quantity" /> <input
							type="hidden" name="Action" value="ADDTOCART" />
						</td>
						<td><a href="javascript:void(0)"
							onclick="AddProduct('#product-form_{$product.category_id}_{$product.product_id}');"
							title="Add to cart"><img
								src="/images/template/add-to-cart-btn.jpg" border="0" /> </a></td>
					</tr>
				</table>
			</div>
		</form>
	</div>
	<!-- end of FOOD ITEM -->
{/block}