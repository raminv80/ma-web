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
		<div class="row">
		{foreach $gallery as $image }
			<img src="{$image.gallery_link}" title="{$image.gallery_title}" alt="{$image.gallery_alt_tag}">
		{/foreach}
		</div>
		<div class="row">Description: {$product_description}</div>
		<div class="row" style="margin:40px;">
		<form class="form-horizontal" id="product-form" role="form" accept-charset="UTF-8" action="" method="post">
			
			<input type="hidden" value="ADDTOCART" name="action" id="action" /> 
			<input type="hidden" name="formToken" id="formToken" value="{$token}" />
			<input type="hidden" value="{$product_id}" name="product_id" id="product_id" /> 
						
				{foreach $attribute as $attr }
					{if $attr.attr_value}
					<div class="form-group">
						<label for="address_state_1" class="col-sm-2 control-label">{$attr.attribute_name}:</label>
						<div class="col-sm-3">
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
					<div class="col-sm-offset-2">
						{if $product_specialprice eq '0.00'}
							<div style="display:inline;">Price: $</div>
							<div style="display:inline;" id="cal-price" value="{$product_price}">{$product_price|number_format:2:'.':','}</div>
						{else}
							<div style="display:inline;text-decoration: line-through;">Price: $</div>
							<div style="display:inline;text-decoration: line-through;">{$product_price|number_format:2:'.':','}</div>
							<div style="display:inline;color:#FF4822">Special Price: $</div>
							<div style="display:inline;color:#FF4822" id="cal-price" value="{$product_specialprice}">{$product_specialprice|number_format:2:'.':','}</div>
						{/if}
						<div style="display:inline;"><input type="hidden" value="{$product_price}" name="price" id="price" /> </div>
						{if $product_gst}
						<div style="display:inline;color:#aaa"><small>(Incl. GST)</small></div>
						{else}
						<div style="display:inline;color:#aaa"><small>(GST-free)</small></div>
						{/if}
					</div>
				</div>
				
				{if $product_instock}  
					<div class="form-group">
						<label for="address_state_1" class="col-sm-2 control-label">Qty: </label>
						<div class="col-sm-2">
							<input type="text" value="1" name="quantity" id="quantity" class="form-control unsigned-int gt-zero" pattern="[0-9]" >
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-10 col-sm-offset-2">
							<a class="btn-primary btn" onclick="$('#product-form').submit();">Add to Cart</a>
							<div style="display:inline;color:#ff0000" id="error-text"></div>
						</div>
					</div>
				{else}
					<div class="form-group">
						<div style="display:inline;color:#ff0000">Out of stock</div>
					</div>
				{/if}
				
		</form>		
						
		</div>
		
		
		

	</div>

	<script type="text/javascript" src="/includes/js/shopping-cart.js"></script>
	
	<script type="text/javascript">
	if (jQuery.validator) {
		  jQuery.validator.setDefaults({
		    debug: true,
		    errorClass: 'has-error',
		    validClass: 'has-success',
		    ignore: "",
		    highlight: function (element, errorClass, validClass) {
		      $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		      $('#error-text').html('Error, please check the red highlighted fields and submit again.');
		    },
		    unhighlight: function (element, errorClass, validClass) {
		      $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
		      $(element).closest('.form-group').find('.help-block').text('');
		    },
		    errorPlacement: function (error, element) {
		      $(element).closest('.form-group').find('.help-block').text(error.text());
		    },
		    submitHandler: function (form) {
		      if ($(form).valid()) {
		          addCart();
		      }
		    }
		  });
		}

		$(document).ready(function(){

			$('#product-form').validate();
			
			{if $attribute}
				{foreach $attribute as $attr }
					var {urlencode data=$attr.attribute_name} = getParameterByName('{urlencode data=$attr.attribute_name}');
	
					$("#{urlencode data=$attr.attribute_name} option[name*='"+ {urlencode data=$attr.attribute_name} +"']").attr("selected", "selected"); 
				{/foreach}
			{/if}
			calculatePrice();
			
		});
	
		function getParameterByName(name) {
		    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
		    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
		        results = regex.exec(location.search);
		    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
		}
		
		
	</script>
	
{/block}
