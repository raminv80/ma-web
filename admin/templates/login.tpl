{block name=body}
<form id="login-form" accept-charset="UTF-8" method="post">
	<input type="hidden" value="{$redirect}" name="redirect" class="redirect" /> 
	<div class="row form-group">
		<div class="col-sm-6 col-sm-offset-3 text-center">
			<img src="/admin/images/themlogo.jpg" alt="" class="img-responsive" id="loginlogo" />
		</div>
	</div>
	<div class="row">
	<div class="col-sm-6 col-sm-offset-3 text-center" id="loginbox">
	<div class="row form-group">
		<label  class="col-sm-4 control-label" for="email">Email:</label>
		<div class="col-sm-8">
			<input type="email" class="form-control" name="email" id="email" class="text-box-login" required>
		</div>
	</div>
	<div class="row form-group">
		<label  class="col-sm-4 control-label" for="password" >Password:</label>
		<div class="col-sm-8">
			<input type="password" class="form-control" name="password" id="password" class="text-box-login-password" required>
		</div>
	</div>
	<div class="col-sm-offset-4 error-alert" style="display:none;">
    <div class="alert alert-danger fade in ">
			<button class="close" aria-hidden="true" type="button" onclick="$(this).closest('.error-alert').fadeOut('slow');">&times;</button>
			<strong></strong>
		</div>
  </div>
	<div class="form-group">
		<div class="col-sm-4">
			<input type="hidden" name="formToken" id="formToken" value="{$token}" />
		</div>
		<div class="col-sm-8 form-actions text-right">
			<button class="btn login_button" type="submit" title="login">Login</button>
		</div>
	</div>
	</div>
	</div>
	<div id="log"></div>
</form>

<script src="/admin/includes/js/jquery.validate.min.js"></script>
<script type="text/javascript">
if (jQuery.validator) {
	  jQuery.validator.setDefaults({
	    errorClass: 'has-error',
	    validClass: 'has-success',
	    ignore: "",
	    highlight: function (element, errorClass, validClass) {
	      $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
	    },
	    unhighlight: function (element, errorClass, validClass) {
	      $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
	    },
	    errorPlacement: function (error, element) {
	     
	    },
	    submitHandler: function (form) {
	    	var formID = $(form).attr('id');
	    	Login(formID);
	    }
	  });
}

$(document).ready(function(){
	$('#login-form').validate({
		onkeyup: false
	});
});

function Login(formID){
	$('body').css('cursor', 'wait');
	$('.btn-primary').addClass('disabled');
	var datastring = $('#'+formID).serialize();
	$.ajax({
		type : "POST",
		url : "/admin/includes/processes/processes-login.php",
		cache : false,
		data : datastring,
		dataType : "html",
		success : function(data, textStatus) {
			try {
				var obj = $.parseJSON(data);
				if (obj.error) {
			 		$('#'+formID).find('.error-alert').find('strong').html(obj.error);
			 		$('#'+formID).find('.error-alert').fadeIn('slow'); 
			 	} else if (obj.success){
			 		location.assign(obj.redirect);
			 	}
			} catch (err) {
			}
			$('body').css('cursor', 'default');
			$('.btn-primary').removeClass('disabled');
		},
		error: function(){
			$('body').css('cursor','default'); 
			$('.btn-primary').removeClass('disabled');
			console.log('AJAX error');
	  }
	});
}
</script>
{/block}