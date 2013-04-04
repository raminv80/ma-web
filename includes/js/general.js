	
	//Top dropDown menu bar definer; defines the top menu bar as a drop down bar
	ddlevelsmenu.setup("ddtopmenubar", "topbar") ;
	//END
	
	//Captcha options
	var RecaptchaOptions = {
			theme : 'white'
	};

function phoneMask(f){
	tel='';
	var str =f.value;
	str = str.replace('(', '');
	str = str.replace(')', '');
	str = str.replace('-', '');
	str = str.replace(' ', '');
	var val =str.split('');
	for(var i=0; i<val.length; i++){
		if(i==0){ val[i]='('+val[i] }
		if(i==1){ val[i]=val[i]+')' }
		if(i==5){ val[i]=val[i]+' ' }
		tel=tel+val[i]
	}
	f.value=tel;
}
	
	
function nextPage(form,p,n) {
	var arg = 'page'+p+'-page'+(n);
	jQuery(".Action").attr('value',arg);
	jQuery(form).submit();
}

function updateItems(){
  	var items = jQuery('.item_qty');
  	var products = "";
  	var quantities = "";
  	jQuery.each(items, function(){
		var id = jQuery(this).attr('rel');
		products += ";" + id;
		var tq = jQuery(this).val();
		if(parseInt(tq) <= 0 ){
			quantities += ";" + "1";
		}else{
			quantities += ";" + jQuery(this).val();
		}
	});
  	jQuery('#product_theform').val(products);
	jQuery('#Action_theform').val('SetQty4Item');
	jQuery('#quantity_theform').val(quantities);
	jQuery('#theform').submit();
}

function deleteitem(id){
	jQuery('#product_theform').val(id);
	jQuery('#Action_theform').val('DeleteItem');
	jQuery('#theform').submit();
}

function increaseValue(id){
	var value = jQuery(id).val();
	value = parseInt(value)+1;
	jQuery(id).val(value);
}

function decreaseValue(id){
	var value = jQuery(id).val();
	value = value-1;
	if(value <1)
		value = 1;
	jQuery(id).val(value);
}


function validateAddToCart(){
	if(jQuery(".product-alt-dropdown").length){
		if(jQuery(".product-alt-dropdown option:selected").attr("value") > 0){
			jQuery(".errorlabel").html("");
			return true;
		}
	}else{
		if(jQuery(".product_id").attr("value") > 0){
			jQuery(".errorlabel").html("");
			return true;
		}
	}
	jQuery(".errorlabel").html("Please select a Emblem Type");
	return false;
}

function setAction(action){
	jQuery("#Trans_Action").val(action);
	return true;
}

function validatePassword(str){
	return true
}

function BillingToShipping(){
	
	//Shipping Validation
	jQuery("#ShippingFullName").val(jQuery("#BillingFullName").val());
	jQuery("#ShippingAddress").val(jQuery("#BillingAddress").val());
	jQuery("#ShippingAddress2").val(jQuery("#BillingAddress2").val());
	jQuery("#ShippingSuburb").val(jQuery("#BillingSuburb").val());
	jQuery("#ShippingState").val(jQuery("#BillingState").val());
	jQuery("#ShippingCountry option:selected").attr("value",jQuery("#BillingCountry option:selected").attr("value"));
	jQuery("#ShippingPostcode").val(jQuery("#BillingPostcode").val());
	jQuery("#ShippingPhone").val(jQuery("#BillingPhone").val());
	jQuery("#ShippingMobile").val(jQuery("#BillingMobile").val());
	jQuery("#ShippingFax").val(jQuery("#BillingFax").val());
	jQuery("#ShippingEmail").val(jQuery("#BillingEmail").val());
}

function validateShippingBilling(str){
	jQuery("#redirect").val(str);
	
	var errorCheck = false;
	var error = '<ul class="error-list">';

	if((""+jQuery('input[name=CardTypeRadioButtonList]:checked', '.creditCard').val()) == 'undefined')
	{
		error += '<li>Card Type is required</li>';
		errorCheck = true;
	}
	if(jQuery('#CardNumberMaskedTextBox').val() == ''){
		error += '<li>Card Number is required</li>';
		errorCheck = true;
	}
	if(jQuery('#month option:selected').attr("value") == '' || jQuery('#year option:selected').attr('value') == ''){
		error += '<li>Card Expiry Date is required</li>';
		errorCheck = true;
	}
	if(jQuery("#CardholderTextBox_text").val() == ""){
		error += '<li>Card Holder\'s Name is required</li>';
		errorCheck = true;
	}
	if(jQuery("#CardSecurityCodeMaskedTextBox_text").val() == ""){
		error += '<li>Card Verification Code is required</li>';
		errorCheck = true;
	}
	
	//Billing Validation 
	if(jQuery("#BillingFullName").val() == ""){
		error += '<li>Full Name is required</li>';
		errorCheck = true;
	}
	if(jQuery("#BillingAddress").val() == ""){
		error += '<li>Address is required</li>';
		errorCheck = true;
	}
	if(jQuery("#BillingSuburb").val() == ""){
		error += '<li>Suburb is required</li>';
		errorCheck = true;
	}
	if(jQuery("#BillingState").val() == ""){
		error += '<li>State is required</li>';
		errorCheck = true;
	}
	if(jQuery("#BillingCountry option:selected").attr("value") == ""){
		error += '<li>Country is required</li>';
		errorCheck = true;
	}
	if(jQuery("#BillingPostcode").val() == ""){
		error += '<li>Postcode is required</li>';
		errorCheck = true;
	}
	if(jQuery("#BillingPhone").val() == ""){
		error += '<li>Phone is required</li>';
		errorCheck = true;
	}
	if(jQuery("#BillingEmail").val() == ""){
		error += '<li>Email is required</li>';
		errorCheck = true;
	}
	
	if(jQuery("#BillEqualShipping").is(':checked') == false){
		//Shipping Validation
		if(jQuery("#ShippingFullName").val() == ""){
			error += '<li>Ship To Full Name is required</li>';
			errorCheck = true;
		}
		if(jQuery("#ShippingAddress").val() == ""){
			error += '<li>Ship To Address is required</li>';
			errorCheck = true;
		}
		if(jQuery("#ShippingSuburb").val() == ""){
			error += '<li>Ship To Suburb is required</li>';
			errorCheck = true;
		}
		if(jQuery("#ShippingState").val() == ""){
			error += '<li>Ship To State is required</li>';
			errorCheck = true;
		}
		if(jQuery("#ShippingCountry option:selected").attr("value") == ""){
			error += '<li>Ship To Country is required</li>';
			errorCheck = true;
		}
		if(jQuery("#ShippingPostcode").val() == ""){
			error += '<li>Ship To Postcode is required</li>';
			errorCheck = true;
		}
		if(jQuery("#ShippingPhone").val() == ""){
			error += '<li>Ship To Phone is required</li>';
			errorCheck = true;
		}
		if(jQuery("#ShippingEmail").val() == ""){
			error += '<li>Ship To Email is required</li>';
			errorCheck = true;
		}
	}
	
	error += '</ul>';
	if(errorCheck){
		jQuery(".error-div").html(error);
		return false;
	}else{
		return true;
	}
}
	
