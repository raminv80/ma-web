{block name=body}
<div id="carttop">
	<div class="container">
		<div class="checkout2">
		<div class="row hidden-xs">
			<div class="col-xs-4 col-sm-4 text-center">
				<span class="bold">1. Cart</span>
			</div>
			<div class="col-xs-4 col-sm-4 text-center">
				<span class="red">2. Delivery</span>
			</div>
			<div class="col-xs-4 col-sm-4 text-center">
				3. Payment
			</div>
		</div>
		<div class="row visible-xs">
			<div class="col-xs-4 col-sm-4 text-center">
				<span class="bold">Step 1</span>
			</div>
			<div class="col-xs-4 col-sm-4 text-center">
				<span class="red">Step 2</span>
			</div>
			<div class="col-xs-4 col-sm-4 text-center">
				Step 3
			</div>
		</div>
		</div>
		<div class="checkout3" style="display: none;">
		<div class="row hidden-xs">
			<div class="col-xs-4 col-sm-4 text-center">
				<span class="bold">1. Cart</span>
			</div>
			<div class="col-xs-4 col-sm-4 text-center">
				<span class="bold">2. Delivery</span>
			</div>
			<div class="col-xs-4 col-sm-4 text-center">
				<span class="red">3. Payment</span>
			</div>
		</div>
		<div class="row visible-xs">
			<div class="col-xs-4 col-sm-4 text-center">
				<span class="bold">Step 1</span>
			</div>
			<div class="col-xs-4 col-sm-4 text-center">
				<span class="bold">Step 2</span>
			</div>
			<div class="col-xs-4 col-sm-4 text-center">
				<span class="red">Step 3</span>
			</div>
		</div>
		</div>
	</div>
</div>

<div id="maincart">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-10 col-md-offset-1 text-center" id="checkout">
        <h1>Delivery</h1>
      </div>
    </div>
  </div>
</div>

<div id="carttext">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-10 col-md-offset-1 text-center">
        {$listing_content1}
      </div>
    </div>
  </div>
