if (jQuery.validator) {
	  jQuery.validator.setDefaults({
	    errorClass: 'has-error',
	    validClass: 'has-success',
	    ignore: "",
	    highlight: function (element, errorClass, validClass) {
	      $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
	      $('.error-textbox').html('Error, please check the highlighted fields.').show();
	    },
	    unhighlight: function (element, errorClass, validClass) {
	      $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
	    },
	    errorPlacement: function (error, element) {
	     
	    },
	    submitHandler: function (form) {
	    	$('.error-textbox').hide();
    	  var formID = $(form).attr('id');
    	  var formCheck = $(form).attr('data-attr-id');
    	  if(formCheck == undefined || formCheck == ""){
    		  formCheck = $(form).attr('id');
    	  }
          switch ( formCheck ) {
          	case 'product-form': 
          		addCart(formID);
          		break;
          	case 'login-form':
        	case 'register-form':
        		userLogin(formID); 
        		break;
        		
        	case 'checkout2-form': 
        		checkout2(formID); 
          		break;
          		
        	case 'checkout3-form': 
        		var paymentOption = 'Credit Card';
        		ga('ec:setAction', 'checkout_option', {
		 	        'step': 2,
		 	        'option': paymentOption
		 	      });
		 	    ga('send', 'event', 'Checkout', 'Payment', paymentOption{
		 	       hitCallback: function() {
		 	    	  form.submit();
		 	    });
          		break;
          		
        	case 'reset-pass-form': 
        		resetPass(formID);
        		break;
        		
          	default:
          		form.submit();
          }
	    }
	  });
}


$('.modifier').change(function() {
	calculatePrice();
});



$('.gt-zero').change(function() {
	if(parseInt($(this).val()) < 1 || $(this).val() == '') {
		$(this).val('1');
	}
	
});

$('.unsigned-int').keyup(function () {
    if (this.value != this.value.replace(/[^0-9]/g, '')) {
       this.value = this.value.replace(/[^0-9]/g, '');
    }
});

function calculatePrice(){
	var price = parseFloat($('#cal-price').attr('value'));
	$('.modifier').each(function(){
			price = price + parseFloat($('option:selected', this).attr('price'));
			
		});
	$('#cal-price').html(price.toFixed(2)); 
	$('#price').val(price.toFixed(2)); 
}


function userLogin(form){
	$('body').css('cursor','wait');
	var datastring = $('#'+form).serialize();
	$.ajax({
		type: "POST",
	    url: "/process/user",
		cache: false,
		data: datastring,
		dataType: "html",
	    success: function(data) {
	    	try{
	    		var obj = $.parseJSON(data);
			 	if (obj.error) {
			 		$('#'+form+'-error').html(obj.error).show();
			 	} else {
			 		window.location.href = obj.url;
			 	}
			 	
			}catch(err){
				console.log('TRY-CATCH error');
			}
			$('body').css('cursor','default'); 
	    },
		error: function(){
			$('body').css('cursor','default'); 
			console.log('AJAX error');
      	}
	});
}

function resetPass(form){
	$('body').css('cursor','wait');
	var datastring = $('#'+form).serialize();
	$.ajax({
		type: "POST",
	    url: "/process/user",
		cache: false,
		data: datastring,
		dataType: "html",
	    success: function(data) {
	    	try{
	    		var obj = $.parseJSON(data);
			 	if (obj.error) {
			 		$('#'+form+'-error').html(obj.error).show();
			 		$('#'+form+'-success').hide();
			 	} else {
			 		$('#'+form+'-success').html(obj.success).show();
			 		$('#'+form+'-error').hide();
			 	}
			 	
			}catch(err){
				console.log('TRY-CATCH error');
			}
			$('body').css('cursor','default'); 
	    },
		error: function(){
			$('body').css('cursor','default'); 
			console.log('AJAX error');
      	}
	});
}

function addCart(form){
	$('body').css('cursor','wait');
  	$('.btn-primary').addClass('disabled');
	var datastring = $('#'+form).serialize();
	$.ajax({
		type: "POST",
	    url: "/process/cart",
		cache: false,
		data: datastring,
		dataType: "html",
	    success: function(data) {
	    	try{
	    		var obj = $.parseJSON(data);
	    		if (obj.url && $(window).width() < 760){
	    			window.location.href = obj.url;
	    		}
			 	$('.nav-itemNumber').html(obj.itemsCount);
			 	$('.nav-subtotal').html('$'+obj.subtotal);
			 	$('#shop-cart-btn').html( obj.popoverShopCart );
			 	$("html, body").animate({scrollTop:$('#shop-cart-btn').scrollTop()}, '1000', 'swing');
			 	$('#shop-cart-btn')
			 	$('#shop-cart-btn').fadeIn(200);
			 	setTimeout(function() {
			 		$('#shop-cart-btn').fadeOut(200);
			    }, 4000);
			 	ga('ec:addProduct', {
			 	    'id': obj.product.id,
			 	    'name': obj.product.name,
			 	    'category': obj.product.category,
			 	    'brand': obj.product.brand,
			 	    'variant': obj.product.variant,
			 	    'price': obj.product.price,
			 	    'quantity': obj.product.quantity
			 	  });
			 	  ga('ec:setAction', 'add');
			 	  var fullname = obj.product.name;
			 	  if(obj.product.variant){
			 		 fullname += ' | ' +  obj.product.variant
			 	  }
			 	  ga('send', 'event', 'Add to Cart', 'click', fullname); 
			 	
			}catch(err){
				console.log('TRY-CATCH error');
			}
			$('body').css('cursor','default'); 
			$('.btn-primary').removeClass('disabled');
	    },
		error: function(){
			$('body').css('cursor','default'); 
			console.log('AJAX error');
			$('.btn-primary').removeClass('disabled');
      	}
	});
}

function updateCart(){
	$('body').css('cursor','wait');
	var datastring = $("#shopping-cart-form").serialize();
	$.ajax({
		type: "POST",
	    url: "/process/cart",
		cache: false,
		data: 'action=updateCart&' + datastring,
		dataType: "html",
	    success: function(data) {
	    	try{
	    		var obj = $.parseJSON(data);
	    		var priceunits = obj.priceunits;
	    		var pricemodifier = obj.pricemodifier;
	    		var subtotals = obj.subtotals;
	    		var totals = obj.totals;
                $('.nav-itemNumber').html(obj.itemsCount);
			 	/*$('.nav-subtotal').html('$'+obj.totals['subtotal']);*/
			 	if (subtotals) {
			 		$.each(pricemodifier, function(id, value){
			 			if(value === "0%"){
				 			$('#qty-discount-'+id).html('');
			 			}else{
				 			$('#qty-discount-'+id).html('(-'+value+')');
			 			}
			 		});
			 		$.each(priceunits, function(id, value){
			 			amount = parseFloat(value);
			 			$('#priceunit-'+id).html('$'+amount.toFixed(2));
			 		});
			 		$.each(subtotals, function(id, value){
			 			amount = parseFloat(value);
			 			$('#subtotal-'+id).html('$'+amount.toFixed(2));
			 		});
			 		$.each(totals, function(id, value){
			 			amount = parseFloat(value);
			 			if(id == 'total'){
			 				$('#subtotal').attr('data-value', amount);
			 				$('#subtotal').html('$'+amount.toFixed(2));
			 			}else if(id == 'discount'){
			 				$('#'+id).html('$'+amount.toFixed(2));
			 			}
			 		});
			 		//renderShippingMethods(obj.shippingMethods);
			 		calculateTotal();
				} else {
					alert('Error: Cannot be updated');
				}
			}catch(err){
				console.log('TRY-CATCH error - '+err);
			}
			$('body').css('cursor','default'); 
	    },
		error: function(){
			$('body').css('cursor','default'); 
			console.log('AJAX error');
      	}
	});
}

function deleteItem(ID){
	$('body').css('cursor','wait');
	$( '#'+ ID ).fadeTo( "fast", 0.5 );
	var frmTkn = $("#formToken").val();
	$.ajax({
		type: "POST",
	    url: "/process/cart",
		cache: false,
		data: 'action=DeleteItem&cartitem_id='+ID+'&formToken='+frmTkn,
		dataType: "html",
	    success: function(data) {
	    	try{
	    		var obj = $.parseJSON(data);
			 	var response = obj.response;
                var totals = obj.totals;
                $('.nav-itemNumber').html(obj.itemsCount);
                $('.nav-subtotal').html('$'+obj.totals['subtotal']);
			 	$('#shop-cart-btn').html( obj.popoverShopCart );
			 	if (response) {
			 		ga('ec:addProduct', {
				 	    'id': obj.product.id,
				 	    'name': obj.product.name,
				 	    'category': obj.product.category,
				 	    'brand': obj.product.brand,
				 	    'variant': obj.product.variant,
				 	    'price': obj.product.price,
				 	    'quantity': obj.product.quantity
			 		});
			 		ga('ec:setAction', 'remove');

			 		var fullname = obj.product.name;
			 		if(obj.product.variant){
			 			fullname += ' | ' +  obj.product.variant
			 		}
			 	  	ga('send', 'event', 'Remove from Cart', 'click', fullname); 
				 	  
			 		if ( parseInt(obj.itemsCount) > 0 ){
                        $( '#'+ ID ).hide('slow');
                        $.each(totals, function(id, value){
                        	amount = parseFloat(value);
                        	$('#'+id).html('$'+amount.toFixed(2));
				 		});
                    } else {
                        location.reload();
                    }
				} else {
					$( '#'+ ID ).fadeTo( "fast", 1 ); 
					alert('Item cannot be deleted');
				}
			}catch(err){
				$( '#'+ ID ).fadeTo( "slow", 1 ); 
				console.log('TRY-CATCH error');
			}
			$('body').css('cursor','default'); 
	    },
		error: function(){
			$( '#'+ ID ).fadeTo( "slow", 1 );
			console.log('AJAX error');
			$('body').css('cursor','default'); 
      	}
	});
}


function checkout2(form) {
	$('body').css('cursor','wait');
	var datastring = $("#"+form).serialize();
	$.ajax({
		type: "POST",
	    url: "/process/cart",
		cache: false,
		data:  datastring,
		dataType: "html",
	    success: function(data) {
	    	try{
	    		var obj = $.parseJSON(data);
			 	if (obj.response) {
			 		$('.checkout2').hide();
			 		$('.checkout3').show();
			 		$('#billing-summary').html(obj.billing);
			 		$('#shipping-summary').html(obj.shipping);
			 		setCCRequired(true);
			 		scrolltodiv('#checkout3-form');
			 		
			 		var shippingOption = 'Standard';
			 	    ga('ec:setAction', 'checkout_option', {
			 	        'step': 1,
			 	        'option': shippingOption
			 	      });
			 	    ga('send', 'event', 'Checkout', 'Shipping', shippingOption);
			 	    ga('ec:setAction','checkout', {
			 		   'step': 2
			 		  });
			 	    ga('send', 'pageview');
			 	}
			}catch(err){
				console.log('TRY-CATCH error');
			}
			$('body').css('cursor','default'); 
	    },
		error: function(){
			$('body').css('cursor','default'); 
			console.log('AJAX error');
      	}
	});
	
}

//function calculateTotal(str) {
//	var value = parseFloat(str);
//	$('#shipping-fee-value').html('$'+ value.toFixed(2));
//	var total = parseFloat($('#total').attr('amount')) + value ;
//	$('.total-amount').html('$'+ total.toFixed(2));
//	var gst = (parseFloat($('#gst').attr('amount')) + value ) /10 ;
//	$('#gst').html('$'+ gst.toFixed(2));
//}

function addProductCart(ID, QTY, PRICE){
	$('body').css('cursor','wait');
	$.ajax({
		type: "POST",
	    url: "/process/cart",
		cache: false,
		data: "action=ADDTOCART&product_id="+ID+"&quantity="+QTY+"&price="+PRICE,
		dataType: "html",
	    success: function(data) {
	    	try{
	    		var obj = $.parseJSON(data);
	    		if (obj.url){
	    			window.location.href = obj.url;
	    		}
			 	
			}catch(err){
				console.log('TRY-CATCH error');
			}
			$('body').css('cursor','default'); 
	    },
		error: function(){
			$('body').css('cursor','default'); 
			console.log('AJAX error');
      	}
	});
}

$('.quantity').change(function() {
	updateCart();
});

function renderShippingMethods(OPT){
	if($('#shippingMethod').get(0).tagName == "select"){
	$('#shippingMethod').empty();
	$.each(OPT, function(id, value){
		$('#shippingMethod').append($('<option>', {
			value : value,
			text : id
		}));
	});
	}
}

$('#shippingMethod').change(function() {
	var price = parseFloat($(this).val());
	$('#shipping-fee').html('$'+ price.toFixed(2));
	calculateTotal(); 
});

function updateShipping(){
	$('body').css('cursor','wait');
	var postcode = $("#postcodesh").val();
	if($("#chksame:checked").length ==1 || postcode == undefined){
		postcode = $("#postcode-field").val();
	}
	var datastring = postcode;
	$.ajax({
		type: "POST",
	    url: "/process/cart",
		cache: false,
		data: 'action=updatePostage&postcode=' + datastring,
		dataType: "html",
	    success: function(data) {
	    	try{
	    		var obj = $.parseJSON(data);
	    		var postagefee = obj.postagefee;
	    		if(isArray(postagefee)) {
	    			// Iterate the array and do stuff
//	    			if (postagefee.length == 1) {
    			 		var amount = parseFloat(postagefee[0].postage_price);
    			 		$('#shippingMethod').val(amount);
    			 		$('#shipMethod').val(postagefee[0].postage_name);
    			 		$('#postageID').val(postagefee[0].postage_id);
    			 		calculateTotal();
//    				} else {
    					//alert('Error: Cannot be updated');
//    				}
    		    } else {
    		    	if (postagefee) {
    			 		var amount = parseFloat(postagefee.postage_price);
    			 		$('#shippingMethod').val(amount);
    			 		$('#shipMethod').val(postagefee.postage_name);
    			 		$('#postageID').val(postagefee.postage_id);
    			 		calculateTotal();
    				} else {
    					alert('Error: Cannot be updated');
    				}
    		    }
			}catch(err){
				console.log('TRY-CATCH error - '+err);
			}
			$('body').css('cursor','default'); 
	    },
		error: function(){
			$('body').css('cursor','default'); 
			console.log('AJAX error');
      	}
	});
}

function calculateTotal() {
	var subtotal = parseFloat($('#subtotal').attr('data-value'));
	var fee = $('#shippingMethod').val();
	if(fee){
		fee = parseFloat(fee);
		$('#shipping-fee').html('$'+ fee.toFixed(2));
	}else{
		fee = parseFloat(0);
		$('#shipping-fee').html('$0.00');
	}
	var total = subtotal + fee; 
	$('#total').html('$'+ total.formatMoney(2, '.', ',') +' <div class="small notbold">AUD (inc. GST)</div>');
	$('#shipMethod').val($('#shippingMethod option:selected').text());
};

function scrolltodiv(id) {
	$('html,body').animate({
		scrollTop : $(id).offset().top
	});
}
function isArray(what) {
    return Object.prototype.toString.call(what) === '[object Array]';
}
Number.prototype.formatMoney = function(c, d, t){
	var n = this, 
    c = isNaN(c = Math.abs(c)) ? 2 : c, 
    d = d == undefined ? "." : d, 
    t = t == undefined ? "," : t, 
    s = n < 0 ? "-" : "", 
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};