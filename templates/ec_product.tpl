{block name=body}

<div id="proddet">
  <div class="container">
    <div class="row" id="productout">
	  <div class="col-sm-12">
        <div id="back">
          <a href="/products"> < Back to collections
          </a>
        </div>
	  </div>
	  <div class="col-sm-12 visible-xs visible-sm">
        <div class="text-center">
	        <h1>{$product_name}</h1>
			<div class="prodcode"></div>
        </div>
	  </div>
      <div class="col-sm-12 col-md-7" id="prodleft">
        <div id="prodslider" >
			<div class="prod-wishlist"><a href="#"><img src="/images/prod-wishlist.png" alt="Wishlist" draggable="false"></a></div>
			<div class="flexslider">
			  <ul class="slides">
			  	{assign var='count' value=0}
			  	{if !empty($gallery)}
			  	{foreach $gallery as $image }
			    <li data-thumb="{$image.gallery_link}?width=100&height=100&crop=1">
					<img src="{$image.gallery_link}?width=757&height=484&crop=1" title="{$image.gallery_title}" alt="{$image.gallery_alt_tag}" class="img-responsive">
			    </li>
				{assign var='count' value=$count+1}
				{/foreach}
				{else}
				    <li data-thumb="images/no-image-available.jpg?width=100&height=100&crop=1">
					<img src="/images/no-image-available.jpg?width=770&height=492&crop=1" title="Placeholder" alt="Placeholder" class="img-responsive">
					</li>
				{/if}
			  </ul>
			</div>
        </div>

		<div id="accout">
		<div id="accordion">
        {if $product_description}
			<h3>
				<div class="head-text">
					<div class="head-title">Description</div>
				</div>
			</h3>
			<div>
				{$product_description}
			</div>
		{/if}
        {if $pwarranty_description}
			<h3>
				<div class="head-text">
					<div class="head-title">Warranty</div>
				</div>
			</h3>
			<div>
				{$pwarranty_description}
			</div>
		{/if}
        {if count($pcarelinks) gt 0}
			<h3>
				<div class="head-text">
					<div class="head-title">Care &amp; cleaning</div>
				</div>
			</h3>
			<div>
				{foreach $pcarelinks as $care}{$care.pcare_description}{/foreach}
			</div>
		{/if}
		{if $pdelivery_description}
			<h3>
				<div class="head-text">
					<div class="head-title">Delivery &amp; returns</div>
				</div>
			</h3>
			<div>
				{$pdelivery_description}
			</div>
		{/if}
		</div>
        </div>
      </div>
      <div class="col-sm-12 col-md-5" id="prodright">
        <h1 class="hidden-xs hidden-sm">{$product_name}</h1>
        <div class="prodcode hidden-xs hidden-sm"></div>
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
                <div class="variant-prices variants specialprice" id="variant-{$variant.variant_id}" style="display:none" data-uid="{$variant.variant_uid}">
                  {if $user.id && $variant.variant_membersprice gt 0}
                    <div>$<span>{$variant.variant_price|number_format:0:'.':','}</span></div>
                    <div><b>Members Price:</b> $<span class="selected-price" data-value="{$variant.variant_membersprice}">{$variant.variant_membersprice|number_format:0:'.':','}</span></div>
                  {elseif $variant.variant_specialprice gt 0}
                    <div class="specialprice">$<span class="selected-price" data-value="{$variant.variant_specialprice}">{$variant.variant_specialprice|number_format:0:'.':','}</span></div>
                    <div class="sale">Sale!</div>
                    <div class="normalprice">Was $<span>{$variant.variant_price|number_format:0:'.':','}</span></div>
                  {else}
                    <div>$<span class="selected-price" data-value="{$variant.variant_price}">{$variant.variant_price|number_format:0:'.':','}</span></div>
                  {/if}
                </div>
              {/foreach}
              <div class="variant-prices" id="variant-">
                  <div class="specialprice">${$general_details.price.min|number_format:0:'.':','}{if $general_details.price.min neq $general_details.price.max} - ${$general_details.price.max|number_format:0:'.':','}{/if}</div>
              </div>
              {if $product_type_id eq 1}
              <div class="memfee">
              + {$CONFIG_VARS.membership_fee} membership fee (new members only). <a href="#" class="price">Learn more ></a>
              </div>
              {/if}
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
          <div class="form-group{if $product_type_id eq 1 && $attr.attribute_type neq 1} attr-hidden{/if}" style="{if $product_type_id eq 1 && $attr.attribute_type neq 1}display:none{/if}">
            <div class="col-sm-12" id="{if $attr.attribute_name eq 'Colour'}colbox{/if}">
              <label for="{urlencode data=$attr.attribute_name}" class="control-label">{$attr.attribute_name}</label>
              <div class="help-block"></div>
              {if $attr.attribute_type eq 1}
              <div class="attrtype1_name">Select one</div>
                {foreach $prdattrValuesArr[$attr.attribute_id] as $value}
                  <div class="attrtype1">
				  	<label for="{urlencode data=$attr.attribute_name|cat:'_'|cat:$value.attr_value_name}">
	                    <input type="radio" onclick="$(this).closest('.form-group').find('.attrtype1_name').html($(this).attr('data-name'));$('.attr-hidden').show();" data-name="{$value.attr_value_name}" class="mainAttr hasAttr updateprice{foreach $value.variants as $vr} variant-{$vr}{/foreach}" value="{$value.attr_value_id}" name="attr[{$attr.attribute_id}][id]" id="{urlencode data=$attr.attribute_name|cat:'_'|cat:$value.attr_value_name}" required="required">
                    	<img src="{$value.attr_value_image}?width=50&height=50&crop=1" title="{$value.attr_value_name}" alt="{$value.attr_value_name}">
                    </label>
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
                      <div class="row charrow">
	                      <div class="col-sm-3 col-lg-2">
						  	<label class="control-label lineno" for="additional-{$attr.attribute_id}-{$var}">Line {$var}</label>
	                      </div>
	                      <div class="col-sm-6 col-md-9 col-lg-6">
						  	<input class="form-control{if $value[$varName]} hasmaxlength{/if}" maxlength="{$value[$varName]}" name="attr[{$attr.attribute_id}][additional][{$var}]" id="additional-{$value.attr_value_id}-{$var}">
	                      </div>
	                      <div class="col-sm-3 col-md-12 col-lg-4 charleft">
		                      {if $value[$varName]}{$value[$varName]} characters left{/if}
	                      </div>
                      </div>
                      {/if}
                    {/for}
                      <div class="row charhelp">
	                      <div class="col-sm-12">
						  	<label>Need help with engraving?</label>
						  	<p>Simply click &quot;add to cart&quot; and proceed through payment. One of our friendly team members will be then get in touch with you.</p>
						  	<a href="/faqs">Engraving tips ></a>
	                      </div>
                      </div>
                    </div>
                  {/foreach}
                {else}
                  {foreach $prdattrValuesArr[$attr.attribute_id] as $value}
                    {if $value.attr_value_description}{$value.attr_value_description}{break}{/if}
                  {/foreach}
                {/if}
              {/if}
            </div>
          </div>
          {/foreach}

          <div class="form-group">
  	        <div class="prodprice col-sm-12">
              <div class="text-danger" id="error-text"></div>
                <div class="text-danger text-center variant-outofstocks {foreach $variants as $variant}{if $variant.variant_instock neq 1} variant-{$variant.variant_id}-outofstock{/if}{/foreach}" style="display:none"><b>Sorry, this item is out of stock</b></div>
                <div class="prodaddcart">
                  <button class="btn btn-red variant-addbtns {foreach $variants as $variant}{if $variant.variant_instock eq 1} variant-{$variant.variant_id}-addbtn{/if}{/foreach}" type="submit">Add to Cart</button>
                </div>

            </div>
          </div>

          {if $product_type_id eq 1}
          <div class="form-group">
	          <div class="col-sm-12" id="bottombox">
		          <h5>When you purchase your first medical ID you are also becoming a MedicAlert member</h5>
				  <p>From the moment you join, and each year you renew your membership, you’ll get access to a range of valuable benefits that could help save your life. <a href="#">Learn more ></a></p>
	          </div>
          </div>
          {/if}
        </form>

        </div>
		<div class="col-xs-12" id="prodformbelow"></div>
    </div>
  </div>
