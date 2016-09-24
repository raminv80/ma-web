{block name=body}

<div id="carttop">
	<div class="container">
		<div class="row hidden-xs">
			<div class="col-xs-4 col-sm-4 text-center">
				<span class="bold">1. Cart</span>
			</div>
			<div class="col-xs-4 col-sm-4 text-center">
				2. Delivery
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
				Step 2
			</div>
			<div class="col-xs-4 col-sm-4 text-center">
				Step 3
			</div>
		</div>
	</div>
</div>

<div id="maincart">
  <div class="container">
    <div class="row">
	  <div class="col-sm-12 col-md-10 col-md-offset-1">
		  <img src="/images/cart-ad.jpg" class="img-responsive hidden-xs" alt="Stay protected every step you take" />
		  <img src="/images/cart-admob.jpg" class="img-responsive visible-xs" alt="Stay protected every step you take" />
	  </div>
      <div class="col-sm-12 col-md-10 col-md-offset-1 text-center" id="checkout">
        <h1>Your cart</h1>
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

<div id="cartmain">
	<div class="container">
        {if $productsOnCart }

        <div class="row headings">
          <div class="col-sm-5 col-md-6">Items</div>
          <div class="hidden-xs col-xs-3 col-sm-2 text-center">Price</div>
          <div class="hidden-xs col-xs-3 col-sm-2 col-md-1 text-center">Quantity</div>
          <div class="hidden-xs col-xs-3 col-sm-2 text-center">Total</div>
          <div class="visible-xs col-xs-3"></div>
        </div>

        <form class="form-horizontal" id="shopping-cart-form" accept-charset="UTF-8" action="" method="post">
          <div class="row tallbox">
            {foreach from=$productsOnCart item=item}
            <div id="{$item.cartitem_id}" class="prod-item cartitem">
              <div class="col-xs-3 col-sm-2 col-md-3 text-center">
                <img class="img-responsive" src="{if $item.gallery.0.gallery_link neq ''}{$item.gallery.0.gallery_link}?width=120&height=76&crop=1{else}/images/no-image-available.jpg?width=120&height=76&crop=1{/if}" alt="{$item.gallery.0.gallery_alt_tag}" title="{$item.gallery.0.gallery_title}" />
              </div>
              <div class="col-xs-9 col-sm-3 bluetext valgn">
                <a href="{if $item.cartitem_type_id eq 1}/{$item.product_url}{else}javascript:void(0){/if}">{$item.cartitem_product_name}</a>{if $item.cartitem_product_gst eq '1'} *{/if}
                {if $item.cartitem_type_id eq 1}<br><small>{$item.cartitem_product_uid}</small>{/if}
                {if $item.attributes &&  $item.cartitem_type_id neq 2} 
                {foreach from=$item.attributes item=attr}
                <div class="attributes">{$attr.cartitem_attr_attribute_name}: {$attr.cartitem_attr_attr_value_name}</div>
                {if $attr.cartitem_attr_attr_value_additional} <a class="eng" href="javascript:void(0)" onclick="if($('.addattr{$attr.cartitem_attr_id}').is(':visible')){ $('.addattr{$attr.cartitem_attr_id}').hide('slow'); $(this).html('Show engraving +'); }else{ $('.addattr{$attr.cartitem_attr_id}').show('slow'); $(this).html('Hide engraving  -'); }">Show engraving +</a> {foreach $attr.cartitem_attr_attr_value_additional|json_decode as $k => $v}
                <div class="additional-attributes addattr{$attr.cartitem_attr_id}" style="display: none;">Line {$k}: {$v}</div>
                {/foreach} {/if} {/foreach} {/if}
              </div>
              <div class="visible-xs col-xs-3"></div>
              <div class="hidden-xs col-xs-9 col-sm-2 text-center valgn" id="priceunit-{$item.cartitem_id}">${$item.cartitem_product_price|number_format:2:".":","}</div>
              <div class="col-xs-9 col-xs-offset-3 col-sm-2 col-sm-offset-0 col-md-1 quant text-center valgn mobl">
                <select id="quantity" class="quantity" name="qty[{$item.cartitem_id}]">
                  {assign var='max' value=10} {if $max lt $item.cartitem_quantity} {assign var='max' value=$item.cartitem_quantity} {/if} {for $opt=1 to $max}
                  <option value="{$opt}" {if $item.cartitem_quantity eq $opt}selected{/if}>{$opt}</option>
                  {/for}
                </select>
                <div id="qty-discount-{$item.cartitem_id}">{if $item.productqty_modifier.productqty_modifier neq 0}(-{$item.productqty_modifier.productqty_modifier|number_format:2:".":','|rtrim:'0'|rtrim:'.'}%){/if}</div>
              </div>
              <div class="col-xs-6 col-xs-offset-3 col-sm-2 col-sm-offset-0 text-center valgn mobl" id="subtotal-{$item.cartitem_id}">${$item.cartitem_subtotal|number_format:2:".":","}</div>
              <div class="col-xs-3 col-sm-1 text-center valgn">
                <a class="hidden-xs hidden-sm remove" href="javascript:void(0)" onclick="deleteItem('{$item.cartitem_id}');">Remove</a>
                <a class="remove visible-xs visible-sm" href="javascript:void(0)" onclick="deleteItem('{$item.cartitem_id}');"><img src="/images/cart-bin.png" alt="Remove" title="Remove" /></a>
              </div>
            </div>
            {/foreach}
          </div>
        </form>

