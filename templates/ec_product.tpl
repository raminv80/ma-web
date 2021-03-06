{block name=body}
<div id="proddet">
  <div class="container">
    <div class="row" id="cart-notification" style="display:none;">
      <div class="col-sm-12">
        <div class="alert alert-info fade in text-center">
          <button class="close" aria-hidden="true" onclick="$('#cart-notification').fadeOut()" type="button">&times;</button>
          <strong></strong>
        </div>
      </div>
    </div>
    <div class="row" id="productout">
	  <div class="col-sm-12">
        <div id="back">
          <a href="/products{if $backcollectionURL}{$backcollectionURL}{/if}"> < Back to{if $backcollection} {$backcollection} {else} collections{/if}
          </a>
        </div>
	  </div>
	  <div class="col-sm-12 visible-xs visible-sm">
        <div class="text-center">
	        <h1>{$product_name}</h1>
            {if $product_membersonly eq 1}<div class="margintb15"><span class="white-tag">Members only</span></div>{/if}
            <div class="margintb15 tag-outofstock" style="display:none"><span class="white-tag">Out of stock</span></div>
			<div class="prodcode"></div>
        </div>
	  </div>
      <div class="col-sm-12 col-md-7" id="prodleft">
        <div id="prodslider" >
			<div class="prod-wishlist"><a href="javascript:void(0)" title="Your wish list" data-pid="{$product_object_id}" class="prodwishlist prodwishlist-{$product_object_id}{if $product_object_id|in_array:$wishlist} active{/if}"><img src="/images/prod-wishlist{if $product_object_id|in_array:$wishlist}-selected{/if}.png" alt="Wishlist" draggable="false"></a></div>
			<div class="flexslider">
			  <ul class="slides">
			  	{assign var='count' value=0}
			  	{if $gallery}
			  	{foreach $gallery as $image }
			    <li data-thumb="{$image.gallery_link}?width=100&height=100&crop=1">
					<img src="{$image.gallery_link}?width=757&height=484&crop=1" title="{$image.gallery_title}" alt="{$image.gallery_alt_tag}" class="img-responsive img-variant-{$image.gallery_variant_id}">
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
        <div id="carousel" class="flexslider hidden-xs hidden-sm">
          <ul class="slides">
            {if $gallery}
			  	{foreach $gallery as $image }
			    <li>
					<img src="{$image.gallery_link}?width=757&height=484&crop=1" title="{$image.gallery_title}" alt="{$image.gallery_alt_tag}" class="img-responsive img-variant-{$image.gallery_variant_id}">
			    </li>
				{/foreach}
            {/if}
          </ul>
        </div>
        <div class="text-center visible-xs visible-sm"><small>Swipe to see more images</small></div>

		<div id="imgholder" style="display: none;">
            {if $gallery}
			  	{foreach $gallery as $image }
					<img src="{$image.gallery_link}?width=757&height=484&crop=1" title="{$image.gallery_title}" alt="{$image.gallery_alt_tag}" class="img-responsive img-variant-{$image.gallery_variant_id}">
				{/foreach}
            {/if}
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
					<div class="head-title">Delivery</div>
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
        {if $product_membersonly eq 1}<div class="margintb15 hidden-xs hidden-sm"><span class="white-tag">Members only</span></div>{/if}
        <div class="margintb15 hidden-xs hidden-sm tag-outofstock" style="display:none"><span class="white-tag">Out of stock</span></div>

        <div class="prodcode hidden-xs hidden-sm"></div>
        <form class="form-horizontal" id="product-form" role="form" accept-charset="UTF-8" action="" method="post">
          <input type="hidden" value="ADDTOCART" name="action" id="action" />
          <input type="hidden" name="formToken" id="formToken" value="{$token}" />
          <input type="hidden" value="{$product_object_id}" name="product_id" id="product_id" />
          <input type="hidden" value="{$impressionList}" name="listname" id="listname" />

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
              + {$CONFIG_VARS.membership_fee} membership fee (new members only). <a href="/cost" title="Learn more" class="price">Learn more ></a>
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
                      {* HACK TO DISPLAY THE VARIANT IMAGE AS COLOUR *}
                       {* COMMENTED OUT !!!
                        {foreach $gallery as $g}
                          {if $g.gallery_variant_id eq $variant.variant_id}{$prdattrValuesArr[$attr.attribute_id][$value.attr_value_id]['attr_value_image'] = $g.gallery_link}{break}{/if}
                        {/foreach}
                        *}
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
          {if !$prdattrValuesArr[$attr.attribute_id]}{continue}{/if}
          <div class="form-group{if $product_type_id eq 1 && $attr.attribute_type neq 1} attr-hidden{/if}" style="{if $product_type_id eq 1 && $attr.attribute_type neq 1}display:none{/if}">
            <div class="col-sm-12" id="{if $attr.attribute_name eq 'Colour'}colbox{/if}">
              <label for="{urlencode data=$attr.attribute_name}" class="control-label">{$attr.attribute_name}</label>
              <div class="help-block"></div>
              {if $attr.attribute_type eq 1}
              <div class="attrtype1_name">Select one</div>
                {foreach $prdattrValuesArr[$attr.attribute_id] as $value}
                  <div class="attrtype1">
				  	<label for="{urlencode data=$attr.attribute_name|cat:'_'|cat:$value.attr_value_name}" class="label-imgs">
	                    <input type="radio" {if count($prdattrValuesArr[$attr.attribute_id]) eq 1}checked="checked"{/if} onclick="" data-name="{$value.attr_value_name}" class="image-selector mainAttr hasAttr updateprice{foreach $value.variants as $vr} variant-{$vr}{/foreach}" value="{$value.attr_value_id}" name="attr[{$attr.attribute_id}][id]" id="{urlencode data=$attr.attribute_name|cat:'_'|cat:$value.attr_value_name}" required="required">
                    	<img src="{$value.attr_value_image}?width=50&height=50&crop=1" data-var="" title="{$value.attr_value_name}" alt="{$value.attr_value_name}">
                    </label>
                  </div>
                {/foreach}
              {else}
              {$displaySelect = 1}
              {if $attr.attribute_type eq 0 && count($prdattrValuesArr[$attr.attribute_id]) eq 1}
                {foreach $prdattrValuesArr[$attr.attribute_id] as $value}
                  {if $value.attr_value_flag1 eq 1}
                    <input type="text" class="form-control hasAttr custom-input-field" maxlength="8" name="attr[{$attr.attribute_id}][additional][0]" id="additional-{$value.attr_value_id}-0" autocomplete="off" required="required">
                    {$displaySelect = 0}
                  {/if}
                {/foreach}
              {/if}
              <select {if $displaySelect eq 0}style="display:none;"{/if} id="{urlencode data=$attr.attribute_name}" name="attr[{$attr.attribute_id}][id]" class="form-control required notMainAttr hasAttr{if $attr.attribute_type eq 2} hasAdditional{/if}" required="required">
                <option value="" class="defaultAttr updateprice{foreach $prdattrArr[$attr.attribute_id] as $vr} variant-{$vr}{/foreach}" >Select one</option>
                {foreach $prdattrValuesArr[$attr.attribute_id] as $value}
                  <option value="{$value.attr_value_id}" {if count($prdattrValuesArr[$attr.attribute_id]) eq 1}selected="selected"{/if} class="updateprice{foreach $value.variants as $vr} variant-{$vr}{/foreach}">{$value.attr_value_name}</option>
                {/foreach}
              </select>

                {if $attr.attribute_type eq 2}
                  {foreach $prdattrValuesArr[$attr.attribute_id] as $value}
                  <div class="additionals" id="additional-{$value.attr_value_id}" style="display:none;">
                    <div>{$value.attr_value_description}</div>
                    {if $value.attr_value_image}<div class="hidden-lg attr-image-wrapper"><img src="{$value.attr_value_image}" alt="{$value.attr_value_name}" title="{$value.attr_value_name}" class="attr-image img-responsive"></div>{/if}
                    <div class="charrow-wrapper" style="background: url('{$value.attr_value_image}') no-repeat;">

	                <div class="charrowin">
                    {for $var=1 to 8}
                      {$varName = 'attr_value_var'|cat:$var}
                      {if $value[$varName] gt 0}
                      <div class="row charrow">
	                      <div class="col-sm-12 col-md-3 col-lg-2 linenob">
						  	<label class="control-label lineno" for="additional-{$attr.attribute_id}-{$var}">Line {$var}</label>
	                      </div>
	                      <div class="col-sm-12 col-md-9 col-lg-7 textcont">
						  	<input type="text" class="form-control{if $value[$varName]} hasmaxlength{/if}" {if $var eq 2}placeholder="Enter text"{/if} style="width: {$value[$varName]*11}px;" maxlength="{$value[$varName]}" name="attr[{$attr.attribute_id}][additional][{$var}]" id="additional-{$value.attr_value_id}-{$var}" onkeyup="if($(this).val().length == {$value[$varName]}){ $('#additional-{$value.attr_value_id}-{$var+1}').focus(); } else if($(this).val().length == 0 && event.keyCode == 8){ $('#additional-{$value.attr_value_id}-{$var-1}').focus();  }">
	                      </div>
	                      <div class="col-sm-6 col-md-12 col-lg-4 charleft">
		                      {if $value[$varName]}{$value[$varName]} characters left{/if}
	                      </div>
                      </div>
                      {/if}
                    {/for}
	                </div>

                    </div>
                      <div class="row charhelp">
	                      <div class="col-sm-12">
						  	<label>Need help with engraving?</label>
						  	<p>If you’re unsure of what details to engrave, simply click ‘add to cart’ and proceed through to payment and we’ll contact you shortly to discuss your needs. </p>
						  	<a href="/bracelet-sizing-and-engraving-tips#engrave-section" target="_blank" title="Click to view engraving tips">Engraving tips ></a>
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
                <div class="error-message text-center variant-outofstock {foreach $variants as $variant}{if $variant.variant_instock neq 1} variant-{$variant.variant_id}-outofstock{/if}{/foreach}" style="display:none"><b>Sorry but this item is temporarily out of stock</b></div>
                <div class="prodaddcart">
                  <button class="btn btn-red variant-addbtns {foreach $variants as $variant}{if $variant.variant_instock eq 1} variant-{$variant.variant_id}-addbtn{/if}{/foreach}" type="submit">Add to Cart</button>
                </div>

            </div>
          </div>

          {if $product_type_id eq 1}
          <div class="form-group">
	          <div class="col-sm-12" id="bottombox">
		          <h5>When you purchase your first medical ID you are also becoming a MedicAlert member</h5>
				  <p>From the moment you join, and each year you renew your membership, you'll get access to a range of valuable benefits that could help save your life. <a href="/benefits-of-membership" title="Learn more">Learn more ></a></p>
	          </div>
          </div>
          {/if}
        </form>

        </div>
		<div class="col-xs-12" id="prodformbelow"></div>
    </div>
  </div>
