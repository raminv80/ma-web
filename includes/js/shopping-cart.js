
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
				 	var msg = obj.message;
				 	var items = obj.itemsCount;  
				 	$('#shopping-cart').html(items);
				 	$('body').css('cursor','default');
				 	alert (msg);
		    		
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
		alert('Quantity must be greater than 0')
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
		    		var total = obj.total;
				 	if (subtotals) {
				 		$.each(subtotals, function(id, value){
				 			amount = parseFloat(value);
				 			$('#subtotal-'+id).html('$'+amount.toFixed(2));
				 		});
				 		$.each(total, function(id, value){
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
	var datastring = $("#product-form").serialize();
	$.ajax({
		type: "POST",
	    url: "/process/cart",
		cache: false,
		data: 'action=DeleteItem&cartitem_id='+ID,
		dataType: "html",
	    success: function(data) {
	    	try{
	    		var obj = $.parseJSON(data);
			 	var response = obj.response;
                                var total = obj.total;
			 	if (response) {
			 		if ( $('.product-item:visible').length = 1 ){
                                            location.reload();
                                        } else {
                                            $( '#'+ ID ).hide('slow');
                                             $.each(total, function(id, value){
				 			amount = parseFloat(value);
				 			$('#'+id).html('$'+amount.toFixed(2));
				 		});
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