{if $showProductEspecial}
        <div class="row" id="special">
	        <div class="col-md-3 nopad">
				<img src="/images/cart-ad2.jpg" class="img-responsive" alt="Special" />
	        </div>
	        <div class="col-sm-9 col-md-7">
		        <h3>Receive an additional sports band or stainless steel product of your choice for only $35*</h3>
				<div class="condn">*Offer only applies to one additional medical ID, must be equal or lesser value.</div>
	        </div>
	        <div class="col-sm-3 col-md-2" id="select">
				<a href="/products" title="Select your additional product" class="btn btn-red">Select your <br /> product</a>
	        </div>
        </div>
{/if}
        <div class="row" id="help">
	        <div class="col-sm-12 col-md-8 helpl">
			  <form class="form-horizontal" id="product-form" role="form" accept-charset="UTF-8" action="" method="post">
                <input type="hidden" value="ADDTOCART" name="action" id="action" />
                <input type="hidden" name="formToken" id="formToken" value="{$token}" />
                <input type="hidden" value="{$products.product_object_id}" name="product_id" id="product_id" />
                
                <h3>Help us to help others</h3>
				<p>While you’re here, why not make a small donation to our not-for-profit organisation? Just a few dollars can help provide our life-saving service, and allow us to educate Australians about the importance of MedicAlert Foundation. Donations over $2 are tax deductible. </p>

				<h4>Select a donation amount:</h4>
				{foreach $products.variants as $v}
				<div class="donbtn">
				  	<label for="variant-{$v.variant_id}">
	                    <input type="radio" value="{$v.variant_id}" data-value="{$v.variant_price|number_format:0:'.':','}" class="{if $v.variant_editableprice eq 1}show-otherval{/if}" name="variant_id" id="variant-{$v.variant_id}">
                        <input type="hidden" disabled value="{$v.attr_value_id}" name="attr[{$v.attribute_id}][id]" id="attribute_id-{$v.variant_id}" class="variant-attributes"/>
                    	<div id="variant-{$v.variant_id}-btn" class="donate-btn btn btn-grey">{if $v.variant_editableprice eq 1}Other{else}${$v.variant_price|number_format:0:'.':','}{/if}</div>
                    </label>
                </div>
                {/foreach}
                <div class="clearl" id="othervalout">
                  <div class="form-group">
                    <input type="text" id="otherval" name="price" placeholder="Please specify an amount" required />
                  <div class="help-block text-center small">Please only specify a whole dollar amount.</div>
                  </div>
                </div>
				<div class="clearl">
                    <button id="prod-submit-btn" type="submit" disabled class="btn btn-red">Add to cart</button>
				</div>
                </form>
	        </div>
	        <div class="col-sm-4 hidden-xs hidden-sm helpr">
				<img src="/images/cart-girls.png" class="img-responsive" alt="Help us to help others" />
	        </div>
        </div>
        <br />
        <div class="row" id="cartprice">
		      <hr />
          <div class="col-sm-12">
            <div class="row tallrow" >
			<div class="col-sm-6 col-md-4" id="disccode">
				<div class="row">
	              <form class="form-horizontal" id="discount-form" accept-charset="UTF-8" action="/process/cart" method="post">
	                <input type="hidden" value="applyDiscount" name="action" id="action" />
	                <div class="col-xs-12 col-sm-12 text-left"><h4>Discount code</h4></div>
	                <div class="col-xs-12 form-group">
	                  <input class="form-control" type="text" placeholder="ENTER CODE" id="promo" value="{if $post}{$post.discount_code}{else}{$cart.cart_discount_code}{/if}" name="discount_code" required/>
	                  <div class="error-msg help-block"></div>
                      <div>
	                    {if $error}<span class="invalid">{$error}</span>{else}<small><b>{$cart.cart_discount_description}</b></small>{/if}
	                  </div>
	                </div>
	                <div class="col-xs-12">
	                  <a href="javascript:void(0);" class="btn-red btn" onclick="$('#discount-form').submit();">Apply</a>
	                </div>
	                <div class="col-xs-6 col-sm-offset-4 col-md-offset-0 col-sm-5 col-md-4 shopping-label"></div>
	              </form>
				</div>
			</div>
			<div class="col-sm-6 col-md-8">
	            <div class="row tallrow">
	              <div class="col-xs-5 col-sm-8 col-md-10 shopping-label text-right mobl">Subtotal</div>
	              <div class="col-xs-7 col-sm-4 col-md-2 num text-right mobr" id="subtotal" data-value="{$totals.subtotal}">${$totals.subtotal|number_format:2:".":","}</div>
	            </div>
	            
	            <div class="row tallrow"{if $totals.discount eq 0} style="display:none"{/if}>
	              <div class="col-xs-5 col-sm-8 col-md-10 shopping-label text-right mobl"><b>Discount</b></div>
	              <div class="col-xs-7 col-sm-4 col-md-2 num text-right mobr" id="discount" data-value="{$totals.discount}">{if $totals.discount gt 0}<b>-${$totals.discount|number_format:2:".":","}</b>{else} $0.00 {/if}</div>
	            </div>
				
	            <!-- SHIPPING -->
	            <div class="row tallrow">
	              <form class="form-horizontal" id="checkout1-form" accept-charset="UTF-8" action="/process/cart" method="post">
	                <input type="hidden" value="checkout1" name="action" id="action" />
	              {foreach $shippingMethods as $k => $v}
	                <input type="hidden" value="{$k}" data-value="{$v}" name="selectedMethod" id="shippingMethod" />
	                <div class="col-xs-5 col-sm-8 col-md-10 shopping-label text-right  mobl">{$k} <img src="/images/question-mark.png" alt="Please allow approximately 20 working days to receive your order." title="Please allow approximately 20 working days to receive your order." data-toggle="tooltip" data-placement="top" /> </div>
	                <div class="col-xs-7 col-sm-4 col-md-2 num text-right mobr">${$v|number_format:2:".":","}</div>
	                {break}
	              {/foreach}
	              </form>
	            </div>

	            <div class="row tallrow">
	              <div class="col-xs-5 col-sm-8 col-md-10 shopping-label text-right  mobl">GST inc. in total<br /><span class="subj">(*Subject to GST)</span></div>
	              <div class="col-xs-7 col-sm-4 col-md-2 num text-right mobr" id="GST_Taxable">${$totals.GST_Taxable|number_format:2:".":","}</div>
	            </div>

	            <div class="row tots tallrow"><!-- The following SUBTOTAL value was intentionally changed to TOTAL  -->
	              <div class="col-xs-5 col-sm-8 col-md-10 shopping-label bold text-right mobl" id="totall">Total</div>
	              <div class="col-xs-7 col-sm-4 col-md-2 bold num text-right mobr" id="total"></div>
	            </div>


			</div>
            </div>

            {* REMOVED - NOT APPLICABLE FOR MAF
            <div class="row tallrow">
              <div class="col-xs-12 col-sm-4 col-sm-offset-5 col-md-offset-7 col-md-5 shopping-label">
                <form class="form-horizontal" id="checkout1-form" accept-charset="UTF-8" action="/process/cart" method="post">
                  <input type="hidden" value="checkout1" name="action" id="action" />
                  <input type="hidden" value="{$selectedShippingFee}" name="shipFee" id="shippingMethod" />
                  <input type="hidden" value="{$selectedShipping}" name="shipMethod" id="shipMethod" />
                  <input type="hidden" value="{$postageID}" name="postageID" id="postageID" />
                  <div class="row form-group">
                    <div class="col-xs-12 col-sm-12">
                      <label for="postcode-field" class="control-label">Postage</label>
                    </div>
                    <div class="col-xs-5 col-sm-5">
                      <input id="postcode-field" name="postcodefield" class="zipcode form-control" type="text" value="{$shippostcode}" placeholder="postcode" required onPaste="updateShipping();" {literal}onblur="if($(this).val().length >= 4){updateShipping();}" onkeydown="if(event.keyCode == 13 || $(this).val().length >= 4){updateShipping();}" {/literal}/>
                    </div>
                    <div class="col-xs-7 col-sm-2 col-sm-offset-5 num text-right" id="shipping-fee">$-unknown-</div>
                  </div>
                </form>
              </div>
            </div>
            *}

          </div>
        </div>
        <div class="row">
          <div class="col-sm-12 text-right">
            <div class="postcode-invalid text-danger"></div>
          </div>
          <div class="col-sm-6 col-md-3 col-sm-offset-6 col-md-offset-9 text-center">
            <button class="btn btn-red process-cnt" type="button" onclick="$('#checkout1-form').submit();">Continue</button>
          </div>
          <div class="col-sm-6 col-md-3 col-sm-offset-6 col-md-offset-9 text-center" id="belowbtn">
            Please note that the MedicAlert membership fee of {$CONFIG_VARS.membership_fee} will be added to your cart if you are joining or you renewal is due.<br />See the <a href="#">benefits of membership</a>.
          </div>
        </div>
        <br />
        <br />
        {else}
        <div>
          <h3>Your shopping cart is empty.</h3>
        </div>
        {/if}
      </div>
    </div>