jQuery(document).ready(function() {
	var mytextsizer=new fluidtextresizer({
		controlsdiv: "fontsize", //id of special div containing your resize controls. Enter "" if not defined
		targets: ["body" ], //target elements to resize text within: ["selector1", "selector2", etc]
		levels: 2, //number of levels users can magnify (or shrink) text
		persist: "session", //enter "session" or "none"
		animate: 500 //animation duration of text resize. Enter 0 to disable
		}) 
	
	//Anything Slider definer
	jQuery('#gallery').anythingSlider({
		// Appearance http://proloser.github.com/AnythingSlider/
		theme               : 'minimalist-round', // Theme name
		width               : 620,      // Override the default CSS width
		height              : 294,      // Override the default CSS height
		resizeContents      : true,      // If true, solitary images/objects in the panel will expand to fit the viewport
	
		// Navigation
		startPanel          : 1,         // This sets the initial panel
		hashTags            : false,      // Should links change the hashtag in the URL?
		buildArrows         : false,      // If true, builds the forwards and backwards buttons
		buildNavigation     : false,      // If true, builds a list of anchor links to link to each panel
		buildStartStop      : false,     // If true, buildsa list of anchor links to link to each panel
		navigationFormatter : null,      // Details at the top of the file on this use (advanced use)
		forwardText         : '&raquo;', // Link text used to move the slider forward (hidden by CSS, replaced with arrow image)
		backText            : '&laquo;', // Link text used to move the slider back (hidden by CSS, replace with arrow image)
	
		// Slideshow options
		autoPlayLocked      : true,
		autoPlay            : true,      // This turns off the entire slideshow FUNCTIONALY, not just if it starts running or not
		startStopped        : false,     // If autoPlay is on, this can force it to start stopped
		pauseOnHover        : false,      // If true & the slideshow is active, the slideshow will pause on hover
		resumeOnVideoEnd    : true,      // If true & the slideshow is active & a youtube video is playing, it will pause the autoplay until the video has completed
		stopAtEnd           : false,     // If true & the slideshow is active, the slideshow will stop on the last page
		playRtl             : false,     // If true, the slideshow will move right-to-left
		startText           : 'Start',   // Start button text
		stopText            : 'Stop',    // Stop button text
		delay               : 3000,      // How long between slideshow transitions in AutoPlay mode (in milliseconds)
		animationTime       : 600,       // How long the slideshow transition takes (in milliseconds)
		easing              : 'swing'    // Anything other than 'linear' or 'swing' requires the easing plugin
	});
	//END
	
	//Homepage read more expander
	jQuery("div.homepage_content_part2").hide();
	jQuery("p.home_read_more a").click(function(){
	if (jQuery(this).parent().next().is(":visible"))
	{
		jQuery(this).parent().next().slideUp("slow");
		jQuery(this).removeClass("visible").addClass("invisible");
		jQuery(this).prev().removeClass("invisible").addClass("visible");
	}
	else
	{
		jQuery(this).parent().next().slideDown("slow");
		jQuery(this).removeClass("visible").addClass("invisible");
		jQuery(this).next().removeClass("invisible").addClass("visible");
	}
	return false;
	}); 
	//END
	
	
	//Accordian
	jQuery(".accordion_heading a").click(function(){
	if (jQuery(this).parent().next().is(":visible"))
	{
		jQuery(this).parent().next().slideUp("slow");
		jQuery(this).parent().css("background", "url(/images/closed.gif) no-repeat 0px 11px");
	}
	else
	{
		jQuery(this).parent().css("background", "url(/images/open.gif) no-repeat 0px 11px");
		jQuery(this).parent().next().slideDown("slow");
	}
	return false;
	});
	//END Accordian
	
	//Scroll to error section
	if(jQuery(".error-list").length){
		jQuery('html,body').animate({scrollTop: jQuery(".error-list:first").offset().top},'slow');
	}
	
	jQuery("#BillEqualShipping").click(function(){
		if(jQuery("#BillEqualShipping").is(':checked') == true){
			BillingToShipping();
		}
	});
	//PrettyPhoto Initialiser
	//jQuery("a[rel^='prettyPhoto']").prettyPhoto();
	//End
	
	
});