</div>
{if $product_type_id eq 1}
<div id="recent">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 text-center">
        <h2>Members also purchased</h2>
      </div>
    </div>

    <div class="row">
      <div id="popslide" class="flexslider">
        <ul class="slides">
          <li>
            <div class="prod">
              <a href="#"> <img src="/images/pop1.jpg?width=568&height=363&crop=1" alt="Popular product 1" class="img-responsive" />
              </a>
            </div>
          </li>

          <li>
            <div class="prod">
              <a href="#"> <img src="/images/pop2.jpg?width=568&height=363&crop=1" alt="Popular product 1" class="img-responsive" />
              </a>
            </div>
          </li>

          <li>
            <div class="prod">
              <a href="#"> <img src="/images/pop3.jpg?width=568&height=363&crop=1" alt="Popular product 1" class="img-responsive" />
              </a>
            </div>
          </li>

          <li>
            <div class="prod">
              <a href="#"> <img src="/images/pop4.jpg?width=568&height=363&crop=1" alt="Popular product 1" class="img-responsive" />
              </a>
            </div>
          </li>

          <li>
            <div class="prod">
              <a href="#"> <img src="/images/pop1.jpg?width=568&height=363&crop=1" alt="Popular product 1" class="img-responsive" />
              </a>
             </div>
          </li>

          <li>
            <div class="prod">
              <a href="#"> <img src="/images/pop1.jpg?width=568&height=363&crop=1" alt="Popular product 1" class="img-responsive" />
              </a>
            </div>
          </li>

          <li>
            <div class="prod">
              <a href="#"> <img src="/images/pop2.jpg?width=568&height=363&crop=1" alt="Popular product 1" class="img-responsive" />
              </a>
            </div>
          </li>

        </ul>
      </div>
    </div>
  </div>