{/block} {* Place additional javascript here so that it runs after General JS includes *} {block name=tail}
<script src="/includes/js/jquery-ui.js"></script>
<script type="text/javascript" src="/includes/js/jquery.selectBoxIt.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("select").selectBoxIt();
    
    $('[data-toggle="tooltip"]').tooltip();
    
    calculateTotal();
    
    
    $('#checkout1-form').validate({
      onkeyup: false,
      onclick: false
    });
    
    //DONATIONS FORM
    $('#product-form').validate({
      submitHandler: function(form) {
        addCart($(form).attr('id'), true);
      }
    });
    
    $('#otherval').rules("add", {
      required: true,
      digits: true,
      max: 1000
    });
    
    $('#discount-form').validate();
    
    if($('#postcode-field').val()){
      updateShipping();
    }
    
    var keyStop = {
      8: ":not(input:text, textarea, input:file, input:password)", // stop backspace = back
      13: "input:text, input:password", // stop enter = submit
      end: null
    };
    $(document).bind("keydown", function(event) {
      var selector = keyStop[event.which];
      
      if(selector !== undefined && $(event.target).is(selector)){
        event.preventDefault(); //stop event
      }
      return true;
    });
    
    
    $('input[name="variant_id"]').change(function(){
      $('.donate-btn').removeClass('active'); 
      $('#prod-submit-btn').removeAttr('disabled');
      
      //Set attribute
      $('.variant-attributes').attr('disabled', 'disabled');
      $('#attribute_id-' + $(this).val()).removeAttr('disabled');
      
      //Show/hide/highligth content based on selection
      $('#variant-' + $(this).val() + '-btn').addClass('active');
      if($('#variant-' + $(this).val()).hasClass('show-otherval')){
        $('#othervalout').fadeIn('slow');
        $('#otherval').val('');
      }else{
        $('#othervalout').hide();
        $('#otherval').val( $('#variant-' + $(this).val()).attr('data-value') );
      }
    });
  });
</script>
{/block}