</div>
 <div id="checkoutcont">
    <div class="container">
      <div class="row">
        <div class="col-sm-12" id="checkout">

          <div class="row tallbox">
	        <div class="col-sm-12 text-center">
		        <h3>Your cart</h3>
	        </div>
          </div>
		  <div class="row headings">
          	<div class="col-sm-5 col-md-6">Items</div>
		  	<div class="hidden-xs col-xs-3 col-sm-2 text-center">Price</div>
		  	<div class="hidden-xs col-xs-3 col-sm-2 col-md-1 text-center">Quantity</div>
		  	<div class="hidden-xs col-xs-3 col-sm-2 text-center">Total</div>
		  </div>
          <div class="row tallbox">
            {foreach from=$productsOnCart item=item}
            <div id="{$item.cartitem_id}" class="prod-item cartitem">
              <div class="col-xs-3 col-sm-2 col-md-3 text-center">
                <img class="img-responsive" src="{if $item.gallery.0.gallery_link neq ''}{$item.gallery.0.gallery_link}?width=150&height=150&crop=1{else}/images/no-image-available.jpg?width=150&height=150&crop=1{/if}" alt="{$item.gallery.0.gallery_alt_tag}" title="{$item.gallery.0.gallery_title}" />
              </div>
              <div class="col-xs-9 col-sm-3 bluetext valgn">
                <a href="/{$item.product_url}">{$item.cartitem_product_name}</a> {if $item.attributes } {foreach from=$item.attributes item=attr}
                <div class="attributes">{$attr.cartitem_attr_attribute_name}: {$attr.cartitem_attr_attr_value_name}</div>
                {if $attr.cartitem_attr_attr_value_additional} <a class="eng" href="javascript:void(0)" onclick="if($('.addattr{$attr.cartitem_attr_id}').is(':visible')){ $('.addattr{$attr.cartitem_attr_id}').hide('slow'); $(this).html('Show engraving +'); }else{ $('.addattr{$attr.cartitem_attr_id}').show('slow'); $(this).html('Hide engraving  -'); }">Show engraving +</a> {foreach $attr.cartitem_attr_attr_value_additional|json_decode as $k => $v}
                <div class="additional-attributes addattr{$attr.cartitem_attr_id}" style="display: none;">Line {$k}: {$v}</div>
                {/foreach} {/if} {/foreach} {/if} <span class="text-right"> </span> <br>
                <span class="mini">{$item.cartitem_product_uid}</span>
              </div>
              <div class="visible-xs col-xs-3"></div>
              <div class="hidden-xs col-xs-9 col-sm-2 text-center valgn" id="priceunit-{$item.cartitem_id}">${$item.cartitem_product_price|number_format:2:".":","}</div>
              <div class="col-xs-9 col-xs-offset-3 col-sm-2 col-sm-offset-0 col-md-1 quant text-center valgn mobl">
			  	{$item.cartitem_quantity}
              </div>
              <div class="col-xs-6 col-xs-offset-3 col-sm-2 col-sm-offset-0 text-center valgn mobl" id="subtotal-{$item.cartitem_id}">${$item.cartitem_subtotal|number_format:2:".":","}</div>
            </div>
            {/foreach}
          </div>
			  <div class="row totcost">
				<div class="col-sm-12">
		            <div class="row tallrow">
		              <div class="col-xs-5 col-sm-8 col-md-10 shopping-label text-right mobl">Subtotal</div>
		              <!-- The following SUBTOTAL value was intentionally changed to TOTAL  -->
		              <div class="col-xs-7 col-sm-4 col-md-2 num text-left mobr" id="subtotal" data-value="{$totals.total}">${$totals.total|number_format:2:".":","}</div>
		            </div>

		            {if $totals.discount gt 0}
		            <div class="row tallrow">
		              <div class="col-xs-5 col-sm-8 col-md-10 shopping-label text-right mobl">Discount</div>
		              <div class="col-xs-7 col-sm-4 col-md-2 num text-left mobr" id="discount" data-value="{$totals.total}">{if $totals.discount gt 0} <small><b>$-{$totals.discount|number_format:2:".":","}</b></small> {else} $0.00 {/if}</div>
		            </div>
					{/if}

		            <!-- SHIPPING -->
		            <div class="row tallrow">
		              {foreach $shippingMethods as $k => $v}
		                <input type="hidden" value="{$v}" name="shipMethod" id="shippingMethod" />
		                <div class="col-xs-5 col-sm-8 col-md-10 shopping-label text-right  mobl">{$k}  <img src="/images/question-mark.png" alt="What is this?" title="What is this?" data-toggle="tooltip" data-placement="top" /> :</div>
		                <div class="col-xs-7 col-sm-4 col-md-2 num text-left mobr">${$v|number_format:2:".":","}</div>
		              {/foreach}
		            </div>

		            <div class="row tallrow">
		              <div class="col-xs-5 col-sm-8 col-md-10 shopping-label text-right  mobl">GST inc. in total<br /><span class="subj">(*Subject to GST)</span></div>
		              <div class="col-xs-7 col-sm-4 col-md-2 num text-left mobr">$2.38</div>
		            </div>

		            <div class="row tots tallrow"><!-- The following SUBTOTAL value was intentionally changed to TOTAL  -->
		              <div class="col-xs-5 col-sm-8 col-md-10 shopping-label bold text-right mobl" id="totall">Total</div>
		              <div class="col-xs-7 col-sm-4 col-md-2 bold num text-left mobr" id="total">{assign var='newTotal' value=$selectedShippingFee + $totals.total}<div class="bold tots" id="total">${$newTotal|number_format:2:".":","}</div>
		              </div>
		            </div>

		            <div class="row tots tallrow"><!-- The following SUBTOTAL value was intentionally changed to TOTAL  -->
		              <div class="col-xs-12 col-sm-8 col-md-10 shopping-label bold text-right mobl"><a href="#">Edit ></a></div>
					</div>
	            </div>
				</div>
				<div class="col-sm-12">

				<div class="row topborder checkout3" style="display:none;">
					<div class="col-sm-6" id="bill3">
						<h3 class="bold tots">Billing address</h3>
						<div id="billing-summary">
						{if $address.B}
						{$address.B.address_name} {$address.B.address_surname}<br >
						{$address.B.address_line1}<br >
						{$address.B.address_suburb} {$address.B.address_state} {$address.B.address_postcode}<br >
						{$address.B.email}<br >
						{$address.B.address_telephone}<br >
						{/if}
						</div>
						<a href="javascript:void(0);" onclick="goCheckout2('#billing');"><span class="small">Edit ></span></a>
					</div>
					<div class="col-sm-6" id="ship3">
						<h3 class="bold tots">Shipping address</h3>
						<div id="shipping-summary">
						{if $address.same_address}
						<span class="small">Shipping address same as billing address</span><br />
						{if $comments}Shipping instructions: {$comments}{else}No shipping instructions <br />{/if}
						{else}
						{if $address.S}
						{$address.S.address_name} {$address.S.address_surname}<br >
						{$address.S.address_line1}<br >
						{$address.S.address_suburb} {$address.B.address_state} {$address.B.address_postcode}<br >
						{$address.S.address_telephone}<br >
						{if $comments}Shipping instructions: {$comments}{else}No shipping instructions <br />{/if}
						{/if}
						{/if}
					</div>
					<a href="javascript:void(0);" onclick="goCheckout2('#shipping');"><span class="small">Edit ></span></a>
				</div>
				</div>
            </div>
            </div>
          </div>

          <div class="checkout2 down">
            <form id="checkout2-form" role="form" accept-charset="UTF-8" action="" method="post">
              <input type="hidden" value="checkout2" name="action" />
              <input type="hidden" value="{$selectedShippingFee}" name="shipFee" id="shippingMethod"/>
	            <input type="hidden" value="{$selectedShipping}" name="shipMethod" id="shipMethod"/>
	            <input type="hidden" value="{$postageID}" name="postageID" id="postageID"/>
              <br />
              <div class="row checkform">
                <div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2 text-center" id="billing">
                <h3 class="bold tots">Billing details</h3>
    	  		<div class="row">
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="name1">First Name<span>*</span>:</label>
    					<input class="form-control" value="{if $address & $address.B.address_name}{$address.B.address_name}{else}{$user.gname}{/if}" type="text" name="address[B][address_name]" id="name1" required="">
						<div class="error-msg help-block"></div>
    				</div>
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="surname1">Surname<span>*</span>:</label>
    					<input class="form-control" value="{if $address && $address.B.address_surname}{$address.B.address_surname}{else}{$user.surname}{/if}" type="text" name="address[B][address_surname]" id="surname1" required="">
						<div class="error-msg help-block"></div>
    				</div>
    			</div>


    	  		<div class="row">
                  <div class="col-sm-6 form-group">
                    <label class="visible-ie-only" for="street">Address<span>*</span>:</label>
                      <input id="street" value="{if $address}{$address.B.address_line1}{/if}" name="address[B][address_line1]" type="text" class="billing-req form-control" required="required"  />
						<div class="error-msg help-block"></div>
                  </div>
                  <div class="col-sm-6 form-group">
                    <label class="visible-ie-only" for="suburb">Suburb*:</label>
                      <input id="suburb" value="{if $address}{$address.B.address_suburb}{else}{$selectedShippingSuburb}{/if}" name="address[B][address_suburb]" type="text" class="billing-req form-control" required="required"  />
						<div class="error-msg help-block"></div>
                  </div>
    	  		</div>

    	  		<div class="row">
                  <div class="col-sm-6 form-group">
                    <label class="visible-ie-only" for="state">State*:</label>
                      <select id="state" name="address[B][address_state]" class="required form-control">
                        <option value="">Please select</option>
                        {foreach $options_state as $value }
                        <option value="{$value.postcode_state}" {if ($address && $address.B.address_state eq $value.postcode_state) OR $selectedShippingState eq $value.postcode_state}selected="selected"{/if}>{$value.postcode_state}</option>
                      {/foreach}
                      </select>
						<div class="error-msg help-block"></div>
                  </div>
                  <div class="col-sm-6 form-group">
                    <label class="visible-ie-only" for="postcode-field">Postcode*:</label>
                      <input id="postcode-field" value="{if $shippostcode}{$shippostcode}{else}{if $address && $address.B.address_postcode}{$address.B.address_postcode}{/if}{/if}" name="address[B][address_postcode]" pattern="[0-9]" type="text" class="postcode billing-req form-control" required="required" onPaste="updateShipping();" onBlur="updateShipping();" {literal}onkeydown="if(event.keyCode == 13 || $(this).val().length >= 4){updateShipping();}"{/literal} />
						<div class="error-msg help-block"></div>
                  </div>
    	  		</div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label class="visible-ie-only" for="email">Email*:</label>
                      <input id="email" value="{if $address && $address.B.email}{$address.B.email}{else}{$user.email}{/if}" name="address[B][email]" type="email" class="billing-req form-control" required="required"  />
						<div class="error-msg help-block"></div>
                  </div>
                  <div class="col-sm-6 form-group">
                    <label class="visible-ie-only" for="phone">Phone*:</label>
                      <input id="phone" value="{if $address}{$address.B.address_telephone}{/if}" name="address[B][address_telephone]" type="text" class="phone billing-req form-control" required="required"  />
						<div class="error-msg help-block"></div>
                  </div>
                </div>
                </div>

                <div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2 text-center" id="shipping">
                    <div>
                     <h3>Shipping details</h2>

                    <div class="row">
	                    <div class="col-sm-12 form-group">
							<input id="chksame" name="address[chksame]" type="checkbox" checked="checked" onclick="sameAddress();" />
							<label class="chklab" for="chksame"><span class="bold">Ship items to the above billing address.</span> Please note we cannot ship to PO boxes.</label>
	                    </div>
                    </div>
					<div id="shipping-subform">
					<div class="row">
						<div class="col-sm-6 form-group">
                        <label class="visible-ie-only" for="namesh1">First Name*:</label>
                          <input id="namesh1" value="{if $address}{$address.S.address_name}{/if}" name="address[S][address_name]" type="text" class="shipping-req form-control" required="required"/>
						<div class="error-msg help-block"></div>
						</div>
						<div class="col-sm-6 form-group">
                        <label class="visible-ie-only" for="surnamesh1">Surname*:</label>
                          <input id="surnamesh1" value="{if $address}{$address.S.address_surname}{/if}" name="address[S][address_surname]" type="text" class="shipping-req form-control" required="required"/>
						<div class="error-msg help-block"></div>
                      </div>
					</div>
					<div class="row">
						<div class="col-sm-6 form-group">
                        <label class="visible-ie-only" for="streetsh">Street address*:</label>
                          <input id="streetsh" value="{if $address}{$address.S.address_line1}{/if}" name="address[S][address_line1]" type="text" class="shipping-req form-control" required="required" />
						<div class="error-msg help-block"></div>
                      </div>
						<div class="col-sm-6 form-group">
                         <label class="visible-ie-only" for="suburbsh">Suburb*:</label>
                          <input id="suburbsh" value="{if $address}{$address.S.address_suburb}{/if}" name="address[S][address_suburb]" type="text" class="shipping-req form-control" required="required" />
						<div class="error-msg help-block"></div>
                      </div>
					</div>

					<div class="row">
						<div class="col-sm-6 form-group">
                         <label class="visible-ie-only" for="statesh">State*:</label>
                          <select id="statesh" name="address[S][address_state]" class="shipping-select-req required form-control">
                            <option value="">Please select</option>
                            {foreach $options_state as $value }
                            <option value="{$value.postcode_state}" {if $address && $address.S.address_state eq $value.postcode_state}selected="selected"{/if}>{$value.postcode_state}</option>
                          {/foreach}
                          </select>
						<div class="error-msg help-block"></div>
                        </div>
						<div class="col-sm-6 form-group">
                         <label class="visible-ie-only" for="postcodesh">Postcode*:</label>
                          <input id="postcodesh" value="{if $address}{$address.S.address_postcode}{/if}" name="address[S][address_postcode]" pattern="[0-9]" type="text" class="postcode shipping-req form-control" required="required"  onPaste="updateShipping();" onBlur="updateShipping();" {literal}onkeydown="if(event.keyCode == 13 || $(this).val().length >= 4){updateShipping();}"{/literal} />
						<div class="error-msg help-block"></div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-6 form-group">
                        <label class="visible-ie-only" for="phonesh">Phone*:</label>
                          <input id="phonesh" value="{if $address}{$address.S.address_telephone}{/if}" name="address[S][address_telephone]" type="text" class="phone shipping-req form-control" required="required" />
						<div class="error-msg help-block"></div>
                      </div>
                    </div>
					</div>

					<div class="row">
						<div class="col-sm-12 form-group">
							<label class="visible-ie-only" for="comments">Shipping instructions <small>(max. 128 characters)</small>:</label>
							<textarea id="text" name="comments" id="text" maxlength="128" class="form-control">{if $comments}{$comments}{/if}</textarea>
						</div>
					</div>


                    <div class="row">
	                    <div class="col-sm-12 form-group">
							<input id="promo1" name="address[wantpromo]" type="checkbox" checked="checked" />
							<label class="chklab" for="promo1"><span class="bold">Please send me future promotional material.</span><br />Including product discounts and the MedicAlert monthly eNews. (You can unsubscribe at any time.)</label>
	                    </div>
                    </div>

					<div class="row">
						<div class="col-sm-12 text-center">
							<div class="error-textbox" style="display:none;" id="error-text1"></div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-12 text-center">
							<div class="postcode-invalid text-danger"></div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-12 text-center">
							<a class="btn-red btn process-cnt" onclick="$('#checkout2-form').submit();">Continue to payment</a>
						</div>
					</div>

                  </div>
                </div>
              </div>
          </form>
          </div>
          <div class="checkout3 down" style="display:none;">

            <form id="checkout3-form" role="form" accept-charset="UTF-8" action="/process/cart" method="post">
              <div class="row process-cnt">
	              <div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2 text-center">
				  	<input type="hidden" value="placeOrder" name="action" />
				  	{if $newTotal gt 0.01 }

				  	{if $error}
				  	<div class="row">
					  	<div class="col-sm-12 error-textbox" id="error">{$error}</div>
				  	</div>
				  	{/if}

				  	<div class="row">
				  		<div class="col-sm-12">
					  		Payment accepted:
				  			<img src="/images/donate-visamc.jpg" alt="Visa Mastercard" />
				  		</div>
				  	</div>
				  	<div class="row notice">
					  	<div class="col-sm-12 dark">
					  	All transactions are secure and encrypted, and we never store your credit card information. To learn more, please view our <a href="/privacy-policy" target="_blank">privacy policy</a>.
					  	</div>
				  	</div>

				  	<div class="row">
				  		<div class="col-sm-6 form-group">
				  			<label class="visible-ie-only" for="ccno">Card number<span>*</span>:</label>
				  			<input type="text" id="ccno" class="cc-req form-control" name="cc[number]" autocomplete="off" />
				  			<div class="error-msg help-block"></div>
				  		</div>

				  		<div class="col-sm-6 form-group">
				  			<label class="visible-ie-only" for="ccname">Cardholder's name<span>*</span>:</label>
				  			<input type="text" id="ccname" class="cc-req form-control" name="cc[name]" autocomplete="off" />
				  			<div class="error-msg help-block"></div>
				  		</div>
                   </div>

				  	<div class="row">
				  		<div class="col-sm-6 form-group">
				  			<label class="visible-ie-only" for="ccmonth">Expiry<span>*</span>:</label>
				  			<div class="row">
					  		<div class="col-sm-6">
				  			<select id="ccmonth" name="cc[month]" class="cc-select-req form-control">
	                        <option value="">Month</option>
	                        <option value="01">1 - Jan</option>
	                        <option value="02">2 - Feb</option>
	                        <option value="03">3 - Mar</option>
	                        <option value="04">4 - Apr</option>
	                        <option value="05">5 - May</option>
	                        <option value="06">6 - Jun</option>
	                        <option value="07">7 - Jul</option>
	                        <option value="08">8 - Aug</option>
	                        <option value="09">9 - Sep</option>
	                        <option value="10">10 - Oct</option>
	                        <option value="11">11 - Nov</option>
	                        <option value="12">12 - Dec</option>
							</select>
				  			<div class="error-msg help-block"></div>
					  		</div>
					  		<div class="col-sm-6">
		                    <select id="ccyear" name="cc[year]" class="cc-select-req form-control" >
	                       {assign var=thisyear value=$smarty.now|date_format:"%Y"}
	                       {assign var=numyears value=$thisyear+20}
	                       <option value="">Year</option>
	                       {for $year=$thisyear to $numyears}
	                         <option value="{$year}">{$year}</option>
	                       {/for}
		                   </select>
				  			<div class="error-msg help-block"></div>
					  		</div>
				  			</div>
				  		</div>

				  		<div class="col-sm-6 form-group">
				  			<label class="visible-ie-only" for="cccsv">Security code<span>*</span> <img src="/images/question-mark.png" alt="What is this?" title="What is this?" data-toggle="tooltip" data-placement="top" /> :</label>
				  			<div>
					  			<input type="text" id="cccsv" name="cc[csv]" class="seccode cc-req form-control" autocomplete="off" />
					  			<img  class="seccode" src="/images/donate-security.jpg" alt="Security code" />
				  			</div>
					  		<div class="error-msg help-block"></div>
				  		</div>
                   </div>
               </div>
              {else}
                <div class="col-sm-12">
                  No payment details are required.
                </div>
                {if $error}
                  <div class="row" id="error">
                  	<div class="col-sm-12 error-textbox">{$error}
                  	</div>
                  </div>
                {/if}
              {/if}
              </div>

              <div class="row">
	              <div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2 text-center autorenew">

				  	<div class="row">
					  	<div class="col-sm-12 text-center">
						  	<h3>Auto renewal of membership (optional)</h3>
					  	</div>
				  	</div>
				  	<div class="row">
					  	<div class="col-sm-12 text-center">
						  	<input class="autor" type="checkbox" value="autorenew" name="autorenewal" id="autorenewal" onclick="autorenew();">
						  	<label class="autor chkbox" for="autorenewal">Stay protected each year: sign up for auto-renewal.</label>
					  	</div>
				  	</div>
				  	<div class="row notice">
					  	<div class="col-sm-12 text-center">
					  		<p>Auto-renewal helps protect you by paying your annual membership fee automatically each year by direct debit from your nominated payment method.
