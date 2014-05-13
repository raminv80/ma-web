
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



function updateCart(PROCESS){
	$('body').css('cursor','wait');
	var datastring = $("#myestimate-form").serialize();
	$.ajax({
		type: "POST",
	    url: PROCESS,
		cache: false,
		data: 'action=updateCart&' + datastring,
		dataType: "json",
	    success: function(data) {
	    	try{
	    		
	    		
			}catch(err){
			}
			$('body').css('cursor','default'); 
	    },
		error: function(){
			$('body').css('cursor','default'); 
      	}
	});
}

function deleteItem(ID, PROCESS){
	$('body').css('cursor','wait');
	$( '#'+ ID ).fadeTo( "fast", 0.5 );
	var frmTkn = $("#formToken").val();
	$.ajax({
		type: "POST",
	    url: PROCESS,
		cache: false,
		data: 'action=DeleteItem&cartitem_id='+ID+'&formToken='+frmTkn,
		dataType: "json",
	    success: function(data) {
	    	try{
			 	if (data.response) {
			 		if ( parseInt(obj.itemsCount) > 0 ){
                        $( '#'+ ID ).hide('slow');
                    } 
				} 
			}catch(err){
			}
			$('body').css('cursor','default'); 
	    },
		error: function(){
			$('body').css('cursor','default'); 
      	}
	});
}

