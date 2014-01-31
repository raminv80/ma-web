{block name=body}
	<header>
		<div id="headout" class="headerbg">
				<div id="videobox">
					<div class="container">
						<div class="row-fluid">
							<div class="span7">
					  			{include file='breadcrumbs.tpl'}
					  			<h3 class="toptitle">{$product_name}</h3>
				  			</div>
						</div>
					</div>
				</div>
			</div>
	</header>
	<div class="container">
	{if !$user.id}
		{if $error}
		<div class="row" style="margin:20px; color:#ff0000">{$error}</div>
		{/if}
		<!-- LOGIN SECTION  -->
		<div class="row" id="login" style="margin:40px;">
			<form class="form-horizontal" id="login-form" role="form" accept-charset="UTF-8" action="/process/user" method="post">
				<input type="hidden" value="login" name="action" id="action" /> 
				<div class="form-group">
				    <label for="email" class="col-sm-2 control-label">Email</label>
				    <div class="col-sm-10">
				      	<input type="email" value="{if $post}{$post.email}{/if}" class="form-control" id="email" name="email" required>
					</div>
				</div>
				<div class="form-group">
				    <label for="password" class="col-sm-2 control-label">Password</label>
				    <div class="col-sm-10">
				    	<input type="password" value="" class="form-control" id="pass" name="pass" required>
					</div>
				</div>
			 	<div class="form-group">
			    	<div class="col-sm-offset-2 col-sm-10">
			      		<button onclick="$('#login-form').submit();" class="btn btn-primary">Log In</button>
			    	</div>
			  	</div>
			</form>
			<div class="row" style="margin:20px;">
				<!-- <form id="facebook-form" action="/process/user" method="post">
					<input type="hidden" value="FBlogin" name="action" id="action" /> 
					<a href='javascript:void(0)' onclick="$('#facebook-form').submit();"><img src="/images/loginFB.gif" alt="login with facebook"></a>
				</form> -->
				<fb:login-button autologoutlink="true" data-scope="email, user_birthday, user_location" >Log In with Facebook</fb:login-button>
				
			</div>
			<div class="row" style="margin:20px;">
				<button class="btn btn-success" id="submit-login" onclick="$('#register').show('slow');$('#login').hide('slow');" >New User</button>
			</div>
			
		</div>
		
		
		<!-- REGISTER SECTION  -->
		<div class="row" id="register" style="margin:40px; display:none;">
			<form class="form-horizontal" id="register-form" role="form" accept-charset="UTF-8" action="/process/user" method="post">
				<input type="hidden" value="create" name="action" id="action" /> 
				<div class="form-group">
				    <label for="gname" class="col-sm-2 control-label">Given Name</label>
				    <div class="col-sm-10">
				      	<input type="text" value="{if $post}{$post.gname}{/if}" class="form-control" id="gname" name="gname" required>
					</div>
				</div>
				<div class="form-group">
				    <label for="surname" class="col-sm-2 control-label">Surname</label>
				    <div class="col-sm-10">
				      	<input type="text" value="{if $post}{$post.surname}{/if}" class="form-control" id="surname" name="surname" required>
					</div>
				</div>
				<div class="form-group">
				    <label for="email" class="col-sm-2 control-label">Email</label>
				    <div class="col-sm-10">
				      	<input type="email" value="{if $post}{$post.email}{/if}" class="form-control" id="email" name="email" required>
					</div>
				</div>
				<div class="form-group">
				    <label for="password" class="col-sm-2 control-label">Password</label>
				    <div class="col-sm-10">
				    	<input type="password" value="" class="form-control" id="password" name="password" required>
			    	</div>
				</div>
				<div class="form-group">
				    <label for="confirm_password" class="col-sm-2 control-label">Re-enter Password</label>
				    <div class="col-sm-10">
				    	<input type="password" class="form-control req" id="confirm_password" name="confirm_password" required>
					</div>
				</div>
			 	<div class="form-group">
			    	<div class="col-sm-offset-2 col-sm-10">
			      		<button onclick="$('#register-form').submit();" class="btn btn-primary">Sign Up</button>
			    	</div>
			  	</div>
			</form>
			<div class="col-md-offset-4" style="margin:20px;">
				<button class="btn btn-success" id="submit-register" onclick="$('#register').hide('slow');$('#login').show('slow');" >I'm already registered</button>
			</div>
		</div>
	{/if}	
		
	</div>
	
	<script type="text/javascript">


	
		if (jQuery.validator) {
		  jQuery.validator.setDefaults({
		    debug: true,
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
			      $('#submit-register').hide();
			      $('#processing-btn').show();
		          form.submit();
		      }
		    }
		  });
		}

		$(document).ready(function(){

			$('#login-form').validate();
			$('#register-form').validate();
			
			$('#confirm_password').rules("add", {
			      required: true,
			      equalTo: '#password',
			      messages: {
			        equalTo: "The passwords you have entered do not match. Please check them."
			      }
			 });
		})
		
	</script>
{/block}