Please confirm your auto-renewal payment method below. The first payment will occur at your next renewal of annual membership.</p>

							<p>Please update with an alternative credit card if required.</p>
					  	</div>
				  	</div>
				  	<div id="autorenew-subform">

				  	<div class="row">
					  	<div class="col-sm-6 text-center">
					  		<div class="row">
						  		<div class="col-sm-12 form-group">
						  			<input class="form-control autor" type="radio" value="Credit card" name="autopayment" id="autocredit" required="" onclick="automethod();">
						  			<label class="autor chkbox" for="autocredit">Credit card</label>
						  			<div class="error-msg help-block"></div>
						  		</div>
					  		</div>
					  	</div>
					  	<div class="col-sm-6 text-center">
					  		<div class="row">
						  		<div class="col-sm-12 form-group">
						  			<input class="form-control autor" type="radio" value="Direct debit" name="autopayment" id="autodd" required="" onclick="automethod();">
						  			<label class="autor chkbox" for="autodd">Direct debit</label>
						  			<div class="error-msg help-block"></div>
						  		</div>
					  		</div>
					  	</div>
				  	</div>

				  	<div class="row">
					  	<div class="col-sm-6 text-center" id="autocreditc" style="display: none;">
					  		<div class="row">
						  		<div class="col-sm-12 form-group">
						  			<label class="visible-ie-only" for="autocreditno">Card number<span>*</span>:</label>
						  			<input class="form-control" type="text" name="autocreditno" id="autocreditno" required>
						  			<div class="error-msg help-block"></div>
						  		</div>
					  		</div>

					  		<div class="row">
						  		<div class="col-sm-12 form-group">
						  			<label class="visible-ie-only" for="autocreditname">Cardholder name<span>*</span>:</label>
						  			<input class="form-control" type="text" name="autocreditname" id="autocreditname" required>
						  			<div class="error-msg help-block"></div>
						  		</div>
					  		</div>

					  		<div class="row">
						  		<div class="col-sm-12 form-group">
						  			<label class="visible-ie-only" for="autocreditexp">Expiry<span>*</span>:</label>
						  			<div class="row">
							  		<div class="col-sm-6">
						  			<select class="form-control" id="autocreditmonth" name="autocreditmonth" class="cc-select-req form-control">
			                        <option value="">Month</option>
			                        <option value="01">1 - Jan</option>
			                        <option value="02">2 - Feb</option>
			                        <option value="03">3 - Mar</option>
			                        <option value="04">4 - Apr</option>
			                        <option value="05">5 - May</option>
			                        <option value="06">6 - Jun</option>
			                        <option value="07">7 - Jul</option>
			                        <option value="08">8 - Aug</option>
			                        <option value="09">9 - Sep</option>
			                        <option value="10">10 - Oct</option>
			                        <option value="11">11 - Nov</option>
			                        <option value="12">12 - Dec</option>
									</select>
						  			<div class="error-msg help-block"></div>
							  		</div>
							  		<div class="col-sm-6">
				                    <select id="autocredityear" name="autocredityear" class="cc-select-req form-control" >
			                       {assign var=thisyear value=$smarty.now|date_format:"%Y"}
			                       {assign var=numyears value=$thisyear+20}
			                       <option value="">Year</option>
			                       {for $year=$thisyear to $numyears}
			                         <option value="{$year}">{$year}</option>
			                       {/for}
				                   </select>
						  			<div class="error-msg help-block"></div>
							  		</div>
						  			</div>
						  		</div>
					  		</div>

					  		<div class="row">
						  		<div class="col-sm-12 form-group">
						  			<label class="visible-ie-only" for="cccsv">Security code<span>*</span>:<a class="small" data-toggle="modal" data-target="#csv-info" href="#"><img src="/images/question-mark.png" alt="What is this?" title="What is this?" /></a></label>
						  			<div>
							  			<input type="text" id="cccsv" name="cc[csv]" class="seccode cc-req form-control" autocomplete="off" required />
							  			<img class="seccode" src="/images/donate-security.jpg" alt="Security code" />
						  			</div>
						  			<div class="error-msg help-block"></div>
						  		</div>
					  		</div>
					  	</div>

					  	<div class="col-sm-6 col-sm-offset-6 text-center" id="autodirectd" style="display: none;">
					  		<div class="row">
						  		<div class="col-sm-12 form-group">
						  			<label class="visible-ie-only" for="autobsb">BSB<span>*</span>:</label>
						  			<input class="form-control" type="text" name="autobsb" id="autobsb" required>
						  			<div class="error-msg help-block"></div>
						  		</div>
					  		</div>

					  		<div class="row">
						  		<div class="col-sm-12 form-group">
						  			<label class="visible-ie-only" for="autoddname">Account holder's name<span>*</span>:</label>
						  			<input class="form-control" type="text" name="autoddname" id="autoddname" required>
						  			<div class="error-msg help-block"></div>
						  		</div>
					  		</div>

					  		<div class="row">
						  		<div class="col-sm-12 form-group">
						  			<label class="visible-ie-only" for="autoddno">Account number<span>*</span>:</label>
						  			<input class="form-control" type="text" name="autoddno" id="autoddno" required>
						  			<div class="error-msg help-block"></div>
						  		</div>
					  		</div>

					  	</div>

					  	<div class="col-sm-12 text-center">
					  		<div class="row">
						  		<div class="col-sm-12 form-group chkbx">
				                  <input class="autor" type="checkbox" id="accept" name="accept" required/>
					                <label class="autor chklab" for="accept">
					                  I confirm that I have read and agree to the <a href="#">auto-renewal terms &amp; conditions</a> and I wish to register for auto-renewal of my membership.
					                </label>
						  			<div class="error-msg help-block"></div>
						  		</div>
					  		</div>
					  	</div>

				  	</div>
				  	</div>

				  	<div class="row">
					  	<div class="col-sm-12 text-center">
					  		<div class="row">
						  		<div class="col-sm-12 form-group">
						  			<div style="display:none;" id="error-text2"></div>


						  			<a class="btn-red btn process-cnt" id="payment-btn" onclick="$('#checkout3-form').submit();">Complete Checkout</a>
						  		</div>
					  		</div>
					  	</div>

				  	</div>

	              </div>
              </div>
               </div>
            </form>
            </div>
          </div>
        </div>
      </div>
  </div>
  <div id="csv-info" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalcsv" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        What is CSV?<button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close</button>
      </div>
      <div class="modal-body text-center">
	  			<img src="/images/csv.jpg" alt="csv">
	  			<p>3 digit verification number found on the back of your credit card</p>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
{/block}

