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
            {if $error}
		<div class="row" style="margin:20px; color:#ff0000">{$error}</div>
            {/if}
	{if !$user.id}
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
                                        <span class="help-block"><a href="javascript:void(0)" onclick="$('#reset-pass').show('slow');$('#login').hide('slow');">Forgotten your password?</a></span>
					</div>
				</div>
			 	<div class="form-group">
			    	<div class="col-sm-offset-2 col-sm-10">
			      		<button onclick="$('#login-form').submit();" class="btn btn-primary">Log In</button>
			    	</div>
			  	</div>
			</form>
			<div class="row" style="margin:20px;">
				 <form id="facebook-form" action="/process/user" method="post">
					<input type="hidden" value="FBlogin" name="action" id="action" /> <!--  onclick="$('#facebook-form').submit();" -->
					<a href='javascript:void(0)' onclick="FBlogin();"><img src="/images/loginFB.gif" alt="login with facebook"></a>
				</form> 
				<!--<fb:login-button autologoutlink="true" data-scope="email, user_birthday, user_location" >Log In with Facebook</fb:login-button>
				-->
			</div>
			<div class="row" style="margin:20px;">
				<button class="btn btn-success" id="submit-login" onclick="$('#register').show('slow');$('#login').hide('slow');" >New User</button>
			</div>
			
		</div>
		
                <!-- RESET PASSWORD SECTION - Hidden by default -->
		<div class="row" id="reset-pass" style="margin:40px; display:none;">
                    <div class="row">
                        <h3>Reset password</h3>
                    </div>
			<form class="form-horizontal" id="reset-pass-form" role="form" accept-charset="UTF-8" action="/process/user" method="post">
				<input type="hidden" value="resetPassword" name="action" id="action" /> 
				<div class="form-group">
				    <label for="email" class="col-sm-2 control-label">Email</label>
				    <div class="col-sm-10">
				      	<input type="email" value="{if $post}{$post.email}{/if}" class="form-control" id="email" name="email" required>
					</div>
				</div>
			</form>
			<div class="col-md-offset-4" style="margin:20px;">
				<button class="btn btn-default" onclick="$('#reset-pass').hide('slow');$('#login').show('slow');" >Back</button>
                                <button class="btn btn-primary" id="submit-register" onclick="$('#reset-pass-form').submit();"  >Submit</button>
			</div>
		</div>
		
		<!-- REGISTER SECTION - Hidden by default -->
		<div class="row" id="register" style="margin:40px; display:none;">
                    <div class="row">
                        <h3>Create an account</h3>
                    </div>
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
				<button class="btn btn-success" onclick="$('#register').hide('slow');$('#login').show('slow');" >I'm already registered</button>
			</div>
		</div>
	
        {else}
              {if $user.social_id}                          
              <div class="row" id="update-pass" style="margin:40px; ">
                    <div class="row">
                        <h3>{$user.gname}, you're logged in using Facebook!</h3>
                    </div>
                    <div class="row">
                        <a title="Log Out"  href="/process/user?logout=true"><img src="/images/logoutFB.png" alt="Log out Facebook"></a>
                    </div>
              </div>
              {else}
		<!-- UPDATE PASSWORD SECTION  -->
		<div class="row" id="update-pass" style="margin:40px;">
                    
                    <div class="row">
                        <h3>Account Settings: <small>Change Password</small> </h3>
                    </div>
                    
			<form class="form-horizontal" id="update-pass-form" role="form" accept-charset="UTF-8" action="/process/user" method="post">
				<input type="hidden" value="updatePassword" name="action" id="action" /> 
				
				<div class="form-group">
				    <label for="password" class="col-sm-2 control-label">Old Password</label>
				    <div class="col-sm-10">
				    	<input type="password" value="" class="form-control" id="old_password" name="old_password" required>
                                    </div>
                                </div>
                                 <div class="form-group">
				    <label for="password" class="col-sm-2 control-label">New Password</label>
				    <div class="col-sm-10">
				    	<input type="password" value="" class="form-control" id="password" name="password" required>
			    	</div>
				</div>
				<div class="form-group">
				    <label for="confirm_password" class="col-sm-2 control-label">Re-enter New Password</label>
				    <div class="col-sm-10">
				    	<input type="password" class="form-control req" id="confirm_password" name="confirm_password" required>
					</div>
				</div>
			 	<div class="form-group">
			    	<div class="col-sm-offset-2 col-sm-10">
			      		<button onclick="$('#update-pass-form').submit();" class="btn btn-primary">Update</button>
			    	</div>
			  	</div>
			</form>
			
		</div>
		
        
            {/if}
        
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
		
		
		function FBlogin(){
			$.ajax({
				type: "POST",
			    url: "/process/user",
				cache: false,
				data: 'action=FBlogin',
				dataType: "html",
			    success: function(data) {
			    	try{
			    		var obj = $.parseJSON(data);
					 	var msg = obj.message;
					 	var login_url = obj.login_url;  
					 	var error = obj.error;  

					 	if (login_url) {
					 		doRedirect = true;
					 		var left  = ($(window).width()/2)-(500/2),
					 	    	top   = ($(window).height()/2)-(270/2),
					 	     	popup = window.open (login_url, "Login with Facebook", "width=500, height=270, top="+top+", left="+left);
					 	    
						}else if (msg && error) {
						 	alert (msg);
					 	}
					}catch(err){
						console.log('TRY-CATCH error');
					}
			    },
				error: function(){
					console.log('AJAX error');
	          	}
			});
			
		}

		function redirectWin(url) {
			window.location.replace(url);
		}
		
		
	</script>
{/block}
