{block name=productslist}
<div id="productslist">
	{foreach $products as $product}
		<div id="product" class="product">
			<div class="product-image"><a href="{$product.link}"><img src="{$product.image}" /></a></div>
			<div class="product-name"><h3><a href="{$product.link}">{$product.name}</a></h3></div>
			<div class="product-price">&#36;{$product.price}</div>
			<div class="add-to-cart"><input type="button" value="Add To Cart" class="add-to-cart-btn" /></div>
			<div class="compare2">
				<div>
					<div class="rollovertext" title="">
						<span class="comparecheck">
							<input id="ctl00_ContentPlaceHolder2_ProductsList_ctl00_Check" type="checkbox" name="ctl00$ContentPlaceHolder2$ProductsList$ctl00$Check">
						</span>
						<span class="rollovertext" title="">
							<a id="ctl00_ContentPlaceHolder2_ProductsList_ctl00_Compare" href="javascript:__doPostBack('ctl00$ContentPlaceHolder2$ProductsList$ctl00$Compare','')">Compare</a>
						</span>
					</div>
				</div>
			</div>
		</div>
	{/foreach}
	
	<div class="clear"></div>
	
</div>
{/block}