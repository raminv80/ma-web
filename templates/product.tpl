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
			<div class="col-xs-3">
				<div class="image">
					<img src="{$image.gallery_link}" title="{$image.gallery_title}" alt="{$image.gallery_alt_tag}">
				</div>
			</div>
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
		{if $associated_products}
			<div class="row col-sm-5" style="margin: 40px; border: 1px solid rgb(170, 170, 170); padding: 19px;">
				<h4>Related products</h4>
				{foreach from=$associated_products key=k item=it}
					<div class="row">
						<div class="col-xs-3">
							<div class="image">
								<img src="{$it.gallery.0.gallery_link}" alt="{$it.gallery.0.gallery_alt_tag}" title="{$it.gallery.0.gallery_title}" style="width:90px; height:90px;"/>
							</div>
						</div>
						<div class="col-xs-9">
							<div class="col-xs-12">{$it.product_name}</div>
							
							{if $it.attribute} 
								{if $it.product_specialprice eq '0.00'}
									{assign var='lowest' value=$it.product_price}
									{foreach $it.attribute key=katt item=itatt name=attr}
										{foreach $itatt.attr_value key=kattval item=itattval name=attr_val}
											{if $smarty.foreach.attr_val.first}
												{assign var='lowest_attr' value=$itattval.attr_value_price}
											{else}
												{if $itattval.attr_value_price lt $lowest_attr}
													{assign var='lowest_attr' value=$itattval.attr_value_price}
												{/if}
											{/if}
										{/foreach} 
										{assign var="lowest" value=$lowest_attr+$lowest}
									{/foreach} 
									<div class="col-xs-12">from ${$lowest|number_format:2:'.':','}</div>
								{else}
									{assign var='lowest' value=$it.product_specialprice}
									{foreach $it.attribute key=katt item=itatt name=attr}
										{foreach $itatt.attr_value key=kattval item=itattval name=attr_val}
											{if $smarty.foreach.attr_val.first}
												{assign var='lowest_attr' value=$itattval.attr_value_specialprice}
											{else}
												{if $itattval.attr_value_specialprice lt $lowest_attr}
													{assign var='lowest_attr' value=$itattval.attr_value_specialprice}
												{/if}
											{/if}
										{/foreach} 
										{assign var="lowest" value=$lowest_attr+$lowest}
									{/foreach} 
									<div class="col-xs-12" style="color:#FF4822">Special Price from ${$lowest|number_format:2:'.':','}</div>
								{/if} 
							{else}
								{if $it.product_specialprice eq '0.00'}
									<div class="col-xs-12">${$it.product_price|number_format:2:'.':','}</div>
								{else}
									<div class="col-xs-12" style="text-decoration: line-through;">${$it.product_price|number_format:2:'.':','}</div>
									<div class="col-xs-12" style="color:#FF4822">Special Price: ${$it.product_specialprice|number_format:2:'.':','}</div>
								{/if}
							{/if}
							<div class="col-xs-12">{$it.product_description}</div>
							<div class="col-xs-12"><a href="/{$it.absolute_url}" class="btn btn-info">View product</a></div>
						</div>
					</div>
				{/foreach}
			</div>
		{/if}
	</div>

	<script type="text/javascript">


		$(document).ready(function(){

			$('#product-form').validate({
				onkeyup: false,
				onclick: false
			});
			
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
