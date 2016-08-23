{block name=body}

 <div id="maincont">
  	<div class="container">
	  	<div class="row">
	  		<div class="col-sm-12" id="checkout">
		  		{if $productsOnCart }
		  			<h3>Checkout</h3>
			  		<div class="hidden-xs">
			  			<span class="bold">Cart review</span> | Personal details | Payment
			  		</div>
			  		<div class="visible-xs">
			  		<span class="bold">Step 1 of 3</span>
			  		</div>

			  		<div class="row headings">
			  			<div class="visible-xs col-xs-3"></div>
			  			<div class="hidden-xs col-sm-6">Product</div>
			  			<div class="col-xs-3 col-sm-2 text-right">Quantity</div>
			  			<div class="col-xs-3 col-sm-2 text-right">Price</div>
			  			<div class="col-xs-3 col-sm-2 text-right">Total</div>
			  		</div>

			  		<form class="form-horizontal" id="shopping-cart-form" accept-charset="UTF-8" action="" method="post">
				  		<div class="row tallbox">
				  			{foreach from=$productsOnCart item=item}
				  			<div id="{$item.cartitem_id}" class="prod-item cartitem">
					  			<div class="col-xs-2 col-sm-1 text-center valgn"><a href="javascript:void(0)" onclick="deleteItem('{$item.cartitem_id}');">X</a></div>
					  			<div class="hidden-xs hidden-sm col-md-1">
					  				<img class="img-responsive" src="{if $item.gallery.0.gallery_link neq ''}{$item.gallery.0.gallery_link}?height=80{else}/images/no-image-available.jpg?height=80{/if}" alt="{$item.gallery.0.gallery_alt_tag}" title="{$item.gallery.0.gallery_title}" />
					  			</div>
					  			<div class="col-xs-10 col-sm-5 bluetext valgn">
					  				<a href="{$item.url}">{$item.cartitem_product_name}</a>
								  	{if $item.attributes }
										<small>
										{foreach from=$item.attributes item=attr}
											- {$attr.cartitem_attr_attribute_name}: {$attr.cartitem_attr_attr_value_name}
										{/foreach}
										</small>
									{/if}
									<span class="text-right">
									</span>
									<br><span class="mini">{$item.cartitem_product_uid}</span>
					  			</div>
					  			<div class="visible-xs col-xs-3"></div>
					  			<div class="col-xs-3 col-sm-2 col-md-1 quant text-right valgn">
						  			<select id="quantity" class="quantity" name="qty[{$item.cartitem_id}]">
						  			{assign var='max' value=150}
						  			{if $max lt $item.cartitem_quantity}
						  				{assign var='max' value=$item.cartitem_quantity}
						  			{/if}
						  			{for $opt=1 to $max}
									    <option value="{$opt}" {if $item.cartitem_quantity eq $opt}selected{/if}>{$opt}</option>
									{/for}
						  			</select>
						  			<div id="qty-discount-{$item.cartitem_id}">{if $item.productqty_modifier.productqty_modifier neq 0}(-{$item.productqty_modifier.productqty_modifier|number_format:2:".":','|rtrim:'0'|rtrim:'.'}%){/if}</div>
					  			</div>
					  			<div class="col-xs-3 col-sm-2 text-right valgn" id="priceunit-{$item.cartitem_id}">${$item.cartitem_product_price|number_format:2:".":","}</div>
					  			<div class="col-xs-3 col-sm-2 text-right valgn" id="subtotal-{$item.cartitem_id}">${$item.cartitem_subtotal|number_format:2:".":","}</div>
				  			</div>
				  			{/foreach}
				  		</div>
				  	</form>
			  		<br />
			  		<div class="row">
				  		<div class="col-sm-12">
				  		  <div class="row tallrow" id="disccode">

						  		 <form class="form-horizontal" id="discount-form" accept-charset="UTF-8" action="/process/cart" method="post">
					  				<input type="hidden" value="applyDiscount" name="action" id="action" />
					  				 <div class="col-xs-12 col-sm-4 col-md-2 text-left">Discount code</div>
					  				<div class="col-xs-7 col-sm-4 col-md-2">
					  					<input class="form-control" type="text" placeholder="ENTER CODE" id="promo" value="{if $post}{$post.discount_code}{else}{$cart.cart_discount_code}{/if}" name="discount_code"/>
                      <div>{if $error}<span class="invalid">{$error}</span>{else}<small><b>{$cart.cart_discount_description}</b></small>{/if}</div>
					  				</div>
						  	   	<div class="col-xs-5 col-sm-4 col-md-2">
					  					<a href="javascript:void(0);" class="btn-blue btn" onclick="$('#discount-form').submit();">Apply</a>
					  				 </div>
					  				 <div class="col-xs-6 col-sm-offset-4 col-md-offset-0 col-sm-5 col-md-4 shopping-label">

					  				 </div>
					  				<div class="col-xs-6 col-sm-3 col-md-2 num text-right" id="discount">
					  					{if $totals.discount gt 0}
					  						<small><b>$-{$totals.discount|number_format:2:".":","}</b></small>
					  					{else}
					  						$0.00
					  					{/if}
					  				</div>
					  			</form>

				  		  </div>
						  <div class="row tallrow">
						  	<div class="col-sm-offset-2 col-md-offset-2 col-sm-5 col-md-5 ">

						  	</div>
						  	<div class="col-xs-5 col-sm-2 col-md-3 shopping-label">Subtotal</div><!-- The following SUBTOTAL value was intentionally changed to TOTAL  -->
							<div class="col-xs-7 col-sm-3 col-md-2 num text-right" id="subtotal" data-value="{$totals.total}">${$totals.total|number_format:2:".":","}</div>
						  </div>

						  <div class="row tallrow">
						  	<div class="col-xs-12 col-sm-4 col-sm-offset-5 col-md-offset-7 col-md-5 shopping-label">
					  		<form class="form-horizontal" id="checkout1-form" accept-charset="UTF-8" action="/process/cart" method="post">
		            <input type="hidden" value="checkout1" name="action" id="action" />
		            <input type="hidden" value="{$selectedShippingFee}" name="shipFee" id="shippingMethod"/>
		            <input type="hidden" value="{$selectedShipping}" name="shipMethod" id="shipMethod"/>
		            <input type="hidden" value="{$postageID}" name="postageID" id="postageID"/>
		            <div class="row form-group">
		              <div class="col-xs-12 col-sm-12">
		                  <label for="postcode-field" class="control-label">Estimate Shipping Cost<a target="_black" title="Shipping & Delivery" href="/delivery-policy">(?)</a> </label>
		              </div>
		              <div class="col-xs-5 col-sm-5">
		                  <input id="postcode-field" name="postcodefield" class="zipcode form-control" type="text" value="{$shippostcode}" placeholder="postcode" required onPaste="updateShipping();" {literal}onblur="if($(this).val().length >= 4){updateShipping();}" onkeydown="if(event.keyCode == 13 || $(this).val().length >= 4){updateShipping();}"{/literal}/>
		              </div>
		              <div class="col-xs-7 col-sm-2 col-sm-offset-5 num text-right" id="shipping-fee">$-unknown-</div>
		            </div>
		            </form>
						  	</div><!-- The following SUBTOTAL value was intentionally changed to TOTAL  -->
						  </div>
					  		<div class="row tots tallrow">
								<div class="col-xs-5 col-sm-2 col-sm-offset-7 col-md-offset-7 col-md-3 shopping-label bold">
									Total
								</div>
								<div class="col-xs-7 col-sm-3 col-md-2 num text-right bold num" id="total"></div>
					  		</div>
			  		 </div>
            </div>
			  		<div class="row">
			  		<div class="col-sm-12 text-right">
							<div class="postcode-invalid text-danger"></div>
			  		</div>
						<div class="col-sm-3 col-sm-offset-9 text-right">
							<button class="btn btn-blue process-cnt" onclick="$('#checkout1-form').submit();">Next</button>
						</div>
			  		</div>
			  		<br /><br />
		  		{else}
					<div>
						<h3>Your shopping cart is empty.</h3>
					</div>
				{/if}
	  		</div>
	  	</div>
  	</div>
  </div>

{/block}

{* Place additional javascript here so that it runs after General JS includes *}
{block name=tail}
<script type="text/javascript">
  $(document).ready(function(){
	  $('#checkout1-form').validate({
       onkeyup: false,
       onclick: false
     });
    calculateTotal();

    if($('#postcode-field').val()){
    	updateShipping();
    }
    
    var keyStop = {
		   8: ":not(input:text, textarea, input:file, input:password)", // stop backspace = back
		   13: "input:text, input:password", // stop enter = submit

		   end: null
		 };
		 $(document).bind("keydown", function(event){
		  var selector = keyStop[event.which];

		  if(selector !== undefined && $(event.target).is(selector)) {
		      event.preventDefault(); //stop event
		  }
		  return true;
		 });
  });
</script>
{/block}