</div>
{if $product_type_id eq 1 && $productassoc}
<div id="recent">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 text-center">
      {foreach $productassoc as $item}
        {if $item.gallery && $item.gallery.0.gallery_link}
		{if $item.product_published eq 1}
        <h2>Members also purchased</h2>
		{break}
		{/if}
        {/if}
      {/foreach}
      </div>
    </div>

    <div class="row">
      <div id="relatedslide" class="flexslider">
        <ul class="slides">
          {foreach $productassoc as $item}
          {if $item.gallery && $item.gallery.0.gallery_link && $item.product_published eq 1}
            <li>
            <div class="prod">
              <a href="/{$item.product_url}"> <img src="{$item.gallery.0.gallery_link}?width=568&height=363&crop=1" alt="{$item.product_name}" title="{$item.product_name}" class="img-responsive" />
              </a>
            </div>
            </li>
          {/if}
          {/foreach}
        </ul>
      </div>
    </div>
  </div>
</div>
{/if}

{/block}

{block name=tail}
<script type="application/ld+json">
{
  "@context": "http://schema.org/",
  "@type": "Product",
  "name": "{$product_name|strip_tags}",
  "image": "{$DOMAIN}{$general_details.image}",
  "description": "{$product_meta_description}",
  "offers": {
    {if $general_details.price.min eq $general_details.price.max}
  "@type": "Offer",
    "priceCurrency": "AUD",
    "price": "{$general_details.price.min}",
    "availability": "InStock"
  {else}
  "@type": "AggregateOffer",
  "priceCurrency": "AUD",
    "highPrice": "{$general_details.price.max}",
    "lowPrice": "{$general_details.price.min}"
  {/if}
  }
}
</script>
{*{printfile file='/includes/js/jquery-ui.js' type='script'}*}
{*{printfile file='/includes/js/jquery.flexslider-min.js' type='script'}*}

  {printfile file='/node_modules/jquery-ui-dist/jquery-ui.min.js' type='script'}
  {printfile file='/node_modules/flexslider/jquery.flexslider-min.js' type='script'}
