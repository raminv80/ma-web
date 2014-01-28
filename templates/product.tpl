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
		<div class="row">
		{foreach $product_info.gallery as $image }
			<img src="{$image.gallery_link}" title="{$image.gallery_title}" alt="{$image.gallery_alt_tag}">
		{/foreach}
		</div>
		<div class="row">Description: {$product_description}</div>
		<div class="row">
		<form class="form-horizontal" id="product-form" accept-charset="UTF-8" action="" method="post">
			
			<input type="hidden" value="ADDTOCART" name="action" id="action" /> 
			<input type="hidden" value="{$product_id}" name="product_id" id="product_id" /> 
						
				{foreach $product_info.attribute as $attr }
					{if $attr.attr_value}
					<div style="display:inline;"><b>{$attr.attribute_name}: </b> 
						<select id="attr-{$attr.attribute_id}" name="attr[{$attr.attribute_id}]" class='modifier' >
							{foreach $attr.attr_value as $value }
								<option value="{$value.attr_value_id}" 
										price="{$value.attr_value_price}"
										specialprice="{$value.attr_value_specialprice}"
										instock="{$value.attr_value_instock}"
										weight ="{$value.attr_value_weight}"
										width ="{$value.attr_value_width}"
										height ="{$value.attr_value_height}"
										length ="{$value.attr_value_length}"
										>{$value.attr_value_name}
								</option>
							{/foreach}
						</select>
					</div>
					{/if}
				{/foreach}
				
				<div style="display:inline;">Price: $</div>
				<div style="display:inline;" id="cal-price" value="{$product_price}">{$product_price}</div>
				<div style="display:inline;"><input type="hidden" value="{$product_price}" name="price" id="price" /> </div>
				
				
				<div style="display:inline;"><input type="text" value="1" name="quantity" id="quantity" class="unsigned-int gt-zero" ></div>
				<div style="display:inline;"><a class="btn-primary btn" onclick="addCart();">Add to Cart</a></div>
				
				
						
			</div>
		
		</form>
		
		
		{include file='full-product-cat.tpl'}
	</div>

	<script type="text/javascript" src="/includes/js/shopping-cart.js"></script>
{/block}
