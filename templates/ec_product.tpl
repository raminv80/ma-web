{block name=body}

<div id="maincont">
  <div class="container">
    <div class="row" id="productout">
      <div class="col-sm-6" id="prodleft">

        <div id="back">
          <a href="/products"> < Back to product page
          </a>
        </div>

        <div id="prodslider" >
          <div class="carousel-inner" >
            {assign var='count' value=0} {foreach $gallery as $image }
            <div class="item {if $count eq 0}active{/if}">
              <img src="{$image.gallery_link}?height=160" title="{$image.gallery_title}" alt="{$image.gallery_alt_tag}" class="img-responsive">
            </div>
            {assign var='count' value=$count+1} {/foreach} {if $count eq 0}
            <div class="item {if $count eq 0}active{/if}">
              <img src="/images/no-image-available.jpg?height=160" title="Placeholder" alt="Placeholder" class="img-responsive">
            </div>
            {/if}
          </div>

        </div>
      </div>
      <div class="col-sm-6" id="prodright">
        <div class="catname">{$listing_parent.listing_name}</div>
        <h2>{$product_name}</h2>
        <form class="form-horizontal" id="product-form" role="form" accept-charset="UTF-8" action="" method="post">
          <input type="hidden" value="ADDTOCART" name="action" id="action" />
          <input type="hidden" name="formToken" id="formToken" value="{$token}" />
          <input type="hidden" value="{$product_object_id}" name="product_id" id="product_id" />
          <input type="hidden" value="{$listing_parent.listing_object_id}" name="listing_id" id="listing_id" />
          
          <div class="form-group">
            <div class="prodprice col-sm-12">
              <input type="hidden" value="0" name="price" id="price" />
              <input type="hidden" value="0" name="variant_id" id="variant_id" />
              {foreach $variants as $variant}
                {$price = $variant.variant_price}
                {if $variant.variant_specialprice gt 0}{$price = $variant.variant_specialprice}{/if}
                {if $user.id && $variant.variant_membersprice gt 0}{$price = $variant.variant_membersprice}{/if}
                <div class="variant-prices variants" id="variant-{$variant.variant_id}" style="display:none">
                  {if $user.id && $variant.variant_membersprice gt 0}
                    <div>$<span>{$variant.variant_price|number_format:0:'.':','}</span></div>
                    <div><b>Members Price:</b> $<span class="selected-price" data-value="{$variant.variant_membersprice}">{$variant.variant_membersprice|number_format:0:'.':','}</span></div>
                  {elseif $variant.variant_specialprice gt 0}
                    <div>$<span>{$variant.variant_price|number_format:0:'.':','}</span></div>
                    <div><b>Special Price:</b> $<span class="selected-price" data-value="{$variant.variant_specialprice}">{$variant.variant_specialprice|number_format:0:'.':','}</span></div>
                  {else}
                    <div>$<span class="selected-price" data-value="{$variant.variant_price}">{$variant.variant_price|number_format:0:'.':','}</span></div>
                  {/if}
                </div>
              {/foreach}
              <div class="variant-prices" id="variant-">
                  <div>${$general_details.price.min|number_format:0:'.':','}{if $general_details.price.min neq $general_details.price.max} - ${$general_details.price.max|number_format:0:'.':','}{/if}</div>
              </div>
            </div>
          </div>
          {* CREATE ARRAY OF ATTRIBUTES *}
          {$prdattrValuesArr = []}
          {$prdattrArr = []}
          {foreach $attributes as $attr} 
            {foreach $attr.values as $value}
              {foreach $variants as $variant}
                {foreach $variant.productattributes as $prdattr}
                  {if $prdattr.productattr_attr_value_id eq $value.attr_value_id}
                    {if !$prdattrValuesArr[$attr.attribute_id][$value.attr_value_id]['attr_value_id']}
                      {$prdattrValuesArr[$attr.attribute_id][$value.attr_value_id] = $value}
                      {* HACK FOR MAF TO CHANGE THE COLOUR IMAGE *}
                        {foreach $gallery as $g}
                          {if $g.gallery_variant_id eq $variant.variant_id}{$prdattrValuesArr[$attr.attribute_id][$value.attr_value_id]['attr_value_image'] = $g.gallery_link}{break}{/if}
                        {/foreach}
                      {* END OF HACK *}
                    {/if}
                    {$prdattrValuesArr[$attr.attribute_id][$value.attr_value_id]['variants'][] = $variant.variant_id}
                    {$prdattrArr[$attr.attribute_id][] = $variant.variant_id}  
                  {/if}
                {/foreach}
              {/foreach}
            {/foreach}
          {/foreach}
          <input type="hidden" value="{count($prdattrValuesArr)}" name="unused_field" id="attr_cnt" />
          
          
          {foreach $attributes as $attr} 
          <div class="form-group">
            <div class="col-sm-12">
              <label for="{urlencode data=$attr.attribute_name}" class="control-label">{$attr.attribute_name}:</label>
              {if $attr.attribute_type eq 1}
              <div class="attrtype1_name">Select one</div>
                {foreach $prdattrValuesArr[$attr.attribute_id] as $value}
                  <div class="attrtype1">
                    <input type="radio" onclick="$(this).closest('.form-group').find('.attrtype1_name').html($(this).attr('data-name'));" data-name="{$value.attr_value_name}" class="mainAttr hasAttr updateprice{foreach $value.variants as $vr} variant-{$vr}{/foreach}" value="{$value.attr_value_id}" name="attr[{$attr.attribute_id}][id]" id="{urlencode data=$attr.attribute_name|cat:'_'|cat:$value.attr_value_name}" required="required">
                    <label for="{urlencode data=$attr.attribute_name|cat:'_'|cat:$value.attr_value_name}"><img src="{$value.attr_value_image}?width=50&height=50&crop=1" title="{$value.attr_value_name}" alt="{$value.attr_value_name}"></label>
                  </div>
                {/foreach}
              {else}
              <select id="{urlencode data=$attr.attribute_name}" name="attr[{$attr.attribute_id}][id]" class="form-control required notMainAttr hasAttr{if $attr.attribute_type eq 2} hasAdditional{/if}" required="required">
                <option value="" class="defaultAttr updateprice{foreach $prdattrArr[$attr.attribute_id] as $vr} variant-{$vr}{/foreach}" >Select one</option>
                {foreach $prdattrValuesArr[$attr.attribute_id] as $value}
                  <option value="{$value.attr_value_id}" class="updateprice{foreach $value.variants as $vr} variant-{$vr}{/foreach}">{$value.attr_value_name}</option>
                {/foreach}
              </select>
                {if $attr.attribute_type eq 2}
                  {foreach $prdattrValuesArr[$attr.attribute_id] as $value}
                  <div class="additionals" id="additional-{$value.attr_value_id}" style="display:none;">
                    <div>{$value.attr_value_description}</div>
                    {for $var=1 to 7}
                      {$varName = 'attr_value_var'|cat:$var}
                      {if $value[$varName] gt 0}
                        <label class="control-label" for="additional-{$attr.attribute_id}-{$var}">Line {$var}:</label>
                        <input class="form-control" maxlength="{$value[$varName]}" name="attr[{$attr.attribute_id}][additional][{$var}]" id="additional-{$value.attr_value_id}-{$var}">
                      {/if}
                    {/for}
                    </div>
                  {/foreach}
                {/if}
              {/if}
            </div>
          </div>
          {/foreach}
         
          <div class="form-group">
  	        <div class="prodprice col-sm-12">
              <div style="display: inline; color: #ff0000" id="error-text"></div>
              {foreach $variants as $variant}
                <div class="variant-panels" id="variant-{$variant.variant_id}-panel" style="display:none">
                {if $variant.variant_instock eq 1}
                  <!--<div class="form-group">
                    <label for="address_state_1" class="col-sm-2 control-label">Qty: </label>
                    <div class="col-sm-2">
                      <input type="text" value="1" name="quantity" id="quantity" class="form-control unsigned-int gt-zero" pattern="[0-9]" >
                    </div>
                  </div>-->
                    <div>
                      <button class="btn" type="submit">Add to Cart</button>
                    </div>
                {else}
                  <div style="display: inline; color: #ff0000">Out of stock</div>
                {/if}
                </div>
              {/foreach}
            </div>
          </div>
        </form>
        
        {if $product_description}
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
              <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Description</a>
              </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body">{$product_description}</div>
            </div>
          </div>
          {/if} {if $pwarranty_description}
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo">
              <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Warranty</a>
              </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
              <div class="panel-body">{$pwarranty_description}</div>
            </div>
          </div>
          {/if} {if count($pcarelinks) gt 0}
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingThree">
              <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">Care &amp; cleaning</a>
              </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
              <div class="panel-body">{foreach $pcarelinks as $care}{$care.pcare_description}{/foreach}</div>
            </div>
          </div>
          {/if} {if $pdelivery_description}
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFour">
              <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">Delivery &amp; returns</a>
              </h4>
            </div>
            <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
              <div class="panel-body">{$pdelivery_description}</div>
            </div>
          </div>
          {/if}



        </div>
      </div>
    </div>
  </div>