<script>

jQuery.fn.outerHTML = function(s) {
    return s
        ? this.before(s).remove()
        : jQuery("<p>").append(this.eq(0).clone()).html();
};

  $( function() {
    var icons = {
      header: "glyphicon glyphicon-plus",
      activeHeader: "glyphicon glyphicon-minus"
    };
    $( "#accordion" ).accordion({
      icons: icons,
	  heightStyle: "content",
      collapsible: true,
      activate: function( event, ui ) {
        if(!$.isEmptyObject(ui.newHeader.offset()) && !isScrolledIntoView(ui.newHeader)) {
            $('html:not(:animated), body:not(:animated)').animate({ scrollTop: ui.newHeader.offset().top }, 'slow');
        }
    }
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
		  DisplayAdditionalWhenValid();
		  DisplayPrice();
		});

		$('select.hasAttr').focus(function(){
		  UnblockSelectOptions(this);
		});

		$('.hasAdditional').change(function(){
		  SetAdditionals($(this).val());
		});

		$('.hasmaxlength').keyup(function(){
		  var left = parseInt($(this).attr('maxlength')) - $(this).val().length;
		  var content = left + ' character' + (left > 1 ? 's' : '') + ' left';
		  $(this).closest('.row').find('.charleft').html(content);
		});

		DisplayAdditionalWhenValid();
		DisplayPrice();

		$(".label-imgs").click(function(){
		  var oldSelection = $('.image-selector:checked').val();
		  var newSelection = $(this).find('.image-selector').attr('value');
		  if(newSelection != oldSelection){
		    $('.image-selector:checked').attr('checked', false);
		  }
	    $(this).find('.image-selector').prop('checked', true);
      SelectImage($(this).find('.image-selector'));
		});

		if($('.image-selector:checked')){
      setTimeout(function(){
        $('.image-selector:checked').trigger('click');
      }, 1000);
		}

	});

