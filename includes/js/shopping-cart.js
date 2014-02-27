if (jQuery.validator) {
	  jQuery.validator.setDefaults({
	    errorClass: 'has-error',
	    validClass: 'has-success',
	    ignore: "",
	    highlight: function (element, errorClass, validClass) {
	      $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
	      /*$('#error-text').html('Error, please check the red highlighted fields and submit again.');*/
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
	    	  var formID = $(form).attr('id');
	          switch ( formID ) {
	          	
	          	case 'product-form': 
	          		addCart(formID);
	          		break;
	          		
	          	case 'login-form':
	        	case 'create-form':
	        		userLogin(formID);
	        		break;
	        	
	        	case 'reset-pass-form': 
	        		resetPass(formID);
	        		break;
	        		
	          	default:
	          		form.submit();
	          }
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
			 		$('#login-error').html(obj.error).show();
			 		
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
			 		$('#login-error').html(obj.error).show();
			 		$('#login-success').hide();
			 	} else {
			 		$('#login-success').html(obj.success).show();
			 		$('#login-error').hide();
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
			 	$('.nav-itemNumber').html(obj.itemsCount);
			 	$('.nav-subtotal').html('$'+obj.subtotal);
			 	$('#shop-cart-btn').html( obj.popoverShopCart );
			 	$('#shop-cart-btn').slideDown();
			 	setTimeout(function() {
			 		$('#shop-cart-btn').slideUp();
			    }, 3000);
			 	
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
		    		var subtotals = obj.subtotals;
		    		var totals = obj.totals;
                    $('.nav-itemNumber').html(obj.itemsCount);
				 	$('.nav-subtotal').html('$'+obj.totals['subtotal']);
				 	$('#shop-cart-btn').html( obj.popoverShopCart );
				 	if (subtotals) {
				 		$.each(subtotals, function(id, value){
				 			amount = parseFloat(value);
				 			$('#subtotal-'+id).html('$'+amount.toFixed(2));
				 		});
				 		$.each(totals, function(id, value){
				 			amount = parseFloat(value);
				 			$('#'+id).html('$'+amount.toFixed(2));
				 		});
						/* RECALCULATE SUBTOTAL/TOTAL */
					} else {
						alert('Error: Cannot be updated');
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