</div>

{if $associated_products}
<div id="related">Members also pruchased</div>
{/if} 

{/block} 

{block name=tail}
<script type="text/javascript">

	$(document).ready(function(){

			$('#product-form').validate();

			var attributes = [];
			{if $attribute}
				{foreach $attribute as $attr }
					var {urlencode data=$attr.attribute_name} = getParameterByName('{urlencode data=$attr.attribute_name}');
					attributes.push( getParameterByName('{urlencode data=$attr.attribute_name}') );

					$("#{urlencode data=$attr.attribute_name} option[name*='"+ {urlencode data=$attr.attribute_name} +"']").attr("selected", "selected");
				{/foreach}
			{/if}
			
			$('.hasAttr').change(function(){
			  BlockAttrOptions(this);
			  DisplayPrice();
			});
			
			$('select.hasAttr').focus(function(){
			  UnblockSelectOptions(this);
			});
			
			$('select.hasAdditional').change(function(){
			  SetAdditionals($(this).val());
			});
			
			
			//calculatePrice();

			/* ga('ec:addProduct', {
	 	    'id': '{$product_object_id}',
	 	    'name': '{$product_name}',
	 	    'category': '{$product_FullCategoryName}',
	 	    'brand': '{$product_brand}',
	 	    'variant': attributes.join('/')
	 	  });
	 	  ga('ec:setAction', 'detail'); */

		});

	
	function UnblockSelectOptions(ELEMENT){
	  if($(ELEMENT).val() == ''){
	    return false;
	  }
	  
	  var selArr = [];
	  var defaultOptions = $(ELEMENT).find('option.defaultAttr').attr('class').replace(/defaultAttr|updateprice/g, '').trim().split(/\s+/);
	  $(ELEMENT).find('option').addClass('ignoreOption');
	  if(defaultOptions){
	    selArr.push(defaultOptions);
	  }
	 
	  //Check all selected attributes
	  $('.updateprice:selected, .updateprice:checked').each(function(){
	    if(!$(this).hasClass('ignoreOption')){
	      selArr.push($(this).attr('class').replace(/mainAttr|notMainAttr|defaultAttr|hasAttr|updateprice|hasAdditional/g, '').trim().split(/\s+/));
	    }
	  });
	  if(selArr){
    	  classes = getIntersectionArray(selArr);
	  }
	  $(ELEMENT).find('option').removeClass('ignoreOption');
	  
	  //Enable attribute-options for each selected class
	  $.each(classes, function(ckey, cval){
	    $(ELEMENT).find('option.'+cval).each(function(){
		  $(this).removeAttr('disabled');
	 	});		    
	  });
	  
	}
	
	
	function BlockAttrOptions(ELEMENT){
	  //Get all enabled attributes of the current list
	  if($(ELEMENT).is('select')){
	    if($(ELEMENT).val == ''){
	      $(ELEMENT).find('option').addClass('ignoreblock');
	    }else{
	      $(ELEMENT).find('option:enabled').addClass('ignoreblock');  
	    }
	  }else{
	    var name = $(ELEMENT).attr('name');
	    $('.updateprice[name="'+name+'"]').find('option:enabled').addClass('ignoreblock');
	  }
	  
	  //Clear all selections when main-attribute 
	  if($(ELEMENT).hasClass('mainAttr')){
	    $('.notMainAttr').val('');
	    SetAdditionals();
	  }
	  
	  //Check all selected attributes
	  var selArr = [];
	  $('.updateprice:selected, .updateprice:checked').each(function(){
	    selArr.push($(this).attr('class').replace(/mainAttr|notMainAttr|defaultAttr|hasAttr|updateprice|hasAdditional/g, '').trim().split(/\s+/));
	  });
	  if(selArr){
    	  classes = getIntersectionArray(selArr);
    	  
    	  //Disable all attribute-options
    	  $('.updateprice').each(function(){
    	    if($(this).is(':selected')) {
    	      $(this).removeAttr('selected');
    	    }
    	    $(this).attr('disabled', 'disabled');
    	  });
    	  $('.updateprice.mainAttr').removeAttr('disabled');
    	  $('.updateprice.ignoreblock').removeAttr('disabled');
    	  
    	  //Enable attribute-options for each selected class
    	  $.each(classes, function(ckey, cval){
    	    $('.updateprice.'+cval).each(function(){
    		  $(this).removeAttr('disabled');
    	 	});		    
    	  });
	  }
	  $('.updateprice').removeClass('ignoreblock');
	}
	
	
	function DisplayPrice(){
	  var variantElem = 'variant-';
	  var attrcnt1 = $('#attr_cnt').val();
	  
	  //Check each variant
	  $('.variants').each(function(vkey, vval){
	    var attrcnt2 = 0;
	    var flag = true;
	    var variantId = $(vval).attr('id');
	    
	    //Check selected options
	    $('.updateprice:selected, .updateprice:checked').each(function(akey, aval){
	      	attrcnt2++; 
			if(!$(aval).hasClass(variantId) || $(aval).hasClass('defaultAttr') ){
			  flag = false;
			  return false; 
			}
	 	});		    
	    if(attrcnt1 == attrcnt2 && flag){
	      variantElem = variantId;
	      return false;
	    }
	  });
	  
	  //Display selected variant-price
	  $('.variant-prices').hide();
	  $('#price').val('0');
	  $('.variant-panels').hide();
	  $('#'+variantElem).fadeIn('slow');
	  $('#variant_id').val('0');
	  
	  //If valid variant/price then display add-to-cart button and update price field 
	  if(variantElem != 'variant-'){
	    var price = $('#'+variantElem).find('.selected-price').attr('data-value');
	    $('#price').val(price);
	    $('#'+variantElem+'-panel').fadeIn();
	    $('#variant_id').val(variantElem.replace('variant-', ''));
	  }
	}
	
	
	function SetAdditionals(ID){
	  $('.additionals').hide().find('input').attr('disabled', 'disabled');
	  if(ID){
	    $('#additional-'+ID).fadeIn().find('input').removeAttr('disabled');
	  }
	}
		
	
	function getParameterByName(name) {
		name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
		results = regex.exec(location.search);
		return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}

	
	function getIntersectionArray(ARRAYS){
	  var result = ARRAYS.shift().reduce(function(res, v) {
	    if (res.indexOf(v) === -1 && ARRAYS.every(function(a) {
	        return a.indexOf(v) !== -1;
	    })) res.push(v);
	    return res;
	}, []);
	  return result;
	}
</script>

{/block}