{* Place additional javascript here so that it runs after General JS includes *}
{block name=tail}
<script src="/includes/js/jquery-ui.js"></script>
<script>
/*** Handle jQuery plugin naming conflict between jQuery UI and Bootstrap ***/
$.widget.bridge('uitooltip', $.ui.tooltip);
</script>

<script type="text/javascript" src="/includes/js/jquery.selectBoxIt.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    $('#checkout2-form').validate();
    $('#checkout3-form').validate();
    updateShipping();

    sameAddress();
	autorenew();

	$("select").selectBoxIt();
  $('[data-toggle="tooltip"]').tooltip()


    {if $newTotal gt 0.01 }

    $('#ccno').rules("add", {
      creditcard : true,
    });

    $('#cccsv').rules("add", {
      digits: true,
      minlength: 3
    });

    {/if}

    $('#postcode-field').rules("add", {
      digits: true,
      minlength: 3
    });
    $('#postcodesh').rules("add", {
      digits: true,
      minlength: 3
    });

    scrolltodiv('#billing');

    {if $error}
      goCheckout3();
    {/if}
  });


  function sameAddress() {
    if($("#chksame").is(':checked')){
      $('.shipping-req').removeAttr('required');
      $('.shipping-select-req').removeClass('required');
      $('#shipping-subform').hide();
      $(".shipbill").show();
    }else{
      $('.shipping-req').attr('required', 'required');
      $('.shipping-select-req').addClass('required');
      $(".shipbill").hide();
      $('#shipping-subform').show();
    }

  }

  function automethod(){
    if($("input[name=autopayment]:checked").val() == 'Credit card'){
      $('#autodirectd input').removeAttr('required');
      $('#autocreditc input').attr('required', 'required');
      $('#autodirectd').hide();
      $("#autocreditc").show();
    }else{
      $('#autocreditc input').removeAttr('required');
      $('#autodirectd input').attr('required', 'required');
      $('#autodirectd').show();
      $("#autocreditc").hide();
    }
  }

  function autorenew(){
    if($("#autorenewal").is(':checked')){
      $('#autorenew-subform').show();
    }else{
      $('#autorenew-subform').hide();
    }
  }

  function setCCRequired(CONDITION) {
    if(CONDITION){
      $('.cc-req').attr('required', 'required');
      $('.cc-select-req').addClass('required');
    }else{
      $('.cc-req').removeAttr('required');
      $('.cc-select-req').removeClass('required');
    }

  }

  function goCheckout2(OBJ){
    location.reload();
  }

  function goCheckout3(){
    $('.checkout2').hide();
    $('.checkout3').show();
    scrolltodiv('#checkout3-form');
    setCCRequired(true);
  }

  function maxLength(el) {
      if (!('maxLength' in el)) {
          var max = el.attributes.maxLength.value;
          el.onkeypress = function () {
              if (this.value.length >= max) return false;
          };
      }
  }

  maxLength(document.getElementById("text"));
</script>
{/block}