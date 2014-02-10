
<script src="/admin/includes/js/jquery.validate.min.js"></script>

<script type="text/javascript">
if (jQuery.validator) {
	  jQuery.validator.setDefaults({
	    debug: false,
	    errorClass: 'has-error',
	    validClass: 'has-success',
	    ignore: "",
	    highlight: function (element, errorClass, validClass) {
	      $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
	      $('#error-text').html('<label class="control-label">Error, please check the red highlighted fields and submit again.</label>');
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

	    	  $('body').css('cursor','wait');
	    		var datastring = $("#Edit_Record").serialize();
	    		$.ajax({
	    			type: "POST",
	    		    url: "/admin/includes/processes/processes-record.php",
	    			cache: false,
	    			data: datastring,
	    			dataType: "html",
	    		    success: function(data) {
	    		    	try{
	    		    		var obj = $.parseJSON(data);
	    				 	var notice = obj.notice;
	    				 	$('#'+ notice).removeClass('hidden');
	    					setTimeout(function(){
	    						$('#'+ notice).fadeOut('slow', function() {
	    							$('#'+ notice).addClass('hidden');
	    						});
	    			    	},6000);
	    					$.each(obj.IDs, function(k, v) {
	    					    $('input[name="'+k+'"]').val(v);
	    					});
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
	    }
	  });
	}

$(document).ready(function(){
	$('#Edit_Record').validate();
});

</script>