$(window).on('load', function() {
  $('#carousel.flexslider').flexslider({
    animation: "slide",
    controlNav: false,
    animationLoop: false,
    slideshow: false,
    itemWidth: 110,
    itemMargin: 20,
    asNavFor: '#prodslider .flexslider'
  });

  $('#prodslider .flexslider').flexslider({
    animation: "slide",
    controlNav: false,
    animationLoop: false,
    slideshow: false,
    sync: "#carousel.flexslider",
    start: function(){ load_ordered_product(); }
  });

});

  (function() {

    // store the slider in a local variable
    var $window = $(window), flexslider;

    // tiny helper function to add breakpoints
    function getGridSize() {
      var maxItem = $('#relatedslide .prod').length;
      var supported = (window.innerWidth < 768) ? 1 : (window.innerWidth < 992) ? 4 : 6;
      if(maxItem && maxItem < supported){
        var value = 16.66 * maxItem;
        $('#relatedslide').css('max-width', value+'%');
      }else{
        $('#relatedslide').css('max-width', 'initial');
      }

	  return (maxItem > supported ? supported : maxItem);
      //return (window.innerWidth < 768) ? 1 : (window.innerWidth < 992) ? 4 : 6;
    }

    $window.on('load', function() {
      var maxItem = $('#relatedslide .prod').length;
      if(maxItem && maxItem < 6){
        var value = 16.66 * maxItem;
        $('#relatedslide').css('max-width', value+'%');
        $('#relatedslide').css('margin', '0 auto 60px auto');
        $('#recent .flex-direction-nav').hide();
      }

      $('#relatedslide').flexslider({
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

function SelectImage(ELEMENT){
  ELEMENT.closest('.form-group').find('.attrtype1_name').html($(ELEMENT).attr('data-name'));
  $('.attr-hidden').show();

  BlockAttrOptions(ELEMENT);
  DisplayAdditionalWhenValid();
  DisplayPrice();

  $('#carousel.flexslider').flexslider(0);
  $('#prodslider .flexslider').flexslider(0);
  while ($('#prodslider .flexslider').data('flexslider').count > 0){
    $('#prodslider .flexslider').data('flexslider').removeSlide(0);
  }
  while ($('#carousel.flexslider').data('flexslider').count > 0){
    $('#carousel.flexslider').data('flexslider').removeSlide(0);
  }

  $("#imgholder img").each(function(index, element){
    var classes=$(element).attr('class');
    var class1=classes.replace('img-responsive ','');
    var class1=class1.substring(4,class1.length);
    if($("#prodright input[type='radio'].image-selector:checked").hasClass(class1)){
      html=$(element)[0].outerHTML;
      saved1 = $('<li>'+html+'</li>');
      $('#carousel.flexslider').data('flexslider').addSlide($(saved1));
    }
  });
  //Generic images
  $("#imgholder img.img-variant-0").each(function(index, element){
    html=$(element)[0].outerHTML;
    saved1 = $('<li>'+html+'</li>');
    $('#carousel.flexslider').data('flexslider').addSlide($(saved1));
  });

  $("#imgholder img").each(function(index, element){
    var classes=$(element).attr('class');
    var class1=classes.replace('img-responsive ','');
    var class1=class1.substring(4,class1.length);
    if($("#prodright input[type='radio'].image-selector:checked").hasClass(class1)){
      html=$(element)[0].outerHTML;
      saved1 = $('<li>'+html+'</li>');
      $('#prodslider .flexslider').data('flexslider').addSlide($(saved1));
    }
  });

  $("#imgholder img.img-variant-0").each(function(index, element){
    html=$(element)[0].outerHTML;
    saved1 = $('<li>'+html+'</li>');
    $('#prodslider .flexslider').data('flexslider').addSlide($(saved1));
  });

  $('#carousel.flexslider .slides li').on('click', function(){
    var curSlide = $( "#carousel.flexslider .slides li" ).index( this );
    $('#prodslider .flexslider').flexslider(curSlide);
  });
};

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
	    $('.notMainAttr').each(function(){
	      if($(this).find('option').length > 2){
	        $(this).val('');
	      }
	    });
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
	  $('.tag-outofstock').hide();
	  $('.variant-outofstock').hide();
	  $('.variant-addbtns').removeAttr('disabled');
	  $('#'+variantElem).fadeIn('slow');
	  $('#variant_id').val('0');
	  $('.prodcode').html('');

	  //If valid variant/price then display add-to-cart button and update price field
	  if(variantElem != 'variant-'){
	    var price = $('#'+variantElem).find('.selected-price').attr('data-value');
	    $('#price').val(price);
	    /* $('.'+variantElem+'-addbtn').removeAttr('disabled'); */
	    $('#variant_id').val(variantElem.replace('variant-', ''));
	    $('.prodcode').html('Product code: ' + $('#'+variantElem).attr('data-uid'));
	    if($('.'+variantElem+'-outofstock').length > 0){
	      $('.'+variantElem+'-outofstock').fadeIn();
	      $('.tag-outofstock').show();
	      $('.variant-addbtns').attr('disabled', 'disabled');
	    }
	  }
	}


	function SetAdditionals(ID){
	  $('.additionals').hide().find('input').attr('disabled', 'disabled');
	  if(ID){
	    $('#additional-'+ID).fadeIn().find('input').removeAttr('disabled');
	  }
	}

	function DisplayAdditionalWhenValid(){
	  if($('.hasAdditional').length){
	    if($('.hasAdditional').closest('form').valid()){
	      $('.hasAdditional').each(function(){
	        var ID = $(this).val();
            if(ID){
              SetAdditionals(ID);
            }
          });
		}
	    $('.hasAdditional').closest('form').find('.form-group.has-error .help-block').text('');
	    $('.hasAdditional').closest('form').find('.form-group.has-error').removeClass('has-error');
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

<script>
function load_ordered_product(){
  {if $ordered_item}
    var item_clr = ''; var item_length = ''; var item_size = ''; var item_engraving = "";
    {foreach $ordered_item.attributes as $att => $attr}
      {if $attr.cartitem_attr_attribute_id eq '2'}
        item_clr = "{$attr.cartitem_attr_attr_value_id}";
      {/if}
      {if $attr.cartitem_attr_attribute_id eq '1'}
        item_length = "{$attr.cartitem_attr_attr_value_id}";
      {/if}
      {if $attr.cartitem_attr_attribute_id eq '4'}
        item_size = "{$attr.cartitem_attr_attr_value_id}";
        item_engraving = '{$attr.cartitem_attr_attr_value_additional}';
      {/if}
    {/foreach}
    $('.image-selector[value="'+item_clr+'"]').trigger('click');
    if(item_length != ''){
      $('#length').val(item_length);
    }
    if(item_size != ''){
      $('#medical_id_size').val(item_size);
    }
    if(item_engraving != ''){
      var engravings = JSON.parse(item_engraving);
      $.each(engravings, function(i, item) {
        $('input[type="text"][name="attr[4][additional]['+i+']"]').val(item);
      });
    }
    $('#medical_id_size').trigger('change');
  {/if}
}
</script>

{/block}