</div>
{/if}

{/block}

{block name=tail}
<script src="/includes/js/jquery-ui.js"></script>
<script type="text/javascript" src="/includes/js/jquery.selectBoxIt.min.js"></script>
<script type="text/javascript" src="/includes/js/jquery.flexslider-min.js"></script>
<script>
  $( function() {
    var icons = {
      header: "glyphicon glyphicon-plus",
      activeHeader: "glyphicon glyphicon-minus"
    };
    $( "#accordion" ).accordion({
      icons: icons,
	  heightStyle: "content",
      collapsible: true
    });
  } );


	$(document).ready(function(){

      $(window).on('resize', function() {
      setTimeout(function(){
          var slider = $('.flexslider').data('flexslider');
          slider.resize();
      }, 1000);

      });

    	if (matchMedia) {
    		var mq = window.matchMedia("(max-width: 992px)");
    		mq.addListener(WidthChange1);
    		WidthChange1(mq);
    	}

    	function WidthChange1(mq) {

    	if (mq.matches) {
    		$("#prodformbelow").append($("#accordion"));
    	}
    	else{
    		$("#accout").append($("#accordion"));
    	}

    	}
		$('#product-form').validate({
		  onkeyup: false,
		  onclick: false,
          submitHandler: function(form) {
            addCart($(form).attr('id'));
          }
        });

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

		$('.hasmaxlength').keyup(function(){
		  var left = parseInt($(this).attr('maxlength')) - $(this).val().length;
		  var content = left + ' character' + (left > 1 ? 's' : '') + ' left';
		  $(this).closest('.row').find('.charleft').html(content);
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

$(window).load(function() {
  $('#prodslider .flexslider').flexslider({
    animation: "slide",
    controlNav: "thumbnails"
  });
});

  (function() {

    // store the slider in a local variable
    var $window = $(window), flexslider;

    // tiny helper function to add breakpoints
    function getGridSize() {
      return (window.innerWidth < 768) ? 1 : (window.innerWidth < 992) ? 4 : 6;
    }

   /*  $(function() {
      SyntaxHighlighter.all();
    }); */


    $window.load(function() {
      $('#popslide').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        itemWidth: 210,
        itemMargin: 20,
        minItems: getGridSize(), // use function to pull in initial value
        maxItems: getGridSize(),
		start: function(slider){
			flexslider = slider;
		}
      // use function to pull in initial value
      });
    });

    // check grid size on resize event
    $window.resize(function() {
      var gridSize = getGridSize();

      flexslider.vars.minItems = gridSize;
      flexslider.vars.maxItems = gridSize;
    });
  }());



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
	  $('.variant-outofstocks').hide();
	  /* $('.variant-addbtns').attr('disabled', 'disabled'); */
	  $('#'+variantElem).fadeIn('slow');
	  $('#variant_id').val('0');
	  $('.prodcode').html('');

	  //If valid variant/price then display add-to-cart button and update price field
	  if(variantElem != 'variant-'){
	    var price = $('#'+variantElem).find('.selected-price').attr('data-value');
	    $('#price').val(price);
	    $('.'+variantElem+'-outofstock').fadeIn();
	    /* $('.'+variantElem+'-addbtn').removeAttr('disabled'); */
	    $('#variant_id').val(variantElem.replace('variant-', ''));
	    $('.prodcode').html('Product code: ' + $('#'+variantElem).attr('data-uid'));
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
