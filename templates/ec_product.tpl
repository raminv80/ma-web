{block name=body}

<div id="maincont">
  <div class="container">
    <div class="row" id="productout">
      <div class="col-sm-6" id="prodleft">

        <div id="back">
          <a href="/products"> <img src="/images/back.png" alt="" /> Back to product page
          </a>
        </div>

        <div id="prodslider" >
          <div class="carousel-inner" >
            {assign var='count' value=0} {foreach $gallery as $image }
            <div class="item {if $count eq 0}active{/if}">
              <img src="{$image.gallery_link}?height=160" title="{$image.gallery_title}" alt="{$image.gallery_alt_tag}" class="img-responsive">
            </div>
            {assign var='count' value=$count+1} {/foreach} {if $count eq 0}
            <div class="item {if $count eq 0}active{/if}">
              <img src="/images/no-image-available.jpg?height=160" title="Placeholder" alt="Placeholder" class="img-responsive">
            </div>
            {/if}
          </div>

        </div>
      </div>
      <div class="col-sm-6" id="prodright">
        <div class="catname">{$listing_parent.listing_name}</div>
        <h2>{$product_name}</h2>
        <form class="form-horizontal" id="product-form" role="form" accept-charset="UTF-8" action="" method="post">
          <input type="hidden" value="ADDTOCART" name="action" id="action" />
          <input type="hidden" name="formToken" id="formToken" value="{$token}" />
          <input type="hidden" value="{$product_object_id}" name="product_id" id="product_id" />
          <input type="hidden" value="{$listing_parent.listing_object_id}" name="listing_id" id="listing_id" />
          
          <div class="form-group">
            <div class="prodprice col-sm-12">
              <input type="hidden" value="0" name="price" id="price" />
              {$min_price = 999999}
              {$max_price = 0}
              {foreach $variants as $variant}
                {$price = $variant.variant_price}
                {if $variant.variant_specialprice gt 0}{$price = $variant.variant_specialprice}{/if}
                {if $price gt $max_price}{$max_price = $price}{/if}
                {if $price lt $min_price}{$min_price = $price}{/if}
                <div class="variant-prices" id="variant-price{$variant.variant_id}" style="display:none">
                  {if $variant.variant_specialprice gt '0.00'}
                    <div>$<span>{$variant.variant_price|number_format:2:'.':','}</span></div>
                    <div><b>Special Price:</b> $<span class="selected-price" data-value="{$variant.variant_specialprice}">{$variant.variant_specialprice|number_format:2:'.':','}</span></div>
                  {else}
                    <div>$<span class="selected-price" data-value="{$variant.variant_price}">{$variant.variant_price|number_format:2:'.':','}</span></div>
                  {/if}
                </div>
              {/foreach}
              <div class="variant-prices" id="variant-price">
                  <div>${$min_price|number_format:2:'.':','}{if $min_price neq $max_price} - ${$max_price|number_format:2:'.':','}{/if}</div>
              </div>
            </div>
          </div>
          
          {$prdattrArr = []}
          {foreach $attributes as $attr} 
          <div class="form-group">
            <label for="{urlencode data=$attr.attribute_name}" class="col-sm-12control-label">{$attr.attribute_name}:</label>
            <div class="col-sm-12">
              <select id="{urlencode data=$attr.attribute_name}" name="attr[{$attr.attribute_id}]" class="form-control required">
                <option value="" data-variant="" data-prdattr="">Select one</option>
                {foreach $attr.values as $value}
                  {foreach $variants as $variant}
                    {foreach $variant.productattributes as $prdattr}
                      {if $prdattr.productattr_attr_value_id eq $value.attr_value_id && !$value.attr_value_id|in_array:$prdattrArr[$attr.attribute_id]}{$prdattrArr[$attr.attribute_id][] = $value.attr_value_id}<option value="{$value.attr_value_id}" data-variant="" data-prdattr="">{$value.attr_value_name}</option>{/if}
                    {/foreach}
                  {/foreach}
                {/foreach}
              </select>
            </div>
          </div>
          {/foreach}
         
          <div class="form-group">
  	        <div class="prodprice col-sm-12">
              {foreach $variants as $variant}
                {if $variant.variant_instock eq 1}
                <div class="variant-instocks" id="variant-instock{$variant.variant_id}" style="display:none">
                  <!--<div class="form-group">
                    <label for="address_state_1" class="col-sm-2 control-label">Qty: </label>
                    <div class="col-sm-2">
                      <input type="text" value="1" name="quantity" id="quantity" class="form-control unsigned-int gt-zero" pattern="[0-9]" >
                    </div>
                  </div>-->
                  <div class="form-group">
                    <div class="col-sm-12">
                      <a class="btn btn-blue" onclick="$('#product-form').submit();">Add to Cart <img src="/images/cartblue1.png" alt="" /></a>
                      <div style="display: inline; color: #ff0000" id="error-text"></div>
                    </div>
                  </div>
                </div>
                {else}
                <div class="form-group">
                  <div style="display: inline; color: #ff0000">Out of stock</div>
                </div>
                {/if}
              {/foreach}
            </div>
          </div>
        </form>
        
        {if $product_content1}
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
              <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Description                </a>
              </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body">{$product_content1}</div>
            </div>
          </div>
          {/if} {if $product_content3}
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo">
              <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Specifications</a>
              </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
              <div class="panel-body">{$product_content3}</div>
            </div>
          </div>
          {/if} {if $product_content2}
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingThree">
              <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">Features</a>
              </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
              <div class="panel-body">{$product_content2}</div>
            </div>
          </div>
          {/if} {if $product_content4}
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFour">
              <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">Downloads</a>
              </h4>
            </div>
            <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
              <div class="panel-body">{$product_content4}</div>
            </div>
          </div>
          {/if}



        </div>
      </div>
    </div>
  </div>
</div>

{if $associated_products}

<div id="related">Related products</div>

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
                <meta itemprop="description" content="{$item.product_content1|strip_tags}"> <!--<a href="{if $parenturl neq ''}{$parenturl}/{$item.product_url}{else}/{$item.cache_url}{/if}" itemprop="url">
							<!-- <img itemprop='image' src="{if $item.gallery.0.gallery_link neq ''}{$item.gallery.0.gallery_link}?height=276{else}/images/no-image-available.jpg{/if}" alt="{$item.gallery.0.gallery_alt_tag}" title="{$item.gallery.0.gallery_title}" class="img-responsive"> --> <!--	<img itemprop='image' src="{exist_image image=$item.gallery.0.gallery_link|cat:'?height=276' default='/images/no-image-available.jpg?height=180'}" alt="{$item.gallery.0.gallery_alt_tag}" title="{$item.gallery.0.gallery_title}" class="img-responsive">
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
                            {if $item.product_specialprice > 0.0} <span itemprop="price" class="prodsave">${$item.product_specialprice|number_format:2:'.':','}</span> {else} <span itemprop="price">${$item.product_price|number_format:2:'.':','}</span> {/if}
                          </div>
                        </div>
                      </div>
                      <img src="/images/cart.png" align="Cart" class="cartimg pull-right" />
                    </div>
                  </div>
                </div> {if $item.product_specialprice > 0.0} <img src="/images/sale.png" alt="Sale" id="saleimg" /> {/if}
                <div class="prodhover">
                  <div class="circle">View product</div>
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
{/if} {/block} {block name=tail}
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
