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
	{if $validation}
		{foreach $validation as $val_msg}
			<div class="alert alert-danger fade in">
				<button class="close" aria-hidden="true" data-dismiss="alert" type="button">&times;</button>
				<strong>{$val_msg}</strong>
			</div>
		{/foreach}
	{/if}
	{if $productsOnCart }
		{if $error}
		<div class="row error-msg">{$error}</div>
        {/if}
        <div class="col-sm-8">	
			<div class="row cart-table-header">
				<div class="col-md-6">Item</div>
				<div class="col-md-2 text-right">Unit Price</div>
				<div class="col-md-1">Qty</div>
				<div class="col-md-2 text-right">Subtotal</div>
				<div class="col-md-1"></div>
			</div>
			
			<div class="row" id="products-container"> 
				{foreach from=$productsOnCart item=item}
				<div class="row product-item" id="{$item.cartitem_id}">
					<div class="col-md-6">{if $item.cartitem_product_gst eq '0'}*{/if} {$item.cartitem_product_name}
					{if $item.attributes } 
						<small>
						{foreach from=$item.attributes item=attr}
							- {$attr.cartitem_attr_attribute_name}: {$attr.cartitem_attr_attr_value_name} 
						{/foreach}
						</small>
					{/if}
					</div>
					<div class="col-md-2 text-right">${$item.cartitem_product_price|number_format:2:".":","}</div>
					<div class="col-md-1 text-right">{$item.cartitem_quantity}</div>
					<div class="col-md-2 text-right">${$item.cartitem_subtotal|number_format:2:".":","}</div>
				</div>		
				{/foreach} 
			</div>
				
			<div class="row totals">
				<div class="col-md-9 text-right">Subtotal</div>
				<div class="col-md-2 text-right" id="subtotal">${$totals.subtotal|number_format:2:".":","}</div>
			</div>
			<div class="row totals">
				<div class="col-md-9 text-right">Discount {if $cart.cart_discount_code}<small>[Code: {$cart.cart_discount_code}]</small>{/if}</div>
				<div class="col-md-2 text-right" id="discount">-${$totals.discount|number_format:2:".":","}</div>
			</div>
			<div class="row totals shipping-fee">
				<div class="col-md-9 text-right">Postage & Handling</div>
				<div class="col-md-2 text-right" id="shipping-fee-value">$0.00</div>
			</div>
			<div class="row totals">
				<div class="col-md-9 text-right gst">Incl. GST</div> {assign var=gst value=$totals.GST_Taxable/10}
				<div class="col-md-2 text-right gst" id="gst" amount="{$totals.GST_Taxable}">(${$gst|number_format:2:".":","})</div>
			</div>
			<div class="row totals">
				<div class="col-md-9 text-right"><b>Total</b></div>
				<div class="col-md-2 text-right total-amount" id="total" amount="{$totals.total}">${$totals.total|number_format:2:".":","}</div>
			</div>
			{if $totals.GST_Taxable neq $totals.total}
				<div class="row gst">(*) GST Free item.</div>
			{/if}
		</div>
		<div class="col-sm-8 checkout-collapse">

		<div class="panel-group" id="accordion">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="panel-title">Billing and Shipping Address</div>
				</div>
				<div id="collapseOne" class="panel-collapse collapse in">
					<div class="panel-body">

						<div class="col-sm-offset-1 col-sm-8" id="step1">
							<form class="form-horizontal" id="step1-form" role="form" accept-charset="UTF-8" action="" method="post">
								<input type="hidden" value="getShippingFees" name="action" /> <input type="hidden" name="formToken" id="formToken" value="{$token}" />

								<!-- BILLING SECTION - Hidden by default -->
								<div class="row" id="billing-subform">
									<div class="row">
										<h3>Billing Details</h3>
									</div>
									{if $addresses}
									<div class="form-group">
										<label for="previous-address-1" class="col-sm-3 control-label"></label>
										<div class="col-sm-9">
											<select id="previous-address-1" name="previous-address-1" class="form-control">
												<option value="0">Previously used addresses</option> {foreach $addresses as $add }
												<option value="{$add.address_id}" {if $post.previous-address-1 eq $add.address_id}selected="selected" {/if}
												name="{$add.address_name}" line1="{$add.address_line1}" line2="{$add.address_line2}" suburb="{$add.address_suburb}" state="{$add.address_state}"
													country="{$add.address_country}" postcode="{$add.address_postcode}" telephone="{$add.address_telephone}" mobile="{$add.address_mobile}">{$add.address_name}, {$add.address_line1} {$add.address_line2}, {$add.address_suburb}, {$add.address_state}, {$add.address_country},
													{$add.address_postcode}. {$add.address_telephone}{if $add.address_mobile} / {$add.address_mobile}{/if}</option> {/foreach}
											</select>
										</div>
									</div>
									{/if}
									<div class="row" id="billing-subform-content">
										<div class="form-group">
											<label for="address_name_1" class="col-sm-3 control-label">Name *</label>
											<div class="col-sm-9">
												<input type="text" value="{if $address}{$address.B.address_name}{else}{if $user.gname}{$user.gname}{$user.surname}{/if}{/if}" class="form-control biling-req" id="address_name_1" name="address[B][address_name]" required>
											</div>
										</div>
										<div class="form-group">
											<label for="email" class="col-sm-3 control-label">Email *</label>
											<div class="col-sm-9">
												<input type="email" value="{if $address}{$address.B.email}{else}{if $user.email}{$user.email}{/if}{/if}" class="form-control" id="email" name="address[B][email]" required>
											</div>
										</div>
										<div class="form-group">
											<label for="address_line1_1" class="col-sm-3 control-label">Address *</label>
											<div class="col-sm-9">
												<input type="text" value="{if $address}{$address.B.address_line1}{/if}" class="form-control biling-req" id="address_line1_1" name="address[B][address_line1]" required>
											</div>
										</div>
										<div class="form-group">
											<label for="address_line2_1" class="col-sm-3 control-label"></label>
											<div class="col-sm-9">
												<input type="text" value="{if $address}{$address.B.address_line2}{/if}" class="form-control" id="address_line2_1" name="address[B][address_line2]">
											</div>
										</div>
										<div class="form-group">
											<label for="address_suburb_1" class="col-sm-3 control-label">Suburb *</label>
											<div class="col-sm-9">
												<input type="text" value="{if $address}{$address.B.address_suburb}{/if}" class="form-control biling-req" id="address_suburb_1" name="address[B][address_suburb]" required>
											</div>
										</div>
										<div class="form-group">
											<label for="address_state_1" class="col-sm-3 control-label">State *</label>
											<div class="col-sm-9">
												<select id="address_state_1" name="address[B][address_state]" class="form-control required">
													<option value="">Select a state</option> {foreach $options_state as $value }
													<option value="{$value.postcode_state}" {if $address.B.address_state eq $value.postcode_state}selected="selected"{/if}>{$value.postcode_state}</option> {/foreach}
												</select>
											</div>
										</div>
										<!-- <div class="form-group">
							    <label for="address_country_1" class="col-sm-3 control-label">Country</label>
							    <div class="col-sm-9">
							      	<select id="address_country_1" name="address[B][address_country]" class="form-control" >
										<option value="Australia">Australia</option>
									</select>
								</div>
							</div> -->
										<input type="hidden" value="Australia" name="address[B][address_country]" />

										<div class="form-group">
											<label for="address_postcode_1" class="col-sm-3 control-label">Postcode *</label>
											<div class="col-sm-9">
												<input type="text" value="{if $address}{$address.B.address_postcode}{/if}" class="form-control" id="address_postcode_1" name="address[B][address_postcode]" required>
											</div>
										</div>
										<div class="form-group">
											<label for="address_telephone_1" class="col-sm-3 control-label">Phone</label>
											<div class="col-sm-9">
												<input type="text" value="{if $address}{$address.B.address_telephone}{/if}" class="form-control" id="address_telephone_1" name="address[B][address_telephone]">
											</div>
										</div>
										<div class="form-group">
											<label for="address_mobile_1" class="col-sm-3 control-label">Mobile</label>
											<div class="col-sm-9">
												<input type="text" value="{if $address}{$address.B.address_mobile}{/if}" class="form-control biling-req" id="address_mobile_1" name="address[B][address_mobile]">
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-2 col-sm-10">
											<div class="checkbox">
												<label> <input type="checkbox" name="address[same_address]" {if $address}{if $address.same_address}checked="checked" {else}{/if}{else}checked="checked" {/if} onclick="sameAddress();"> Shipping same as Billing details
												</label>
											</div>
										</div>
									</div>
								</div>

								<div class="row" id="shipping-subform" {if $address}{if $address.same_address}style="display: none;" {else}{/if}{else}style="display:none;"{/if} >
									<!-- SHIPPING SECTION - Hidden by default -->
									<div class="row">
										<h3>Shipping Details</h3>
									</div>
									{if $addresses}
									<div class="form-group">
										<label for="previous-address-2" class="col-sm-3 control-label"></label>
										<div class="col-sm-9">
											<select id="previous-address-2" name="previous-address-2" class="form-control">
												<option value="0">Previously used addresses</option> {foreach $addresses as $add }
												<option value="{$add.address_id}" {if $post.previous-address-2 eq $add.address_id}selected="selected" {/if}
												name="{$add.address_name}" line1="{$add.address_line1}" line2="{$add.address_line2}" suburb="{$add.address_suburb}" state="{$add.address_state}"
													country="{$add.address_country}" postcode="{$add.address_postcode}" telephone="{$add.address_telephone}" mobile="{$add.address_mobile}">{$add.address_name}, {$add.address_line1} {$add.address_line2}, {$add.address_suburb}, {$add.address_state}, {$add.address_country},
													{$add.address_postcode}. {$add.address_telephone}{if $add.address_mobile} / {$add.address_mobile}{/if}</option> {/foreach}
											</select>
										</div>
									</div>
									{/if}

									<div class="row" id="shipping-subform-content">
										<div class="form-group">
											<label for="address_name_2" class="col-sm-3 control-label">Name *</label>
											<div class="col-sm-9">
												<input type="text" value="{if $address}{$address.S.address_name}{/if}" class="form-control shipping-req" id="address_name_2" name="address[S][address_name]">
											</div>
										</div>
										<div class="form-group">
											<label for="address_line1_2" class="col-sm-3 control-label">Address *</label>
											<div class="col-sm-9">
												<input type="text" value="{if $address}{$address.S.address_line1}{/if}" class="form-control shipping-req" id="address_line1_2" name="address[S][address_line1]">
											</div>
										</div>
										<div class="form-group">
											<label for="address_line1_2" class="col-sm-3 control-label"></label>
											<div class="col-sm-9">
												<input type="text" value="{if $address}{$address.S.address_line2}{/if}" class="form-control" id="address_line2_2" name="address[S][address_line2]">
											</div>
										</div>
										<div class="form-group">
											<label for="address_suburb_2" class="col-sm-3 control-label">Suburb *</label>
											<div class="col-sm-9">
												<input type="text" value="{if $address}{$address.S.address_suburb}{/if}" class="form-control shipping-req" id="address_suburb_2" name="address[S][address_suburb]">
											</div>
										</div>
										<div class="form-group">
											<label for="address_state_2" class="col-sm-3 control-label">State *</label>
											<div class="col-sm-9">
												<select id="address_state_2" name="address[S][address_state]" class="form-control shipping-select-req">
													<option value="">Select a state</option> {foreach $options_state as $value }
													<option value="{$value.postcode_state}" {if $address.S.address_state eq $value.postcode_state}selected="selected"{/if}>{$value.postcode_state}</option> {/foreach}
												</select>
											</div>
										</div>
										<!-- <div class="form-group">
							    <label for="address_country_2" class="col-sm-3 control-label">Country</label>
							    <div class="col-sm-9">
									<select id="address_country_2" name="address[S][address_country]" class="form-control" >
										<option value="Australia">Australia</option>
									</select>
								</div>
							</div> -->
										<input type="hidden" value="Australia" name="address[S][address_country]" />

										<div class="form-group">
											<label for="address_postcode_2" class="col-sm-3 control-label">Postcode *</label>
											<div class="col-sm-9">
												<input type="text" value="{if $address}{$address.S.address_postcode}{/if}" class="form-control shipping-req" id="address_postcode_2" name="address[S][address_postcode]" pattern="[0-9]">
											</div>
										</div>
										<div class="form-group">
											<label for="address_telephone_2" class="col-sm-3 control-label">Phone</label>
											<div class="col-sm-9">
												<input type="text" value="{if $address}{$address.S.address_telephone}{/if}" class="form-control" id="address_telephone_2" name="address[S][address_telephone]">
											</div>
										</div>
										<div class="form-group">
											<label for="address_mobile_2" class="col-sm-3 control-label">Mobile</label>
											<div class="col-sm-9">
												<input type="text" value="{if $address}{$address.S.address_mobile}{/if}" class="form-control" id="address_mobile_2" name="address[S][address_mobile]">
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="pull-right">
										<button type="submit" class="btn btn-primary">Next</button>
									</div>
								</div>
							</form>
						</div>

						<div id="review-step1" style="display:none;">
							<div class="row">
								<div class="col-sm-offset-2 col-sm-5" id="billing-address-step2"></div>
								<div class="col-sm-5" id="shipping-address-step2"></div>
							</div>
							<div class="form-group">
								<div class="pull-right">
									<button onclick="$('#step1').show();$('#collapseTwo').collapse('hide');$('#review-step1').hide();scrolltodiv('#step1');" class="btn btn-warning">Edit</button>
								</div>
							</div>
						</div>


					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="panel-title">Shipping and Payment Methods</div>
				</div>
				<div id="collapseTwo" class="panel-collapse collapse">
					<div class="panel-body">


						<div class="col-sm-offset-1 col-sm-11" id="step2">


							<form class="form-horizontal" id="step2-form" role="form" accept-charset="UTF-8" action="/process/cart" method="post">
								<input type="hidden" value="placeOrder" name="action" /> <input type="hidden" name="formToken" id="formToken" value="{$token}" />
								<div class="row" id="shipping-method-subform">
									<div class="row">
										<h3>Shipping Method</h3>
									</div>
									<div class="form-group col-sm-8">
										<div id="shipping_methods">
										</div>
									</div>
									<div class="col-sm-4">
										<div class="text-right"><b>Total Amount</b></div>
										<div class="text-right total-amount">${$totals.total|number_format:2:".":","}</div>
									</div>
								</div>

								<div class="row" id="payment-subform">
									<!-- PAYMENT SECTION - Hidden by default -->
									<div class="row">
										<h3>Payment</h3>
									</div>
									<div class="radio">
										<label> <input type="radio" class="pay_opt" name="payment[payment_option]" id="payment_option1" value="transfer" checked> Bank Transfer
										</label>
									</div>
									<div class="radio">
										<label> <input type="radio" class="pay_opt" name="payment[payment_option]" id="payment_option2" value="credit"> Credit Card
										</label>
									</div>

									<div class="row" id="credit-subform" style="margin: 20px;">

										<div class="row">
											<div class="col-sm-offset-3" style="margin-bottom: 10px;">
												<img src="/images/MasterCard.png" alt="MasterCard icon"> <img src="/images/VisaCard.png" alt="VisaCard icon">
											</div>

											<div class="form-group">
												<label for="cc-number" class="col-sm-3 control-label">Credit card number</label>
												<div class="col-sm-5">
													<input type="number" value="" class="form-control " autocomplete="off" id="cc-number" name="cc-number" pattern="[0-9]">
												</div>
											</div>
											<div class="form-group">
												<label for="cc-name" class="col-sm-3 control-label">Name on credit card</label>
												<div class="col-sm-5">
													<input type="text" value="" class="form-control " autocomplete="off" id="cc-name" name="cc-name">
												</div>
											</div>
											<div class="form-group">
												<label for="cc-name" class="col-sm-3 control-label">Expiry date</label>
												<div class="col-sm-9">
													<select name="cc-month">
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
													</select> <select name="cc-year"> {assign var=thisyear value=$smarty.now|date_format:"%Y"} {assign var=tenyears value=$thisyear+10}
														<option selected="selected" value="">Year</option> {for $year=$thisyear to $tenyears}
														<option value="{$year}">{$year}</option> {/for}
													</select>
												</div>
											</div>
											<div class="form-group">
												<label for="cc-cvc" class="col-sm-3 control-label">CVC</label>
												<div class="col-sm-2">
													<input type="text" value="" class="form-control " autocomplete="off" id="cc-cvc" name="cc-cvc" pattern="[0-9]">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="pull-right">
										<button type="submit" class="btn btn-primary">Place Order</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
					{else}
					<div class="row" style="margin: 60px; background-color: rgb(238, 238, 238);">
						<div style="display: inline;">Your shopping cart is empty.</div>
					</div>
					{/if}
				</div>

				<script type="text/javascript">
					$(document).ready(
							function() {

								$('#step1-form').validate();
								$('#step2-form').validate();

								$('#cc-number').rules("add", {
									creditcard : true,
									messages : {
										equalTo : "Number not valid."
									}
								});

								$('#credit-subform').hide();

								$('.pay_opt').change(function() {
									if (this.value == 'credit') {
										$('#credit-subform').show();
									} else if (this.value == 'transfer') {
										$('#credit-subform').hide();
									}
								});

								$('#previous-address-1').change(
										function() {
											if ($(this).val() > 0) {
												$('#address_name_1').val(
														$('option:selected',
																this).attr(
																'name'));
												$('#address_line1_1').val(
														$('option:selected',
																this).attr(
																'line1'));
												$('#address_line2_1').val(
														$('option:selected',
																this).attr(
																'line2'));
												$('#address_suburb_1').val(
														$('option:selected',
																this).attr(
																'suburb'));
												$('#address_state_1').val(
														$('option:selected',
																this).attr(
																'state'));
												$('#address_country_1').val(
														$('option:selected',
																this).attr(
																'country'));
												$('#address_postcode_1').val(
														$('option:selected',
																this).attr(
																'postcode'));
												$('#address_telephone_1').val(
														$('option:selected',
																this).attr(
																'telephone'));
												$('#address_mobile_1').val(
														$('option:selected',
																this).attr(
																'mobile'));
											}
										});

								$('#previous-address-2').change(
										function() {
											if ($(this).val() > 0) {
												$('#address_name_2').val(
														$('option:selected',
																this).attr(
																'name'));
												$('#address_line1_2').val(
														$('option:selected',
																this).attr(
																'line1'));
												$('#address_line2_2').val(
														$('option:selected',
																this).attr(
																'line2'));
												$('#address_suburb_2').val(
														$('option:selected',
																this).attr(
																'suburb'));
												$('#address_state_2').val(
														$('option:selected',
																this).attr(
																'state'));
												$('#address_country_2').val(
														$('option:selected',
																this).attr(
																'country'));
												$('#address_postcode_2').val(
														$('option:selected',
																this).attr(
																'postcode'));
												$('#address_telephone_2').val(
														$('option:selected',
																this).attr(
																'telephone'));
												$('#address_mobile_2').val(
														$('option:selected',
																this).attr(
																'mobile'));
											}
										});

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