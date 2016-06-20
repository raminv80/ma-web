{block name=body}

<div id="maincont">
	<div class="container">
		<div class="row" id="productout">
			<div class="col-sm-6" id="prodleft">

			<div id="back">
				<a href="/products">
					<img src="/images/back.png" alt="" /> Back to product page
				</a>
			</div>

			<div id="prodslider" class="carousel slide" data-ride="carousel">
			<!-- Wrapper for slides -->
			<div class="carousel-inner" role="listbox">
				{assign var='count' value=0}
				{foreach $gallery as $image }
				<div class="item {if $count eq 0}active{/if}">
					<img src="{$image.gallery_link}?height=560" title="{$image.gallery_title}" alt="{$image.gallery_alt_tag}" class="img-responsive">
				</div>
				{assign var='count' value=$count+1}
				{/foreach}
				{if $count eq 0}
				<div class="item {if $count eq 0}active{/if}">
					<img src="/images/no-image-available.jpg?height=560" title="Placeholder" alt="Placeholder" class="img-responsive">
				</div>
        {/if}
			</div>

			<!-- Controls -->
			{if $gallery|@count gt 1}
			<a class="left carousel-control" href="#prodslider" role="button" data-slide="prev">
				<img src="/images/left.png" alt="Previous" />
			</a>
			<a class="right carousel-control" href="#prodslider" role="button" data-slide="next">
				<img src="/images/right.png" alt="Next" />
			</a>
			{/if}

			</div>
			{if $gallery|@count gt 1}
				<div class="swmore text-center">Swipe for more images</div>
			{/if}
			</div>
			<div class="col-sm-6" id="prodright">
			<div class="catname">{$listing_parent.listing_name}</div>
			<h2>{$product_name}</h2>
{if $REQUEST_URI|strstr:"/ergonomic-accessories/" || $REQUEST_URI|strstr:"/sit-to-stand/mid-duty-electric-height-adjustable-desks"}
			<form class="form-horizontal" id="product-form" role="form" accept-charset="UTF-8" action="" method="post">
			<input type="hidden" value="ADDTOCART" name="action" id="action" />
			<input type="hidden" name="formToken" id="formToken" value="{$token}" />
			<input type="hidden" value="{$product_object_id}" name="product_id" id="product_id" />
			<input type="hidden" value="{$listing_parent.listing_object_id}" name="listing_id" id="listing_id" />

				{foreach $attribute as $attr }
					{if $attr.attr_value}
					<div class="form-group">
						<label for="address_state_1" class="col-sm-12 col-md-12 control-label">{$attr.attribute_name}:</label>
						<div class="col-sm-10 col-md-6">
							<select id="{urlencode data=$attr.attribute_name}" name="attr[{$attr.attribute_id}]" class='form-control modifier required' >
									<option value=""
											price="0"
											instock="0"
											weight ="0"
											width ="0"
											height ="0"
											length ="0"
											name =""
											>Select one
									</option>
								{foreach $attr.attr_value as $value }
									<option value="{$value.attr_value_id}"
											price="{if $product_specialprice neq '0.00'}{$value.attr_value_specialprice}{else}{$value.attr_value_price}{/if}"
											instock="{$value.attr_value_instock}"
											weight ="{$value.attr_value_weight}"
											width ="{$value.attr_value_width}"
											height ="{$value.attr_value_height}"
											length ="{$value.attr_value_length}"
											name ="{urlencode data=$value.attr_value_name}"
											>{$value.attr_value_name}
									</option>
								{/foreach}
							</select>
						</div>
					</div>
					{/if}
				{/foreach}

				<div class="form-group">
					<div class="prodprice col-sm-12">
						{if $product_specialprice eq '0.00'}
							<div style="display:inline;">$</div>
							<div style="display:inline;" id="cal-price" value="{$product_price}">{$product_price|number_format:2:'.':','}</div>
						{else}
							<div style="display:inline;text-decoration: line-through;">$</div>
							<div style="display:inline;text-decoration: line-through;">{$product_price|number_format:2:'.':','}</div>
							<br>
							<div style="display:inline;color:#FF4822">Special Price: $</div>
							<div style="display:inline;color:#FF4822" id="cal-price" value="{$product_specialprice}">{$product_specialprice|number_format:2:'.':','}</div>
						{/if}
						<div style="display:inline;"><input type="hidden" value="{$product_price}" name="price" id="price" /> </div>
						{if $product_gst}
						<!--<div style="display:inline;color:#aaa"><small>(Incl. GST)</small></div>-->
						{else}
						<div style="display:inline;color:#aaa"><small>(GST-free)</small></div>
						{/if}

					</div>
				</div>
				{if $product_instock}
					<!--<div class="form-group">
						<label for="address_state_1" class="col-sm-2 control-label">Qty: </label>
						<div class="col-sm-2">
							<input type="text" value="1" name="quantity" id="quantity" class="form-control unsigned-int gt-zero" pattern="[0-9]" >
						</div>
					</div>-->
					<div class="form-group">
						<div class="col-sm-12">
							<a class="btn btn-blue" onclick="$('#product-form').submit();">Add to Cart <img src="/images/cartblue1.png" alt="" /></a>
							<div style="display:inline;color:#ff0000" id="error-text"></div>
						</div>
					</div>
				{else}
					<div class="form-group">
						<div style="display:inline;color:#ff0000">Out of stock</div>
					</div>
				{/if}
				</form>
{else}
	<div>
		<b>Interested in this product? Please <a href="/contact-us">contact us</a></b><br><br>
	</div>
{/if}
					{if $product_content1}
					<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingOne">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
								description <div class="arrowbox"><img src="/images/accordion-arrow.png" alt="" /></div>
								</a>
							</h4>
						</div>
						<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
							<div class="panel-body">
							{$product_content1}
							</div>
						</div>
					</div>
					{/if}
					{if $product_content3}
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingTwo">
							<h4 class="panel-title">
								<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
								Specifications <div class="arrowbox"><img src="/images/accordion-arrow.png" alt="" /></div>
								</a>
							</h4>
						</div>
						<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
							<div class="panel-body">
							{$product_content3}
							</div>
						</div>
					</div>
					{/if}
					{if $product_content2}
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingThree">
							<h4 class="panel-title">
								<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
								Features <div class="arrowbox"><img src="/images/accordion-arrow.png" alt="" /></div>
								</a>
							</h4>
						</div>
						<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
							<div class="panel-body">
							{$product_content2}
							</div>
						</div>
					</div>
					{/if}
					{if $product_content4}
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingFour">
							<h4 class="panel-title">
								<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
								Downloads <div class="arrowbox"><img src="/images/accordion-arrow.png" alt="" /></div>
								</a>
							</h4>
						</div>
						<div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
							<div class="panel-body">
							{$product_content4}
							</div>
						</div>
					</div>
					{/if}



				</div>
			</div>
		</div>
	</div>
