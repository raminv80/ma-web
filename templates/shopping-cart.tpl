{block name=body}

 <div id="maincont">
  	<div class="container">
	  	<div class="row">
	  	  <div class="col-sm-12" id="breadcrumbs">
          <div class="breadcrumbs">
          <a href="/">Home</a> / Checkout
          </div>
        </div>
	  		<div class="col-sm-12">
		  		{if $productsOnCart }
		  			<h3>Checkout</h3>
			  		<div class="hidden-xs">
			  			<span class="bold">Step 1: Cart review</span> / Step 2: Personal details / Step 3: Payment
			  		</div>
			  		<div class="visible-xs">
			  		<span class="bold">Step 1 of 3</span>
			  		</div>
			  		
			  		<div class="row headings">
			  			<div class="hidden-xs col-sm-1"></div>
			  			<div class="col-xs-3 col-sm-6"></div>
			  			<div class="col-xs-3 col-sm-1 centre">Quantity</div>
			  			<div class="col-xs-3 col-sm-1 col-sm-offset-1 centre">Price</div>
			  			<div class="col-xs-3 col-sm-1 centre">Total</div>		  			
			  		</div>
			  		<form class="form-horizontal" id="shopping-cart-form" accept-charset="UTF-8" action="" method="post">
				  		<div class="row tallbox">
				  			{foreach from=$productsOnCart item=item}
				  			<div id="{$item.cartitem_id}" class="prod-item row cartitem">
					  			<div class="hidden-xs col-sm-1">
					  				<img class="img-responsive" src="{if $item.gallery.0.gallery_link neq ''}{$item.gallery.0.gallery_link}{else}/images/no-image-available.jpg{/if}" alt="{$item.gallery.0.gallery_alt_tag}" title="{$item.gallery.0.gallery_title}" />
					  			</div>
					  			<div class="col-xs-12 col-sm-6 bluetext">
					  				<a href="{$item.url}">{$item.cartitem_product_name}</a>
								  	{if $item.attributes } 
										<small>
										{foreach from=$item.attributes item=attr}
											- {$attr.cartitem_attr_attribute_name}: {$attr.cartitem_attr_attr_value_name} 
										{/foreach}
										</small>
									{/if}
					  			</div>
					  			<div class="col-xs-3 col-sm-2 centre">
						  			<select id="quantity" class="quantity" name="qty[{$item.cartitem_id}]">
						  			{assign var='max' value=150}
						  			{if $max lt $item.cartitem_quantity}
						  				{assign var='max' value=$item.cartitem_quantity}
						  			{/if}  
						  			{for $opt=1 to $max}
									    <option value="{$opt}" {if $item.cartitem_quantity eq $opt}selected{/if}>{$opt}</option>
									{/for}
						  			</select>
						  			<div id="qty-discount-{$item.cartitem_id}">{if $item.productqty_modifier.productqty_modifier neq 0}(-{$item.productqty_modifier.productqty_modifier|number_format:0:".":','}%){/if}</div>
					  			</div>
					  			<div class="col-xs-3 col-sm-1  centre" id="priceunit-{$item.cartitem_id}">${$item.cartitem_product_price|number_format:2:".":","}</div>
					  			<div class="col-xs-3 col-sm-1 centre" id="subtotal-{$item.cartitem_id}">${$item.cartitem_subtotal|number_format:2:".":","}</div>
					  			<div class="col-xs-3 col-sm-1">
                    <a href="javascript:void(0)" onclick="deleteItem('{$item.cartitem_id}');">
                      Delete
                    </a>
                  </div>	
				  			</div>
				  			{/foreach}
				  		</div>
				  	</form>
			  		<br />
			  		<div class="row">
			  		   <div class="col-sm-6">
			  		     <div class="row"><div class="col-xs-12">Have a discount or promotion code?</div></div>
			  		     <div class="row">
				  			<form class="form-horizontal" id="discount-form" accept-charset="UTF-8" action="/process/cart" method="post">
				  				<input type="hidden" value="applyDiscount" name="action" id="action" /> 
					  			<div class="col-sm-6 margintop8"><input class="form-control" type="text" id="promo" value="{if $post}{$post.discount_code}{else}{$cart.cart_discount_code}{/if}" name="discount_code"/></div>
					  			<div class="col-sm-6"><a href="javascript:void(0);" class="grey btn" onclick="$('#discount-form').submit();">Apply</a></div>
				  			</form>
				  			</div>
				  			<div class="row">
				  			{if $error || $totals.discount_error}
	                <div class="col-sm-12 error-txt">{$error} {$totals.discount_error}</div>
	              {else}
	              	<div class="col-sm-12" id="cart_discount_description">{$cart.cart_discount_description}</div>
	              {/if}
	               </div>
				  		</div>
				  		
				  		<div class="col-sm-6">
				  		  <div class="row"><div class="col-xs-4 col-sm-9 text-right">Discount</div>
				  		  <div class="col-xs-8 col-sm-3 num text-right" id="discount">${if $totals.discount gt 0}-{/if}{$totals.discount|number_format:2:".":","}</div></div>
								<div class="row"><div class="col-xs-4 col-sm-9 text-right">Subtotal</div><!-- The following SUBTOTAL value was intentionally changed to TOTAL  -->
								<div class="col-xs-8 col-sm-3 num text-right" id="subtotal" data-value="{$totals.total}">${$totals.total|number_format:2:".":","}</div></div> 		
				  	
					  		<form class="form-horizontal" id="checkout1-form" accept-charset="UTF-8" action="/process/cart" method="post">
		            <input type="hidden" value="checkout1" name="action" id="action" /> 
		            <input type="hidden" value="{$selectedShippingFee}" name="shipFee" id="shippingMethod"/>  
		            <input type="hidden" value="{$selectedShipping}" name="shipMethod" id="shipMethod"/> 
		            <input type="hidden" value="{$postageID}" name="postageID" id="postageID"/> 
		            <div class="row form-group">
		              <div class="col-xs-12 col-sm-7 col-sm-offset-5">
		                  <label for="postcode-field" class="control-label">Estimate Shipping Cost<a target="_black" title="Shipping & Delivery" href="/shipping-and-delivery">(?)</a> </label>
		              </div>
		              <div class="col-xs-5 col-sm-5 col-sm-offset-5">
		                  <input id="postcode-field" name="postcodefield" class="zipcode form-control" type="text" value="{$selectedShippingPostcode}" placeholder="postcode" required onPaste="updateShipping();" {literal}onblur="if($(this).val().length >= 4){updateShipping();}" onkeydown="if(event.keyCode == 13 || $(this).val().length >= 4){updateShipping();}"{/literal}/>
		              </div>
		              <div class="col-xs-7 col-sm-2 num text-right" id="shipping-fee">$-unknown-</div>          
		            </div>
		            </form>
					  		<div class="row tots">
								<div class="col-xs-4 col-sm-2 col-sm-offset-7 bold">
									Total
								</div>
								<div class="col-xs-8 col-sm-3 bold num text-right" id="total"></div>		  		
					  		</div>
			  		 </div>
            </div>
			  		<br /><br />
			  		<div class="row">
						<div class="col-sm-2 col-sm-offset-10 text-right"><a href="javascript:void(0);" class="red btn" onclick="$('#checkout1-form').submit();">Next</a></div>		  		
			  		</div>
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

