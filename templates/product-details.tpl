{block name=productdetails}
<div id="productdetails">

	<div id="product" class="product-large">
	
		<div id="product-images">
			<div class="images-small" id="images-small">
				{foreach $productimages as $image}
					<div class="image-small"><img onclick="javascript:swapImage('{$image.id}')" alt="" src="{$image.image}" id="{$image.id}" width="62px;"></div>
				{/foreach}
			</div>
			<div id="large-image">
				<table cellspacing="0" cellpadding="0" id="product-image">
					<tr>
						<td valign="middle" align="center">
							<a onclick="showPopupImage('LargeImageContainer')" id="large-image-link"><img alt="{$product.name}" src="{$productimages[0].image}" width="300"></a>
						</td>
					</tr>
				</table>
				<div id="enlarge-image">
					<p class="enlarge"><a onclick="expandElement('LargeImageContainer')" id="ctl00_ContentPlaceHolder2_largeLink2"><img alt="Enlarge Image" src="images/enlarge.gif">&nbsp;Enlarge Image</a></p>
				</div>
			</div>
		</div>
		
		<div id="product-details">
			<div id="product-name"><h1>{$product.name}</h1></div>
			<div id="product-code">Product Code:{$product.code}</div>
			<div id="product-price">&#36;{$product.price}</div>
			<div id="product-qty">
				Quantity: <select id="qty" name="qty">
					<option></option>
					{foreach $productqty as $qty}
						<option value="{$qty}">{$qty}</option>
					{/foreach}
				</select>
			</div>
			<div class="add-to-cart"><input type="button" value="Add To Cart" class="add-to-cart-btn" /></div>
			<div id="product-reviews">
				<div id="product-reviews-read"><a href="#" onclick="readReviews('{$product.code}')" rel="{$product.code}">Read Reviews</a></div>
				<div id="product-reviews-write"><a href="/write-reviews.php?pid={$product.code}">Write a Review</a></div>
			</div>
		</div>

	</div>
	<div class="clear"></div>
	
	<div id="product-details-tabs">
		<div id="product-description-tab" class="product-tab">Product Description</div>
		<div id="product-description">{$product.description}</div>
		<div id="product-specifications-tab" class="product-tab">Specifications</div>
		<div id="product-specifications">{$product.specifications}</div>
		<div id="product-reviews-tab" class="product-tab">Customer Reviews</div>
		<div id="product-reviews">{$product.reviews}</div>
	</div>

</div>
{/block}