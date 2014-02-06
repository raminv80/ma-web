
$(document).ready(function(){
			
	/* $('.modifier').trigger('change'); */
	calculatePrice();
	
});

$('.modifier').change(function() {
	/* alert($(this).val()); */
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

function sameAddress() {
	$('#shipping-subform').toggle();
	if ($('#shipping-subform:visible').length > 0) {
		$('.shipping-req').attr('required', 'required');
		$('.shipping-select-req').addClass('required');
	} else {
		$('.shipping-req').removeAttr('required');
		$('.shipping-select-req').removeClass('required');
	}
}

function addCart(){
	if ($('#quantity').val() > 0 ) {
		$('body').css('cursor','wait');
		var datastring = $("#product-form").serialize();
		$.ajax({
			type: "POST",
		    url: "/process/cart",
			cache: false,
			data: datastring,
			dataType: "html",
		    success: function(data) {
		    	try{
		    		var obj = $.parseJSON(data);
				 	$('#nav-itemNumber').html(obj.itemsCount);
				 	$('#nav-subtotal').html('$'+obj.subtotal);
				 	$('body').css('cursor','default');
				 	$('#shop-cart-btn').attr('data-content', obj.popoverShopCart );
				 	$('#shop-cart-btn').popover('show');
				 	setTimeout(function() {
				 		$('#shop-cart-btn').popover('hide');
				    }, 3000);
				 	
				 	/*alert (obj.message);*/
		    		
				}catch(err){
					$('body').css('cursor','default'); 
					console.log('TRY-CATCH error');
				}
		    },
			error: function(){
				$('body').css('cursor','default'); 
				console.log('AJAX error');
          	}
		});
	} else {
		alert('Quantity must be greater than 0');
	}
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
                    $('#nav-itemNumber').html(obj.itemsCount);
				 	$('#nav-subtotal').html('$'+obj.totals['subtotal']);
				 	$('#shop-cart-btn').attr('data-content', obj.popoverShopCart );
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
				 	$('body').css('cursor','default'); 
				}catch(err){
					$('body').css('cursor','default'); 
					console.log('TRY-CATCH error');
				}
		    },
			error: function(){
				$('body').css('cursor','default'); 
				console.log('AJAX error');
          	}
		});
	
}

function deleteItem(ID){
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
                $('#nav-itemNumber').html(obj.itemsCount);
                $('#nav-subtotal').html('$'+obj.totals['subtotal']);
			 	$('#shop-cart-btn').attr('data-content', obj.popoverShopCart );
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
	    },
		error: function(){
			$( '#'+ ID ).fadeTo( "slow", 1 );
			console.log('AJAX error');
      	}
	});
	
}