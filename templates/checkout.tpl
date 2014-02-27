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
	{if $productsOnCart }
		{if $error}
		<div class="row" style="margin:20px; color:#ff0000">{$error}</div>
        {/if}
        <div class="col-sm-8">	
			<div class="row" style="margin-top: 20px; background-color: rgb(238, 238, 238);">
				<div style="display:inline;" class="col-md-6">Product</div>
				<div style="display:inline; text-align:right;" class="col-md-2">Unit Price</div>
				<div style="display:inline;" class="col-md-1">Qty</div>
				<div style="display:inline; text-align:right;" class="col-md-2">Subtotal</div>
				<div style="display:inline;" class="col-md-1"></div>
			</div>
			
				<div class="row" style="margin-top: 20px; margin-bottom: 10px;" id="products-container"> 
					{foreach from=$productsOnCart item=item}
					<div class="row" style="margin-top: 10px;" id="{$item.cartitem_id}">
						<div style="display:inline;" class="col-md-6">{$item.cartitem_product_name}
						{if $item.attributes } 
							<small>
							{foreach from=$item.attributes item=attr}
								- {$attr.cartitem_attr_attribute_name}: {$attr.cartitem_attr_attr_value_name} 
							{/foreach}
							</small>
						{/if}
						</div>
						<div style="display:inline; text-align:right;" class="col-md-2">${$item.cartitem_product_price}</div>
						<div style="display:inline; text-align:right;" class="col-md-1">{$item.cartitem_quantity}</div>
						
						<div style="display:inline; text-align:right;" class="col-md-2">${$item.cartitem_subtotal}</div>
						
					</div>		
					{/foreach} 
				</div>
				
			
			<div class="row" style="margin-top: 10px;">
				<div style="display:inline; text-align:right;" class="col-md-10">Subtotal</div>
				<div style="display:inline; text-align:right;" class="col-md-2" id="subtotal">${$cart.cart_subtotal}</div>
			</div>
			<div class="row" style="margin-top: 10px;">
				<div style="display:inline; text-align:right;" class="col-md-10">Discount {if $cart.cart_discount_code}<small>[Code: {$cart.cart_discount_code}]</small>{/if}</div>
				<div style="display:inline; text-align:right;" class="col-md-2" id="discount">-${$cart.cart_discount}</div>
			</div>
			<div class="row" style="margin-top: 10px;">
				<div style="display:inline; text-align:right;" class="col-md-10">Postage & Handling</div>
				<div style="display:inline; text-align:right;" class="col-md-2" id="shipping">${$cart.cart_shipping_fee}</div>
			</div>
			<div class="row" style="margin-top: 10px;">
				<div style="display:inline; text-align:right;" class="col-md-10">Total</div>
				<div style="display:inline; text-align:right;" class="col-md-2" id="total">${$cart.cart_total}</div>
			</div>
		</div>
		<div class="col-sm-8">
			<form class="form-horizontal" id="order-form" role="form" accept-charset="UTF-8" action="/process/cart" method="post">
				
				<div class="row" id="order" style="margin:40px;">
					<input type="hidden" value="placeOrder" name="action"/> 
					<input type="hidden" name="formToken" id="formToken" value="{$token}" />
					
					<div class="form-group">
					    <label for="email" class="col-sm-2 control-label">Email</label>
					    <div class="col-sm-10">
					      	<input type="email" value="{if $post}{$post.email}{else}{if $user.email}{$user.email}{/if}{/if}" class="form-control" id="email" name="email" required>
						</div>
					</div>
					
					<!-- BILLING SECTION - Hidden by default -->
					<div class="row" id="billing-subform">
		                <div class="row">
		                    <h3>Billing Details</h3>
		                </div>
		                <input type="hidden" value="{$user.id}" name="address[1][address_user_id]" id="address_user_id" /> 
		                {if $addresses}
		                <div class="form-group">
						    <label for="previous-address-1" class="col-sm-2 control-label"></label>
						    <div class="col-sm-10">
						      	<select id="previous-address-1" name="previous-address-1" class="form-control" >
									<option value="0">Previously used addresses</option>
									{foreach $addresses as $add }
										<option value="{$add.address_id}" {if $post.previous-address-1 eq $add.address_id}selected="selected"{/if}
												name="{$add.address_name}"
												line1="{$add.address_line1}"
												line2="{$add.address_line2}"
												suburb="{$add.address_suburb}"
												state="{$add.address_state}"
												country="{$add.address_country}"
												postcode="{$add.address_postcode}"
												telephone="{$add.address_telephone}"
												mobile="{$add.address_mobile}"
										>{$add.address_name}, {$add.address_line1} {$add.address_line2}, {$add.address_suburb}, {$add.address_state}, {$add.address_country}, {$add.address_postcode}. {$add.address_telephone}{if $add.address_mobile} / {$add.address_mobile}{/if}</option>
									{/foreach}
								</select>
							</div>
						</div>
						{/if}
						<div class="row" id="billing-subform-content">
							<div class="form-group">
							    <label for="address_name_1" class="col-sm-2 control-label">Name</label>
							    <div class="col-sm-10">
							      	<input type="text" value="{if $post}{$post.address.1.address_name}{/if}" class="form-control biling-req" id="address_name_1" name="address[1][address_name]" required>
								</div>
							</div>
							<div class="form-group">
							    <label for="address_line1_1" class="col-sm-2 control-label">Address</label>
							    <div class="col-sm-10">
							      	<input type="text" value="{if $post}{$post.address.1.address_line1}{/if}" class="form-control biling-req" id="address_line1_1" name="address[1][address_line1]" required>
								</div>
							</div>
							<div class="form-group">
							    <label for="address_line2_1" class="col-sm-2 control-label"></label>
							    <div class="col-sm-10">
							      	<input type="text" value="{if $post}{$post.address.1.address_line2}{/if}" class="form-control" id="address_line2_1" name="address[1][address_line2]">
								</div>
							</div>
							<div class="form-group">
							    <label for="address_suburb_1" class="col-sm-2 control-label">Suburb</label>
							    <div class="col-sm-10">
							      	<input type="text" value="{if $post}{$post.address.1.address_suburb}{/if}" class="form-control biling-req" id="address_suburb_1" name="address[1][address_suburb]" required>
								</div>
							</div>
							<div class="form-group">
							    <label for="address_state_1" class="col-sm-2 control-label">State</label>
							    <div class="col-sm-10">
							      	<select id="address_state_1" name="address[1][address_state]" class="form-control required" >
										<option value="">Select a state</option>
										{foreach $options_state as $value }
											<option value="{$value.postcode_state}" {if $post.address.1.address_state eq $value.postcode_state}selected="selected"{/if}>{$value.postcode_state}</option>
										{/foreach}
									</select>
								</div>
							</div>
							<!-- <div class="form-group">
							    <label for="address_country_1" class="col-sm-2 control-label">Country</label>
							    <div class="col-sm-10">
							      	<select id="address_country_1" name="address[1][address_country]" class="form-control" >
										<option value="Australia">Australia</option>
									</select>
								</div>
							</div> -->
							<input type="hidden" value="Australia" name="address[1][address_country]"/> 
							
							<div class="form-group">
							    <label for="address_postcode_1" class="col-sm-2 control-label">Postcode</label>
							    <div class="col-sm-10">
							      	<input type="text" value="{if $post}{$post.address.1.address_postcode}{/if}" class="form-control" id="address_postcode_1" name="address[1][address_postcode]" required>
								</div>
							</div>
							<div class="form-group">
							    <label for="address_telephone_1" class="col-sm-2 control-label">Phone</label>
							    <div class="col-sm-10">
							      	<input type="text" value="{if $post}{$post.address.1.address_telephone}{/if}" class="form-control" id="address_telephone_1" name="address[1][address_telephone]" >
								</div>
							</div>
							<div class="form-group">
							    <label for="address_mobile_1" class="col-sm-2 control-label">Mobile</label>
							    <div class="col-sm-10">
							      	<input type="text" value="{if $post}{$post.address.1.address_mobile}{/if}" class="form-control biling-req" id="address_mobile_1" name="address[1][address_mobile]" >
								</div>
							</div>
						</div>
						<div class="form-group">
		    				<div class="col-sm-offset-2 col-sm-10">
								<div class="checkbox">
								    <label>
								    	<input type="checkbox" name="same_address" {if $post}{if $post.same_address}checked="checked"{else}{/if}{else}checked="checked"{/if} onclick="sameAddress();"> Shipping same as Billing details
								    </label>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row" id="shipping-subform" {if $post}{if $post.same_address}style="display:none;"{else}{/if}{else}style="display:none;"{/if} > 
					<!-- SHIPPING SECTION - Hidden by default -->
		                <div class="row">
		                    <h3>Shipping Details</h3>
		                </div>
		                <input type="hidden" value="{$user.id}" name="address[2][address_user_id]" id="address_user_id" />
						{if $addresses}
						<div class="form-group">
						    <label for="previous-address-2" class="col-sm-2 control-label"></label>
						    <div class="col-sm-10">
						      	<select id="previous-address-2" name="previous-address-2" class="form-control" >
									<option value="0">Previously used addresses</option>
									{foreach $addresses as $add }
										<option value="{$add.address_id}" {if $post.previous-address-2 eq $add.address_id}selected="selected"{/if}
												name="{$add.address_name}"
												line1="{$add.address_line1}"
												line2="{$add.address_line2}"
												suburb="{$add.address_suburb}"
												state="{$add.address_state}"
												country="{$add.address_country}"
												postcode="{$add.address_postcode}"
												telephone="{$add.address_telephone}"
												mobile="{$add.address_mobile}"
										>{$add.address_name}, {$add.address_line1} {$add.address_line2}, {$add.address_suburb}, {$add.address_state}, {$add.address_country}, {$add.address_postcode}. {$add.address_telephone}{if $add.address_mobile} / {$add.address_mobile}{/if}</option>
									{/foreach}
								</select>
							</div>
						</div>
						{/if}
						<div class="row" id="shipping-subform-content">
							<div class="form-group">
							    <label for="address_name_2" class="col-sm-2 control-label">Name</label>
							    <div class="col-sm-10">
							      	<input type="text" value="{if $post}{$post.address.2.address_name}{/if}" class="form-control shipping-req" id="address_name_2" name="address[2][address_name]" >
								</div>
							</div>
							<div class="form-group">
							    <label for="address_line1_2" class="col-sm-2 control-label">Address</label>
							    <div class="col-sm-10">
							      	<input type="text" value="{if $post}{$post.address.2.address_line1}{/if}" class="form-control shipping-req" id="address_line1_2" name="address[2][address_line1]" >
								</div>
							</div>
							<div class="form-group">
							    <label for="address_line1_2" class="col-sm-2 control-label"></label>
							    <div class="col-sm-10">
							      	<input type="text" value="{if $post}{$post.address.2.address_line2}{/if}" class="form-control" id="address_line2_2" name="address[2][address_line2]">
								</div>
							</div>
							<div class="form-group">
							    <label for="address_suburb_2" class="col-sm-2 control-label">Suburb</label>
							    <div class="col-sm-10">
							      	<input type="text" value="{if $post}{$post.address.2.address_suburb}{/if}" class="form-control shipping-req" id="address_suburb_2" name="address[2][address_suburb]" >
								</div>
							</div>
							<div class="form-group">
							    <label for="address_state_2" class="col-sm-2 control-label">State</label>
							    <div class="col-sm-10">
							      	<select id="address_state_2" name="address[2][address_state]" class="form-control shipping-select-req" >
										<option value="">Select a state</option>
										{foreach $options_state as $value }
											<option value="{$value.postcode_state}" {if $post.address.2.address_state eq $value.postcode_state}selected="selected"{/if}>{$value.postcode_state}</option>
										{/foreach}
									</select>
								</div>
							</div>
							<!-- <div class="form-group">
							    <label for="address_country_2" class="col-sm-2 control-label">Country</label>
							    <div class="col-sm-10">
									<select id="address_country_2" name="address[2][address_country]" class="form-control" >
										<option value="Australia">Australia</option>
									</select>
								</div>
							</div> -->
							<input type="hidden" value="Australia" name="address[2][address_country]"/> 
							
							<div class="form-group">
							    <label for="address_postcode_2" class="col-sm-2 control-label">Postcode</label>
							    <div class="col-sm-10">
							      	<input type="text" value="{if $post}{$post.address.2.address_postcode}{/if}" class="form-control shipping-req" id="address_postcode_2" name="address[2][address_postcode]" pattern="[0-9]">
								</div>
							</div>
							<div class="form-group">
							    <label for="address_telephone_2" class="col-sm-2 control-label">Phone</label>
							    <div class="col-sm-10">
							      	<input type="text" value="{if $post}{$post.address.2.address_telephone}{/if}" class="form-control" id="address_telephone_2" name="address[2][address_telephone]" >
								</div>
							</div>
							<div class="form-group">
							    <label for="address_mobile_2" class="col-sm-2 control-label">Mobile</label>
							    <div class="col-sm-10">
							      	<input type="text" value="{if $post}{$post.address.2.address_mobile}{/if}" class="form-control" id="address_mobile_2" name="address[2][address_mobile]" >
								</div>
							</div>
						</div>
					</div>
				 	
				 	<div class="row" id="payment-subform"> 
					<!-- SHIPPING SECTION - Hidden by default -->
		                <div class="row">
		                    <h3>Payment</h3>
		                </div>
		                <input type="hidden" value="{$cart.cart_id}" name="payment[payment_cart_id]" id="payment_cart_id" />
		                <input type="hidden" value="{$cart.cart_user_id}" name="payment[payment_user_id]" id="payment_user_id" />
		                <input type="hidden" value="{$cart.cart_subtotal}" name="payment[payment_subtotal]" id="payment_cart_id" />
		                <input type="hidden" value="{$cart.cart_shipping_fee}" name="payment[payment_shipping_fee]" id="payment_cart_id" />
		                <input type="hidden" value="{$cart.cart_total}" name="payment[payment_charged_amount]" id="payment_cart_id" />
		                
		                <div class="radio">
						  	<label>
						   		<input type="radio" name="payment[payment_option]" id="payment_option1" value="transfer" checked>
						    	Bank Transfer
						  	</label>
						</div>
						<div class="radio">
						  	<label>
						    	<input type="radio" name="payment[payment_option]" id="payment_option2" value="credit">
						    	Credit Card
						  	</label>
						</div>
						
						<div class="row" id="credit-subform" style="margin:20px; ">
						
							<div class="col-md-8">
								    <div class="col-sm-offset-2" style="margin-bottom:10px;">
								    	<img src="/images/MasterCard.png" alt="MasterCard icon">
								    	<img src="/images/VisaCard.png" alt="VisaCard icon">
								    </div>
								    
								<div class="form-group">
								    <label for="cc-number" class="col-sm-2 control-label">Credit card number</label>
								    <div class="col-sm-10">
								      	<input type="number" value="" class="form-control " autocomplete="off" id="cc-number" name="cc-number" pattern="[0-9]">
									</div>
								</div>
								<div class="form-group">
								    <label for="cc-name" class="col-sm-2 control-label">Name on credit card</label>
								    <div class="col-sm-10">
								      	<input type="text" value="" class="form-control " autocomplete="off" id="cc-name" name="cc-name" >
									</div>
								</div>
								<div class="form-group">
								    <label for="cc-name" class="col-sm-2 control-label">Expiry date</label>
								    <div class="col-sm-10">
										<select name="cc-month" >
											<option selected="selected" value="">Month</option>
											<option value="01">01 - January</option>
											<option value="02">02 - February</option>
											<option value="03">03 - March</option>
											<option value="04">04 - April</option>
											<option value="05">05 - May</option>
											<option value="06">06 - June</option>
											<option value="07">07 - July</option>
											<option value="08">08 - August</option>
											<option value="09">09 - September</option>
											<option value="10">10 - October</option>
											<option value="11">11 - November</option>
											<option value="12">12 - December</option>
										</select>
										<select name="cc-year" >
											{assign var=thisyear value=$smarty.now|date_format:"%Y"}
											{assign var=tenyears value=$thisyear+10}
												<option selected="selected" value="">Year</option>
											{for $year=$thisyear to $tenyears}
												<option value="{$year}">{$year}</option>
											{/for}
										</select>
									</div>
								</div>
								<div class="form-group">
								    <label for="cc-cvc" class="col-sm-2 control-label">CVC</label>
								    <div class="col-sm-10">
								      	<input type="text" value="" class="form-control " autocomplete="off" id="cc-cvc" name="cc-cvc" pattern="[0-9]">
									</div>
								</div>
							</div>
						</div>
	
	
	
					</div>
				 	
				 	<div class="form-group">
				    	<div class="col-sm-offset-2 col-sm-10">
				      		<button onclick="$('#order-form').submit();" class="btn btn-primary">Place Order</button>
				    	</div>
				  	</div>
					
				</div>
			</form>
		</div>
	{else}
		<div class="row" style="margin: 60px; background-color: rgb(238, 238, 238);">
			<div style="display:inline;">Your shopping cart is empty.</div>
		</div>
	{/if}
	</div>
	
	<script type="text/javascript">
	

		$(document).ready(function(){

			$('#order-form').validate();

			$('#cc-number').rules("add", {
			      creditcard: true,
			      messages: {
			        equalTo: "Number not valid."
			      }
			 });

			$('#credit-subform').hide();
			
			$('input:radio').change(function() {
		        if (this.value == 'credit') {
		        	$('#credit-subform').show();
		        }
		        else if (this.value == 'transfer') {
		            $('#credit-subform').hide();
		        }
		    });

			$('#previous-address-1').change(function() {
				if ($(this).val() > 0) {
						$('#address_name_1').val($('option:selected', this).attr('name'));
						$('#address_line1_1').val($('option:selected', this).attr('line1'));
						$('#address_line2_1').val($('option:selected', this).attr('line2'));
						$('#address_suburb_1').val($('option:selected', this).attr('suburb'));
						$('#address_state_1').val($('option:selected', this).attr('state'));
						$('#address_country_1').val($('option:selected', this).attr('country'));
						$('#address_postcode_1').val($('option:selected', this).attr('postcode'));
						$('#address_telephone_1').val($('option:selected', this).attr('telephone'));
						$('#address_mobile_1').val($('option:selected', this).attr('mobile'));					
				}
			});

			$('#previous-address-2').change(function() {
				if ($(this).val() > 0) {
						$('#address_name_2').val($('option:selected', this).attr('name'));
						$('#address_line1_2').val($('option:selected', this).attr('line1'));
						$('#address_line2_2').val($('option:selected', this).attr('line2'));
						$('#address_suburb_2').val($('option:selected', this).attr('suburb'));
						$('#address_state_2').val($('option:selected', this).attr('state'));
						$('#address_country_2').val($('option:selected', this).attr('country'));
						$('#address_postcode_2').val($('option:selected', this).attr('postcode'));
						$('#address_telephone_2').val($('option:selected', this).attr('telephone'));
						$('#address_mobile_2').val($('option:selected', this).attr('mobile'));					
				}
			});

			/* $('#previous-address-2').change(function() {
				if ($(this).val() > 0) {
					$('.shipping-req').removeAttr('required');
					$('#shipping-subform-content').hide();
				} else {
					$('.shipping-req').attr('required', 'required');
					$('#shipping-subform-content').show();
				}
			}); */
		    
		})
		
		function sameAddress() {
			$('#shipping-subform').toggle();
			if ($('#shipping-subform:visible').length > 0) {
				$('.shipping-req').attr('required', 'required');
				$('.shipping-select-req').addClass('required');
			} else {
				$('.shipping-req').removeAttr('required');
				$('.shipping-select-req').removeClass('required');
			}
		}

		
	</script>

{/block}