</div>

{if $associated_products}

<div id="related">
	Related products
</div>

<div id="relatedcont">
	<div class="container">
			<div class="row">
				<div class="col-sm-12" id="homeprods">
				{foreach from=$associated_products key=k item=item}
				<div class="product">
					<div itemscope itemtype="http://schema.org/Product">
						<div class="productin">
						<a href="{if $parenturl neq ''}{$parenturl}/{$item.product_url}{else}/{$item.cache_url}{/if}">
						<meta itemprop="brand" content="{$item.brand.0.listing_name}">
						<meta itemprop="description" content="{$item.product_content1|strip_tags}">
						<!--<a href="{if $parenturl neq ''}{$parenturl}/{$item.product_url}{else}/{$item.cache_url}{/if}" itemprop="url">
							<!-- <img itemprop='image' src="{if $item.gallery.0.gallery_link neq ''}{$item.gallery.0.gallery_link}?height=276{else}/images/no-image-available.jpg{/if}" alt="{$item.gallery.0.gallery_alt_tag}" title="{$item.gallery.0.gallery_title}" class="img-responsive"> -->
						<!--	<img itemprop='image' src="{exist_image image=$item.gallery.0.gallery_link|cat:'?height=276' default='/images/no-image-available.jpg?height=180'}" alt="{$item.gallery.0.gallery_alt_tag}" title="{$item.gallery.0.gallery_title}" class="img-responsive">
						</a> -->
						<div class="prodimg">
							<img itemprop='image' src="{exist_image image=$item.gallery_link|cat:'?width=235&height=235' default='/images/no-image-available.jpg?height=235'}" alt="{$item.gallery_alt_tag}" title="{$item.gallery_title}" class="img-responsive" />
						</div>

						<div class="prodinfo">
							<div class="row">
								<div class="col-sm-12">
									<div class="prodcat pull-left">Sit to stand</div>
								</div>
								<div class="col-sm-12">
									<div class="prodname1 pull-left" itemprop="name">{$item.product_name}</div>
								</div>
								<div class="col-sm-12">
									<div class="prodprice1 pull-left" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
									{if $item.product_instock eq 1}
									<meta itemprop="availability" href="http://schema.org/InStock" />
									{else}
									<meta itemprop="availability" href="http://schema.org/OutOfStock" />
									{/if}
										<div class="prodfullprice">
										<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="prodprice">
										{if $item.product_specialprice > 0.0}
											<span itemprop="price" class="prodsave">${$item.product_specialprice|number_format:2:'.':','}</span>
										{else}
											<span itemprop="price">${$item.product_price|number_format:2:'.':','}</span>
										{/if}
										</div>
										</div>
									</div>
									<img src="/images/cart.png" align="Cart" class="cartimg pull-right" />
								</div>
							</div>
						</div>
						{if $item.product_specialprice > 0.0}
							<img src="/images/sale.png" alt="Sale" id="saleimg" />
						{/if}
						<div class="prodhover">
							<div class="circle">
								View product
							</div>
						</div>
						</a>
						</div>
					</div>
				</div>
				{/foreach}
				</div>
			</div>
	</div>
</div>
		{/if}

{/block}

{block name=tail}
	<script type="text/javascript">


		$(document).ready(function(){

		if (Modernizr.touch) {
			$(".swmore").addClass("touch");
		}

			$('#product-form').validate({
				onkeyup: false,
				onclick: false
			});

			var attributes = [];
			{if $attribute}
				{foreach $attribute as $attr }
					var {urlencode data=$attr.attribute_name} = getParameterByName('{urlencode data=$attr.attribute_name}');
					attributes.push( getParameterByName('{urlencode data=$attr.attribute_name}') );

					$("#{urlencode data=$attr.attribute_name} option[name*='"+ {urlencode data=$attr.attribute_name} +"']").attr("selected", "selected");
				{/foreach}
			{/if}
			calculatePrice();

			ga('ec:addProduct', {
	 	    'id': '{$product_object_id}',
	 	    'name': '{$product_name}',
	 	    'category': '{$product_FullCategoryName}',
	 	    'brand': '{$product_brand}',
	 	    'variant': attributes.join('/')
	 	  });
	 	  ga('ec:setAction', 'detail');

		});

		function getParameterByName(name) {
		    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
		    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
		        results = regex.exec(location.search);
		    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
		}


	</script>

{/block}
