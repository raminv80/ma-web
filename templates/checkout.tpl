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
          <h1>Checkout</h1>
          <div class="checkout2">
            <div class="hidden-xs">
              <a href="/shopping-cart">Step 1: Cart review</a> / <span class="bold">Step 2: Personal details</span> / Step 3: Payment
            </div>
            <div class="visible-xs">
              <span class="bold">Step 2 of 3</span>
            </div>
          </div>
          <div class="checkout3" style="display:none;">
            <div class="hidden-xs">
              <a href="/shopping-cart">Step 1: Cart review</a> / Step 2: Personal details / <span class="bold tots">Step 3: Payment</span>
            </div>
            <div class="visible-xs">
              <span class="bold">Step 3 of 3</span>
            </div>
          </div>  
          <br />
          <br />
          
          <div class="row tallbox">
              {foreach from=$productsOnCart item=item}
                <div class="row cartitem">
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
                    <div id="qty-{$item.cartitem_id}">{$item.cartitem_quantity}</div>
                    <div id="qty-discount-{$item.cartitem_id}">{if $item.productqty_modifier.productqty_modifier neq 0}(-{$item.productqty_modifier.productqty_modifier|number_format:0:".":','}%){/if}</div>
                  </div>
                  <div class="col-xs-3 col-sm-1 centre" id="priceunit-{$item.cartitem_id}">
                  <div id="price-{$item.cartitem_id}">${$item.cartitem_product_price|number_format:2:".":","}</div>
                  </div>
                  <div class="col-xs-3 col-sm-1 centre" id="subtotal-{$item.cartitem_id}">${$item.cartitem_subtotal|number_format:2:".":","}</div>
                  <div class="col-xs-3 col-sm-1">
                    <a href="/shopping-cart">Edit</a>
                  </div>  
                </div>
              {/foreach}
              <div class="row">
              <div class="col-sm-6 col-sm-offset-6">
                <div class="row"><div class="col-xs-4 col-sm-9 text-right">Discount</div>
                <div class="col-xs-8 col-sm-3 num text-right" id="discount">${if $totals.discount gt 0}-{/if}{$totals.discount|number_format:2:".":","}</div></div>
                <div class="row"><div class="col-xs-4 col-sm-9 text-right">Subtotal</div><!-- The following SUBTOTAL value was intentionally changed to TOTAL  -->
                <div class="col-xs-8 col-sm-3 num text-right" id="subtotal" data-value="{$totals.total}">${$totals.total|number_format:2:".":","}</div></div>    
                <div class="row"><div class="col-xs-4 col-sm-9 text-right">Shipping</div>
                <div class="col-xs-8 col-sm-3 num text-right" data-value="{$selectedShippingFee}" id="shipping-fee">{if $selectedShippingFee}${$selectedShippingFee|number_format:2:".":","}{else}$0.00{/if} </div></div>
                <div class="row tots">
	                <div class="col-xs-4 col-sm-2 col-sm-offset-7 bold text-right">
	                  Total
	                </div>
                <div class="col-xs-8 col-sm-3 bold num text-right" id="total">{assign var='newTotal' value=$selectedShippingFee + $totals.total}<div class="bold tots" id="total">${$newTotal|number_format:2:".":","}</div> <div class="small">AUD (inc. GST)</div></div>         
                </div>
             </div>
            </div>
            <div class="row topborder checkout3" style="display:none;">
            <div class="col-sm-6" id="bill3">
              <span class="bold tots">Billing address</span><br />
              <div id="billing-summary">
              {if $address.B}
                {$address.B.address_name}<br >
                {$address.B.address_line1}<br >
                {$address.B.address_suburb} {$address.B.address_state} {$address.B.address_postcode}<br >
                {$address.B.email}<br >
                {$address.B.address_telephone}<br >
              {/if}
              </div>
              <a href="javascript:void(0);" onclick="goCheckout2('#billing');"><span class="small">Edit</span></a>
            </div>
            <div class="col-sm-6" id="ship3">
              <span class="bold tots">Shipping address</span><br />
              <div id="shipping-summary">
              {if $address.same_address}
                <span class="small">Shipping address same as billing address</span><br />
                {if $comments}Shipping instructions: {$comments}{else}No shipping instructions <br />{/if}
              {else}
                {if $address.S}
                  {$address.S.address_name}<br >
                  {$address.S.address_line1}<br >
                  {$address.S.address_suburb} {$address.B.address_state} {$address.B.address_postcode}<br >
                  {$address.S.address_telephone}<br >
                  {if $comments}Shipping instructions: {$comments}{else}No shipping instructions <br />{/if}
                {/if}
              {/if}
              </div>
              <a href="javascript:void(0);" onclick="goCheckout2('#shipping');"><span class="small">Edit</span></a>
            </div>
            </div>
          </div>
          
          <div class="checkout2">
            <form class="form-horizontal" id="checkout2-form" role="form" accept-charset="UTF-8" action="" method="post">
              <input type="hidden" value="checkout2" name="action" />
              <input type="hidden" value="{$selectedShippingFee}" name="shipFee" id="shippingMethod"/>  
	            <input type="hidden" value="{$selectedShipping}" name="shipMethod" id="shipMethod"/> 
	            <input type="hidden" value="{$postageID}" name="postageID" id="postageID"/> 
              <br />
              <div class="row checkform">
                <div class="col-sm-6" id="billing">
                <h4 class="bold tots">Billing details</h4>
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="name1">Name*:</label>
                    <div class="col-sm-8">
                      <input id="name1" value="{if $address}{$address.B.address_name}{else}{$user.gname} {$user.surname}{/if}" name="address[B][address_name]" type="text" class="billing-req form-control" required="required" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="street">Street address*:</label>
                    <div class="col-sm-8">
                      <input id="street" value="{if $address}{$address.B.address_line1}{/if}" name="address[B][address_line1]" type="text" class="billing-req form-control" required="required"  />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="suburb">Suburb*:</label>
                    <div class="col-sm-8">
                      <input id="suburb" value="{if $address}{$address.B.address_suburb}{else}{$selectedShippingSuburb}{/if}" name="address[B][address_suburb]" type="text" class="billing-req form-control" required="required"  />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="suburb">State*:</label>
                    <div class="col-sm-8">
                      <select id="state" name="address[B][address_state]" class="required form-control">
                        <option value="">Please select</option>
                        {foreach $options_state as $value }
                        <option value="{$value.postcode_state}" {if $address.B.address_state eq $value.postcode_state OR $selectedShippingState eq $value.postcode_state}selected="selected"{/if}>{$value.postcode_state}</option> 
                      {/foreach}              
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="suburb">Postcode*:</label>
                    <div class="col-sm-8">
                      <input id="postcode-field" value="{if $address}{$address.B.address_postcode}{else}{$selectedShippingPostcode}{/if}" name="address[B][address_postcode]" pattern="[0-9]" type="text" class="postcode billing-req form-control" required="required" onPaste="updateShipping();" onBlur="updateShipping();" {literal}onkeydown="if(event.keyCode == 13 || $(this).val().length >= 4){updateShipping();}"{/literal} />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="suburb">Email*:</label>
                    <div class="col-sm-8">
                      <input id="email" value="{if $address}{$address.B.email}{else}{$user.email}{/if}" name="address[B][email]" type="email" class="billing-req form-control" required="required"  />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="suburb">Confirm email*:</label>
                    <div class="col-sm-8">
                      <input id="emailconf" value="{if $address}{$address.B.emailconf}{else}{$user.email}{/if}" name="address[B][emailconf]" type="email" class="billing-req form-control" required="required"  />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="suburb">Phone*:</label>
                    <div class="col-sm-8">
                      <input id="phone" value="{if $address}{$address.B.address_telephone}{/if}" name="address[B][address_telephone]" type="text" class="phone billing-req form-control" required="required"  />
                    </div>
                  </div>
                  <div class="col-sm-4">
                  </div>
                  <div class="col-sm-8">
                    <div class="cbout">
                      <input type="checkbox" id="chksame" name="address[same_address]" {if $address}{if $address.same_address}checked="checked"{/if}{else}checked="checked"{/if} onclick="sameAddress();updateShipping();">
                      <label class="chklab">Ship items to the above billing address.</label>
                    </div>
                    <div class="cbout"><small>Please note we cannot ship to PO boxes.</small></div>
                    <div class="shipbill">
                      <span class="small italic">Item(s) will be shipped to your billing address.</span>
                    </div>
                    <div class="cbout">
                      <input id="promo1" name="address[wantpromo]" type="checkbox" checked="checked" />
                      <label class="chklab">Please send me future promotional material.</label>             
                    </div>
                  </div>
                </div>
                <div class="col-sm-6" id="shipping">
                  <div class="row">
                    <div id="shipping-subform">
                     <h4 class="bold tots">Shipping details</h4>
                      <div class="form-group">
                        <label class="col-sm-4 control-label" for="namesh1">Name*:</label>
                        <div class="col-sm-8">
                          <input id="namesh1" value="{if $address}{$address.S.address_name}{/if}" name="address[S][address_name]" type="text" class="shipping-req form-control" required="required"/>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-4 control-label" for="streetsh">Street address*:</label>
                        <div class="col-sm-8">
                          <input id="streetsh" value="{if $address}{$address.S.address_line1}{/if}" name="address[S][address_line1]" type="text" class="shipping-req form-control" required="required" />
                        </div>
                      </div>
                      <div class="form-group">
                         <label class="col-sm-4 control-label" for="suburbsh">Suburb*:</label>
                        <div class="col-sm-8">
                          <input id="suburbsh" value="{if $address}{$address.S.address_suburb}{/if}" name="address[S][address_suburb]" type="text" class="shipping-req form-control" required="required" />
                        </div>
                      </div>
                      <div class="form-group">
                         <label class="col-sm-4 control-label" for="statesh">State*:</label>
                        <div class="col-sm-8">
                          <select id="statesh" name="address[S][address_state]" class="shipping-select-req required form-control">
                            <option value="">Please select</option>
                            {foreach $options_state as $value }
                            <option value="{$value.postcode_state}" {if $address.S.address_state eq $value.postcode_state}selected="selected"{/if}>{$value.postcode_state}</option> 
                          {/foreach}
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                         <label class="col-sm-4 control-label" for="postcodesh">Postcode*:</label>
                        <div class="col-sm-8">
                          <input id="postcodesh" value="{if $address}{$address.S.address_postcode}{/if}" name="address[S][address_postcode]" pattern="[0-9]" type="text" class="postcode shipping-req form-control" required="required"  onPaste="updateShipping();" onBlur="updateShipping();" {literal}onkeydown="if(event.keyCode == 13 || $(this).val().length >= 4){updateShipping();}"{/literal} />
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-4 control-label" for="phonesh">Phone*:</label>
                        <div class="col-sm-8">
                          <input id="phonesh" value="{if $address}{$address.S.address_telephone}{/if}" name="address[S][address_telephone]" type="text" class="phone shipping-req form-control" required="required" />
                        </div>  
                      </div>    
                    </div>          
                  </div>
                  
                  <div class="row">
                    <div class="col-sm-12">
                      Shipping instructions <small>(max. 128 characters)</small>:
                    </div>
                    <div class="col-sm-12">
                      <textarea id="text" name="comments" id="text" maxlength="128" class="form-control">{if $comments}{$comments}{/if}</textarea>
                    </div>
                  </div>
                </div>
              </div>
          </form>
          <div class="row">
            <div class="error-textbox" id="error-text1"></div>
          </div>
            <div class="row">
            <div class="col-sm-2 col-sm-offset-10 text-right"><a class="red btn" onclick="$('#checkout2-form').submit();">Next</a></div>         
            </div>
          </div>
          <div class="checkout3" style="display:none;">
          
            <form class="form-horizontal" id="checkout3-form" role="form" accept-charset="UTF-8" action="/process/cart" method="post">
              <div class="row">
              <input type="hidden" value="placeOrder" name="action" />
              {if $newTotal gt 0.01 } 
                <div class="col-sm-12 dark">
                  All transactions are secure and encrypted, and we never store your credit card information. To learn more, please view our <a href="/privacy-policy" target="_blank">privacy policy</a>.
                </div>
              {if $error}
                <div class="row" id="payment-error">{$error}</div>
              {/if}
              <div class="col-sm-12">
                <img src="images/mastercard.png" alt="mastercard-icon" />
                <img src="images/visa.png" alt="visa-icon" />
              </div>
               <div class="col-sm-7 margintop15">
                   <div class="form-group">
                     <label class="col-sm-4 control-label" for="ccno">Credit card number*:</label>
                     <div class="col-sm-8">
                       <input type="text" id="ccno" class="cc-req form-control" name="cc[number]" autocomplete="off" />
                     </div>
                   </div>
                   <div class="form-group">
                     <label class="col-sm-4 control-label" for="ccname">Name on card*:</label>
                     <div class="col-sm-8">
                       <input type="text" id="ccname" class="cc-req form-control" name="cc[name]" autocomplete="off" />
                     </div>
                   </div>
                   <div class="form-group">
                    <label class="col-sm-4 control-label" for="ccmonth">Expiration date*:</label>
		                <div class="col-sm-4">
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
                    </div>
	                  <div class="col-sm-4">
	                    <select id="ccyear" name="cc[year]" class="cc-select-req form-control" >
                       {assign var=thisyear value=$smarty.now|date_format:"%Y"} 
                       {assign var=numyears value=$thisyear+20}
                       <option value="">Year</option>
                       {for $year=$thisyear to $numyears}
                         <option value="{$year}">{$year}</option> 
                       {/for}
	                    </select>
	                  </div>
                   </div>
                   <div class="form-group">
                     <label class="col-sm-4 control-label" for="cccsv">CSV*:<br/><span class="small"><a href="/help-with-checkout#csv" target="_blank">What is this?</a></span></label>
                     <div class="col-sm-8">
                       <input type="text" id="cccsv" name="cc[csv]" class="cc-req form-control" autocomplete="off" />
                     </div>
                   </div>
               </div>
              {else}
                <div class="col-sm-12">
                  No payment details are required.
                </div>
                {if $error}
                  <div class="row" id="payment-error">{$error}</div>
                {/if}
              {/if}
              </div>
              <div class="row form-group">
                <div id="chk-tickbox" class="col-sm-1 col-sm-offset-2 text-right">
                  <input type="checkbox" id="accept" name="accept" required/>
                </div>
                <label class="col-sm-8" class="chklab form-control txt-left" for="checkbox">
                  I have read the <a href="/privacy-policy" target="_blank">Privacy Policy</a> and I understand and agree to the <a href="/shipping-and-delivery" target="_blank">Shipping & Delivery Policy</a>, <a href="/returns-policy" target="_blank">Return Policy</a>, <a href="/warranty-and-support" target="_blank">Warranty & Support Policy</a> and the web site <a href="/terms-and-conditions" target="_blank">Terms and Conditions</a>.
                </label>
              </div>
              <div class="error-textbox" id="error-text2"></div>
              <div class="row">
				        <div class="col-sm-4 col-sm-offset-8 text-right"><a class="red btn" onclick="$('#checkout3-form').submit();">Complete Checkout</a></div>
				        <div class="col-sm-4 col-sm-offset-8 num text-right">
                  <span class="small">Your payment will be processed.</span>
                </div> 
				      </div>
            </form>
            </div>
          </div>
        </div>
      </div>
  </div>
{/block}

{* Place additional javascript here so that it runs after General JS includes *}
{block name=tail}
<script type="text/javascript">
  $(document).ready(function(){
    
    $('#checkout2-form').validate();
    $('#checkout3-form').validate(); 
    
    sameAddress();
      
    $('#emailconf').rules("add", {
          required: true,
          equalTo: '#email',
          messages: {
            equalTo: "The emails you have entered do not match. Please check them."
          }
    }); 

    {if $newTotal gt 0.01 } 

    $('#ccno').rules("add", {
      creditcard : true,
      messages : {
        equalTo : "Number not valid."
      }
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