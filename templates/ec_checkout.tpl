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
        <h1 class="checkout2">Delivery</h1>
        <h1 class="checkout3" style="display: none;">Payment</h1>
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
		  	<div class="hidden-xs col-xs-3 col-sm-2 col-md-2 text-center">Price</div>
		  	<div class="hidden-xs col-xs-3 col-sm-2 col-md-2 text-center">Quantity</div>
		  	<div class="hidden-xs col-xs-3 col-sm-2 col-md-2 text-center">Total</div>
		  </div>
          <div class="row tallbox">
            {foreach from=$productsOnCart item=item}
            <div id="{$item.cartitem_id}" class="prod-item cartitem">
              <div class="col-xs-3 col-sm-2 col-md-2 text-center">
                <img class="img-responsive" src="{if $item.gallery.0.gallery_link neq ''}{$item.gallery.0.gallery_link}?width=120&height=76&crop=1{else}/images/no-image-available.jpg?width=120&height=76&crop=1{/if}" alt="{$item.gallery.0.gallery_alt_tag}" title="{$item.gallery.0.gallery_title}" />
              </div>
              <div class="col-xs-9 col-sm-3 col-md-4 bluetext valgn">
                <a href="{if $item.cartitem_type_id eq 1}/{$item.product_url}{else}javascript:void(0){/if}">{$item.cartitem_product_name}{if $item.cartitem_type_id eq 3} - {$item.variant_name}{/if}</a>{if $item.cartitem_product_gst eq '1'} *{/if}
                {if $item.cartitem_type_id eq 1}<br><small>{$item.cartitem_product_uid}</small>{/if}
                {if $item.attributes &&  $item.cartitem_type_id neq 2} 
                {foreach from=$item.attributes item=attr}
                <div class="attributes">
                  {$attr.cartitem_attr_attribute_name}: {$attr.cartitem_attr_attr_value_name} 
                  {if $attr.cartitem_attr_attr_value_additional && $attr.cartitem_attr_attribute_id eq 1} 
                    ({foreach $attr.cartitem_attr_attr_value_additional|json_decode as $k => $v}<span>{$v}</span>{/foreach}) 
                  {/if}  
                 </div>
                {if $attr.cartitem_attr_attr_value_additional && $attr.cartitem_attr_attribute_id eq 4} 
                  <a class="eng" href="javascript:void(0)" onclick="if($('.addattr{$attr.cartitem_attr_id}').is(':visible')){ $('.addattr{$attr.cartitem_attr_id}').hide('slow'); $(this).html('Show engraving +'); }else{ $('.addattr{$attr.cartitem_attr_id}').show('slow'); $(this).html('Hide engraving  -'); }">Show engraving +</a> 
                  {foreach $attr.cartitem_attr_attr_value_additional|json_decode as $k => $v}
                  <div class="additional-attributes addattr{$attr.cartitem_attr_id}" style="display: none;">Line {$k}: {$v}</div>
                  {/foreach} 
                {/if} 
                {/foreach} {/if}
              </div>
              <div class="visible-xs col-xs-3"></div>
              <div class="hidden-xs col-xs-9 col-sm-2 col-md-2 text-center valgn" id="priceunit-{$item.cartitem_id}">${$item.cartitem_product_price|number_format:2:".":","}</div>
              <div class="col-xs-9 col-xs-offset-3 col-sm-2 col-sm-offset-0 col-md-2 quant text-center valgn mobl">
			  	<span class="visible-xs">{$item.cartitem_quantity} x ${$item.cartitem_product_price|number_format:2:".":","}</span><span class="hidden-xs">{$item.cartitem_quantity}</span>
              </div>
              <div class="col-xs-6 col-xs-offset-3 col-sm-2 col-sm-offset-0 text-center valgn mobl" id="subtotal-{$item.cartitem_id}">${$item.cartitem_subtotal|number_format:2:".":","}</div>
            </div>
            {/foreach}
          </div>
			  <div class="row totcost">
				<div class="col-sm-12">
		            <div class="row tallrow">
		              <div class="col-xs-7 col-sm-8 col-md-10 shopping-label text-right mobl">Subtotal</div>
		              <div class="col-xs-5 col-sm-4 col-md-2 num text-right mobr" id="subtotal" data-value="{$totals.subtotal}">${$totals.subtotal|number_format:2:".":","}</div>
		            </div>
		            <div class="row tallrow" {if $totals.discount eq 0}style="display:none"{/if}>
		              <div class="col-xs-7 col-sm-8 col-md-10 shopping-label text-right mobl"><b>Discount</b></div>
		              <div class="col-xs-5 col-sm-4 col-md-2 num text-right mobr" id="discount" data-value="{$totals.discount}">{if $totals.discount gt 0}<b>-${$totals.discount|number_format:2:".":","}</b>{else}$0.00{/if}</div>
		            </div>
                    
                    {assign var='newTotal' value=$totals.total}
		            <!-- SHIPPING -->
		            <div class="row tallrow">
		              {foreach $shippingMethods as $k => $v}
                        <input type="hidden" value="{$k}" data-value="{$v}" name="selectedMethod" id="shippingMethod" />
		                <div class="col-xs-8 col-sm-8 col-md-10 shopping-label text-right  mobl">{$k} <img src="/images/question-mark.png" alt="Please allow up to 10 business days to process and dispatch your order." title="Please allow up to 10 business days to process and dispatch your order." data-toggle="tooltip" data-placement="top" /></div>
		                <div class="col-xs-4 col-sm-4 col-md-2 num text-right mobr">${$v|number_format:2:".":","}</div>
                        {$newTotal = $v + $newTotal}
		              {/foreach}
		            </div>

		            <div class="row tallrow">
		              <div class="col-xs-7 col-sm-8 col-md-10 shopping-label text-right  mobl">GST inc. in total<br /><span class="subj">(*Subject to GST)</span></div>
		              <div class="col-xs-5 col-sm-4 col-md-2 num text-right mobr" id="GST">(${$totals.GST|number_format:2:".":","})</div>
		            </div>

		            <div class="row tots tallrow"><!-- The following SUBTOTAL value was intentionally changed to TOTAL  -->
		              <div class="col-xs-7 col-sm-8 col-md-10 shopping-label bold text-right mobl" id="totall">Total</div>
		              <div class="col-xs-5 col-sm-4 col-md-2 bold num text-right mobr" id="total"><div class="bold tots" id="total">${$newTotal|number_format:2:".":","}</div>
		              </div>
		            </div>

		            <div class="row tots tallrow"><!-- The following SUBTOTAL value was intentionally changed to TOTAL  -->
		              <div class="col-xs-12 col-sm-8 col-md-10 shopping-label bold text-right mobl"><a href="/shopping-cart" title="Edit items">Edit ></a></div>
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
						{$address.B.address_email}<br >
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
						{if $address.comments}Shipping instructions: {$address.comments}{else}No shipping instructions <br />{/if}
						{else}
						{if $address.S}
						{$address.S.address_name} {$address.S.address_surname}<br >
						{$address.S.address_line1}<br >
						{$address.S.address_suburb} {$address.B.address_state} {$address.B.address_postcode}<br >
						{$address.S.address_telephone}<br >
						{if $address.comments}Shipping instructions: {$address.comments}{else}No shipping instructions <br />{/if}
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
    					<input class="form-control" value="{if $address & $address.B.address_name}{$address.B.address_name}{else}{if $user.gname}{$user.gname}{else}{$new_user.gname}{/if}{/if}" type="text" name="address[B][address_name]" id="name1" required="">
						<div class="error-msg help-block"></div>
    				</div>
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="surname1">Surname<span>*</span>:</label>
    					<input class="form-control" value="{if $address && $address.B.address_surname}{$address.B.address_surname}{else}{if $user.surname}{$user.surname}{else}{$new_user.surname}{/if}{/if}" type="text" name="address[B][address_surname]" id="surname1" required="">
						<div class="error-msg help-block"></div>
    				</div>
    			</div>


    	  		<div class="row">
                  <div class="col-sm-6 form-group">
                    <label class="visible-ie-only" for="street">Address<span>*</span>:</label>
                      <input id="street" value="{if $address}{$address.B.address_line1}{else}{if $user.maf.main.user_address}{$user.maf.main.user_address}{else}{$new_user.address}{/if}{/if}" name="address[B][address_line1]" type="text" class="billing-req form-control" required="required"  />
						<div class="error-msg help-block"></div>
                  </div>
                  <div class="col-sm-6 form-group">
                    <label class="visible-ie-only" for="suburb">Suburb*:</label>
                      <input id="suburb" value="{if $address}{$address.B.address_suburb}{else}{if $user.maf.main.user_suburb}{$user.maf.main.user_suburb}{else}{$new_user.suburb}{/if}{$selectedShippingSuburb}{/if}" name="address[B][address_suburb]" type="text" class="billing-req form-control" required="required"  />
						<div class="error-msg help-block"></div>
                  </div>
    	  		</div>

    	  		<div class="row">
                  <div class="col-sm-6 form-group">
                    <label class="visible-ie-only" for="state">State*:</label>
                      <select id="state" name="address[B][address_state]" class="required form-control addplugin">
                        <option value="">Please select an option</option>
                        {$cur_state = $selectedShippingState}
                        {if $address}{$cur_state = $address.B.address_state}{else}{if $user.maf.main.user_state_id}{$cur_state = $user.maf.main.user_state_id}{else}{$cur_state = $new_user.state}{/if}{/if}
                        {foreach $options_state as $opt }
                        <option value="{$opt.value}" {if $cur_state eq $opt.value}selected="selected"{/if}>{$opt.value}</option>
                      {/foreach}
                      </select>
						<div class="error-msg help-block"></div>
                  </div>
                  <div class="col-sm-6 form-group">
                    <label class="visible-ie-only" for="postcode-field">Postcode*:</label>
                      <input id="postcode-field" value="{if $shippostcode}{$shippostcode}{else}{if $address && $address.B.address_postcode}{$address.B.address_postcode}{else}{if $user.maf.main.user_postcode}{$user.maf.main.user_postcode}{else}{$new_user.postcode}{/if}{/if}{/if}" name="address[B][address_postcode]" pattern="[0-9]*" type="text" class="postcode billing-req form-control" required="required" onPaste="updateShipping();" onBlur="updateShipping();" {literal}onkeydown="if(event.keyCode == 13 || $(this).val().length >= 4){updateShipping();}"{/literal} />
						<div class="error-msg help-block"></div>
                  </div>
    	  		</div>

                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label class="visible-ie-only" for="email">Email*:</label>
                      <input id="email" value="{if $address && $address.B.address_email}{$address.B.address_email}{else}{if $user.email}{$user.email}{else}{$new_user.email}{/if}{/if}" name="address[B][address_email]" type="email" class="billing-req form-control" required="required"  />
						<div class="error-msg help-block"></div>
                  </div>
                  <div class="col-sm-6 form-group">
                    <label class="visible-ie-only" for="phone">Phone*:</label>
                      <input id="phone" value="{if $address}{$address.B.address_telephone}{else}{if $user.maf.main.user_mobile}{$user.maf.main.user_mobile}{else}{if $new_user.mobile neq ''}{$new_user.mobile}{else}{if $new_user.home_phone neq ''}{$new_user.home_phone}{else}{if $new_user.work_phone neq ''}{$new_user.work_phone}{/if}{/if}{/if}{/if}{/if}" name="address[B][address_telephone]" type="text" class="phone billing-req form-control" required="required" maxlength="10" pattern="[0-9]*"/>
						<div class="error-msg help-block"></div>
                  </div>
                </div>
                </div>

                <div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2 text-center" id="shipping">
                    <div>
                     <h3>Shipping details</h2>

                    <div class="row">
	                    <div class="col-sm-12 form-group">
							<input id="chksame" name="address[same_address]" type="checkbox" checked="checked" onclick="sameAddress();" />
							<label class="chklab" for="chksame"><span class="bold">Ship items to the above billing address.</span></label>
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
                          <select id="statesh" name="address[S][address_state]" class="shipping-select-req required form-control addplugin">
                            <option value="">Please select an option</option>
                            {foreach $options_state as $opt }
                            <option value="{$opt.value}" {if $address && $address.S.address_state eq $opt.value}selected="selected"{/if}>{$opt.value}</option>
                          {/foreach}
                          </select>
						<div class="error-msg help-block"></div>
                        </div>
						<div class="col-sm-6 form-group">
                         <label class="visible-ie-only" for="postcodesh">Postcode*:</label>
                          <input id="postcodesh" value="{if $address}{$address.S.address_postcode}{/if}" name="address[S][address_postcode]" pattern="[0-9]*" type="text" class="postcode shipping-req form-control" required="required"  onPaste="updateShipping();" onBlur="updateShipping();" {literal}onkeydown="if(event.keyCode == 13 || $(this).val().length >= 4){updateShipping();}"{/literal} />
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
							<textarea id="text" name="address[comments]" id="text" maxlength="128" class="form-control">{if $address.comments}{$address.comments}{/if}</textarea>
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
				  	{if $newTotal gte 0.01 }


				  	<div class="row">
				  		<div class="col-sm-12">
					  		Payment accepted:
				  			<img src="/images/cards.png" alt="Visa Mastercard" />
				  		</div>
				  	</div>
				  	<div class="row notice">
					  	<div class="col-sm-12 dark">
					  	  Australia Medic Alert Foundation is secured by GeoTrust&reg; to protect your information. To learn more, please view our <a href="/privacy-policy" target="_blank">privacy policy</a>.
					  	</div>
				  	</div>

				  	<div class="row">
				  		<div class="col-sm-6 form-group">
				  			<label class="visible-ie-only" for="ccno">Card number<span>*</span>:</label>
				  			<input type="text" id="ccno" class="cc-req form-control copycc" pattern="[0-9]*" name="cc[number]" autocomplete="off" />
				  			<div class="error-msg help-block"></div>
				  		</div>

				  		<div class="col-sm-6 form-group">
				  			<label class="visible-ie-only" for="ccname">Cardholder's name<span>*</span>:</label>
				  			<input type="text" id="ccname" class="cc-req form-control copycc" name="cc[name]" autocomplete="off" />
				  			<div class="error-msg help-block"></div>
				  		</div>
                   </div>

				  	<div class="row">
				  		<div class="col-sm-6 form-group">
				  			<label class="visible-ie-only" for="ccmonth">Expiry<span>*</span>:</label>
				  			<div class="row">
					  		<div class="col-sm-6">
				  			<select id="ccmonth" name="cc[month]" class="cc-select-req form-control copycc">
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
		                    <select id="ccyear" name="cc[year]" class="cc-select-req form-control copycc" >
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
				  			<label class="visible-ie-only" for="cccsv">Security code<span>*</span> <img src="/images/question-mark.png" alt="The three-digit number on the signature panel on the back of the card." title="The three-digit number on the signature panel on the back of the card." data-toggle="tooltip" data-placement="top" /> :</label>
				  			<div>
					  			<input type="text" id="cccsv" name="cc[csv]" class="seccode cc-req form-control copycc" autocomplete="off" pattern="[0-9]*" maxlength="4"  />
					  			<img  class="seccode" src="/images/donate-security.jpg" alt="Security code" />
				  			</div>
					  		<div class="error-msg help-block"></div>
				  		</div>
                   </div>
               </div>
              {else}
                <div class="col-sm-12">
                  <h3>No payment details are required.</h3>
                  <br>
                </div>
              {/if}
              </div>

          <div class="row">
                
                <div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2 text-center autorenew" {if $user.maf.main.autoBilling eq 't' || $user.maf.main.lifetime eq 1 || $newTotal eq 0}style="display:none"{/if}>

            <div class="row">
              <div class="col-sm-12 text-center">
                <h3>Auto renewal of membership (optional)</h3>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12 text-center">
                <input class="autor" type="checkbox" value="autorenew" {if (!$post || $post.autorenewal) && ($user.maf.main.autoBilling neq 't' && $user.maf.main.lifetime neq 1 && $newTotal neq 0)}checked="checked"{/if} name="autorenewal" id="autorenewal" onclick="autorenew();">
                <label class="autor chkbox" for="autorenewal">Stay protected each year: sign up for auto-renewal.</label>
              </div>
            </div>
            <div class="row notice">
              <div class="col-sm-12 text-center">
                <p>Auto-renewal helps protect you by paying your annual membership fee automatically each year by direct debit from your nominated payment method.
                            Please confirm your auto-renewal payment method below. The first payment will occur at your next renewal of annual membership.</p>

              
              </div>
            </div>
            <div id="autorenew-subform">
            <div class="row">
              <div class="col-sm-6 text-center">
                <div class="row">
                  <div class="col-sm-12 form-group">
                    <input class="form-control autor" type="radio" value="cc" name="autopayment" id="autocredit">
                    <label class="autor chkbox" for="autocredit">Credit card</label>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 text-center">
                <div class="row">
                  <div class="col-sm-12 form-group">
                    <input class="form-control autor" type="radio" value="dd" name="autopayment" id="autodd">
                    <label class="autor chkbox" for="autodd">Direct debit</label>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
              </div>
            </div>

            <div id="auto-cc-wrapper" class="auto-opts" style="display:none;">
                <div class="row">
                <div class="col-sm-12">
                <p>Please update with an alternative credit card if required.</p><br>
                </div>
              <div class="col-sm-6 form-group">
                <label class="visible-ie-only" for="auto-ccno">Card number<span>*</span>:</label>
                <input type="text" id="auto-ccno" class="auto-cc-req form-control" name="auto-cc[number]" pattern="[0-9]*" autocomplete="off" />
                <div class="error-msg help-block"></div>
              </div>

              <div class="col-sm-6 form-group">
                <label class="visible-ie-only" for="auto-ccname">Cardholder's name<span>*</span>:</label>
                <input type="text" id="auto-ccname" class="auto-cc-req form-control" name="auto-cc[name]" autocomplete="off" />
                <div class="error-msg help-block"></div>
              </div>
                   </div>

            <div class="row">
              <div class="col-sm-6 form-group">
                <label class="visible-ie-only" for="auto-ccmonth">Expiry<span>*</span>:</label>
                <div class="row">
                <div class="col-sm-6">
                <select id="auto-ccmonth" name="auto-cc[month]" class="auto-cc-req form-control">
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
                        <select id="auto-ccyear" name="auto-cc[year]" class="auto-cc-req form-control" >
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
                <label class="visible-ie-only" for="auto-cccsv">Security code<span>*</span> <img src="/images/question-mark.png" alt="The three-digit number on the signature panel on the back of the card." title="The three-digit number on the signature panel on the back of the card." data-toggle="tooltip" data-placement="top" /> :</label>
                <div>
                  <input type="text" id="auto-cccsv" name="auto-cc[csv]" class="seccode auto-cc-req form-control" autocomplete="off" pattern="[0-9]*" maxlength="4" />
                  <img  class="seccode" src="/images/donate-security.jpg" alt="Security code" />
                </div>
                <div class="error-msg help-block"></div>
              </div>
                   </div>
              </div>

              <div class="row auto-opts" id="auto-dd-wrapper" style="display: none;">
                  <div class="col-sm-offset-3 col-sm-6 form-group">
                    <label class="visible-ie-only" for="autobsb">BSB<span>*</span>:</label>
                    <input class="form-control auto-dd-req" type="text" name="auto-dd[bsb]" autocomplete="off" maxlength="6" id="autobsb"  pattern="[0-9]*">
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-offset-3 col-sm-6 form-group">
                    <label class="visible-ie-only" for="autoddname">Account holder's name<span>*</span>:</label>
                    <input class="form-control auto-dd-req" type="text" name="auto-dd[name]" autocomplete="off" maxlength="80" id="autoddname" >
                    <div class="error-msg help-block"></div>
                  </div>

                  <div class="col-sm-offset-3 col-sm-6 form-group">
                    <label class="visible-ie-only" for="autoddno">Account number<span>*</span>:</label>
                    <input class="form-control auto-dd-req" type="text" name="auto-dd[number]" autocomplete="off" maxlength="9" id="autoddno"  pattern="[0-9]*">
                    <div class="error-msg help-block"></div>
                  </div>

              </div>

              <div id="auto-renewal-conf-wrapper" class="col-sm-12 text-center" style="display:none;">
                <div class="row">
                  <div class="col-sm-12 form-group chkbx">
                          <input class="autor auto-cc-req auto-dd-req" type="checkbox" id="accept" name="accept" />
                          <label class="autor chklab" for="accept">
                            I confirm that I have read and agree to the <a href="/terms-and-conditions#auto-renewal" target="_blank" title="Click to view our terms and conditions">auto-renewal terms &amp; conditions</a> and I wish to register for auto-renewal of my membership.
                          </label>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
              </div>

            </div>
            </div>

            <div class="row">
              <div class="col-sm-offset-2 col-sm-8 text-center">
                    <div class="error-textbox" {if !$error}style="display:none;"{/if} id="form-error">{$error}</div>
                    <a class="btn-red btn process-cnt" id="payment-btn" onclick="$('#checkout3-form').submit();">Complete Checkout</a>
              </div>
            </div>
                </div>
            </form>
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
<script type="text/javascript" src="/includes/js/jquery.selectBoxIt.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    $('#checkout2-form').validate({
      submitHandler: function(form) {
        checkout2($(form).attr('id'));
      }
    });
    
    $('#checkout3-form').validate({
      submitHandler: function(form) {
        $('#payment-btn').html('Processing...').attr('disabled','disabled');
      	form.submit();
      }
    });
  		
    updateShipping();

    sameAddress();
	autorenew();

	$("select.addplugin").selectBoxIt({ autoWidth: false });
  	$('[data-toggle="tooltip"]').tooltip();

  	$('#phone').rules("add", {
     minlength: 8
   	});

    {if $newTotal gt 0.01 }

    $('#ccno').rules("add", {
      creditcard : true,
    });

    $('#cccsv').rules("add", {
      digits: true,
      minlength: 3
    });
    
    $('#auto-ccno').rules("add", {
      creditcard : true,
    });
    
    $('#autoddno').rules("add", {
      digits: true
    });
    
    $('#autobsb').rules("add", {
      digits: true,
      minlength: 6
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
    
    $('input[name="autopayment"]').change(function(){
      automethod();
    });
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
    var option = $('input[name="autopayment"]:checked').val();
    $('.auto-opts').hide();
    $('.auto-cc-req').removeAttr('required');
    $('.auto-dd-req').removeAttr('required');
    if(option){
      $('.auto-'+option+'-req').attr('required', 'required');
      $('#auto-'+option+'-wrapper').fadeIn();
      $('#auto-renewal-conf-wrapper').fadeIn();
      if(option == 'cc'){
        $('.copycc').each(function(){
          elementID = $(this).attr('id');
          elementName = $(this).attr('name');
          elementVal = $(this).val();
          elementNode = $(this).prop('nodeName');
          if(elementNode == 'SELECT'){
            $('select#auto-' + elementID).val(elementVal); 
            //$('select#auto-' + elementID).data("selectBox-selectBoxIt").selectOption(elementVal);            
          }else{
            $('input[name="auto-' + elementName + '"]').val(elementVal);  
          }
        });
      }
    }else{
      $('#auto-renewal-conf-wrapper').hide();
    }
  }

  function autorenew(){
    if($("#autorenewal").is(':checked')){
      $('#autorenew-subform').show();
      $('input[name="autopayment"]').attr('required', 'required');
      automethod();
    }else{
      $('#autorenew-subform').hide();
      $('input[name="autopayment"]').removeAttr('required');
      $('.auto-cc-req').removeAttr('required');
      $('.auto-dd-req').removeAttr('required');
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