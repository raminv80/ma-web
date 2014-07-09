{block name=body}

<div id="maincont">
      <div class="container">
      <div class="row">
      <!-- LOGIN SECTION  -->
      <div class="col-sm-5" id="login">
        <h3>Existing Customer</h3>
        <div id="login" class="row">
	        <form class="form-horizontal" id="login-form" data-attr-id="login-form" role="form" accept-charset="UTF-8" action="" method="post">
	          <input type="hidden" value="login" name="action" id="action" /> 
	          <input type="hidden" value="{$redirect}" name="redirect" class="redirect" /> 
	          <input type="hidden" name="formToken" id="formToken" value="{$token}" />
	          <div class="form-group">
	              <label for="email" class="col-sm-3 control-label">Email</label>
	              <div class="col-sm-9">
	                  <input type="email" value="{if $post}{$post.email}{/if}" class="form-control email req" id="email" name="email" required>
	                  <span class="help-block"></span>
	            </div>
	          </div>
	          <div class="form-group">
	              <label for="password" class="col-sm-3 control-label">Password</label>
	              <div class="col-sm-9">
	                <input class="form-control" type="password" id="password1" name="pass" autocomplete="off" required/>
                  <a class="showhide" style="line-height: 34px;position: absolute;right: 20px;top: 0;" href="javascript:void(0);" onclick="if($(this).html() == 'Show'){ $(this).closest('div').find('input[name=pass]').get(0).type='text';$(this).html('Hide'); }else{ $(this).closest('div').find('input[name=pass]').get(0).type='password';$(this).html('Show'); }">Show</a>
	                <span class="help-block"></span>
	            </div>
	            <span class="form-help-block col-sm-offset-3 col-sm-9"><a href="javascript:void(0)" onclick="$('#reset-pass').show('slow');">Forgot your password?</a></span>
	          </div>
	          <div class="row error-msg" id="login-form-error"></div>
	          <div class="form-group">
	              <div class="col-sm-offset-3 col-sm-9">
	                  <button type="submit" class="btn btn-primary">Log In</button>
	              </div>
	            </div>
	        </form>
	      </div>
        <div id="reset-pass" style="display:none;"><!-- RESET PASSWORD SECTION - Hidden by default -->
	        
	        <h3>Reset password</h3>
	        <form class="form-horizontal" id="reset-pass-form" data-attr-id="reset-pass-form" role="form" accept-charset="UTF-8" action="" method="post">
	          <input type="hidden" value="resetPassword" name="action" id="action" /> 
	          <input type="hidden" name="formToken" id="formToken" value="{$token}" />
	          <div class="form-group">
	              <label for="email" class="col-sm-4 control-label">Email</label>
	              <div class="col-sm-8">
	                  <input type="email" value="{if $post}{$post.email}{/if}" class="form-control" id="email" name="email" required>
	              <span class="help-block"></span>
	            </div>
	          </div>
	          <div class="form-group">
		          <div class="col-sm-offset-4 col-sm-8" >
		              <button type="submit" class="btn btn-primary">Reset Password</button>
		          </div>
	          </div>
	          <div class="row error-msg" id="reset-pass-form-error"></div>
            <div class="row success-msg" id="reset-pass-form-success"></div>
	        </form>
	      </div>
	      
	      {if $allowGuest}
					<hr class="or-separator">
					<div class="row">	<!-- CONTINUE AS GUEST SECTION -->
						<a href="/store/checkout" class="btn btn-info" >Continue as Guest</a>
					</div>
				{/if}
				<hr class="or-separator">
				<div class="row" id="social-login">	<!-- FACEBOOK LOGIN SECTION -->
					 <form id="facebook-form" action="/process/user" method="post">
						<input type="hidden" value="FBlogin" name="action" id="action" /> <!--  onclick="$('#facebook-form').submit();" -->
						<input type="hidden" name="formToken" id="formToken" value="{$token}" />
						<input type="hidden" value="{$redirect}" name="redirect" class="redirect" /> 
						<a href='javascript:void(0)' onclick="FBlogin();"><img src="/images/loginFB.gif" alt="login with facebook"></a>
					</form> 
					<!--<fb:login-button autologoutlink="true" data-scope="email, user_birthday, user_location" >Log In with Facebook</fb:login-button>
					-->
				</div>
      </div>
                  
      
      
      <!-- REGISTER SECTION - Hidden by default -->
      <div class="col-sm-5 col-sm-offset-1" id="register">
        <div class="row error-msg" id="register-form-error"></div>
        <div class="row">
          <h3>Create an account</h3>
        </div>
        <form class="form-horizontal" id="register-form" data-attr-id="register-form" role="form" accept-charset="UTF-8" action="" method="post">
          <input type="hidden" value="create" name="action" id="action" /> 
          <input type="hidden" value="{$redirect}" name="redirect" class="redirect" /> 
          <input type="hidden" name="formToken" id="formToken" value="{$token}" />
          <div class="form-group">
              <label for="gname" class="col-sm-4 control-label">First Name</label>
              <div class="col-sm-8">
                  <input type="text" value="{if $post}{$post.gname}{/if}" class="form-control" id="gname" name="gname" required>
              <span class="help-block"></span>
            </div>
          </div>
          <div class="form-group">
              <label for="surname" class="col-sm-4 control-label">Last Name</label>
              <div class="col-sm-8">
                  <input type="text" value="{if $post}{$post.surname}{/if}" class="form-control" id="surname" name="surname" required>
              <span class="help-block"></span>
            </div>
          </div>
          <div class="form-group">
              <label for="email" class="col-sm-4 control-label">Email</label>
              <div class="col-sm-8">
                  <input type="email" value="{if $post}{$post.email}{/if}" class="form-control" id="reg-email" name="email" required>
              <span class="help-block"></span>
            </div>
          </div>
          <div class="form-group">
              <label for="confirm_email" class="col-sm-4 control-label">Re-enter Email</label>
              <div class="col-sm-8">
                  <input type="text" value="{if $post}{$post.confirm_email}{/if}" class="form-control" id="confirm_email" name="confirm_email" required>
              <span class="help-block"></span>
            </div>
          </div>
          <div class="form-group">
              <label for="password" class="col-sm-4 control-label">Password</label>
              <div class="col-sm-8">
                <input type="password" value="" class="form-control" id="password" name="password" autocomplete="off" required>
                <a class="showhide" style="line-height: 34px;position: absolute;right: 20px;top: 0;" href="javascript:void(0);" onclick="if($(this).html() == 'Show'){ $(this).closest('div').find('input[name=password]').get(0).type='text';$(this).html('Hide'); }else{ $(this).closest('div').find('input[name=password]').get(0).type='password';$(this).html('Show'); }">Show</a>
                <span class="help-block"></span>
              </div>
          </div>
          <div class="form-group">
              <label for="confirm_password" class="col-sm-4 control-label">Re-enter Password</label>
              <div class="col-sm-8">
                <input type="password" value="" class="form-control" id="confirm_password" name="confirm_password" autocomplete="off" required>
                <a class="showhide" style="line-height: 34px;position: absolute;right: 20px;top: 0;" href="javascript:void(0);" onclick="if($(this).html() == 'Show'){ $(this).closest('div').find('input[name=confirm_password]').get(0).type='text';$(this).html('Hide'); }else{ $(this).closest('div').find('input[name=confirm_password]').get(0).type='password';$(this).html('Show'); }">Show</a>
              <span class="help-block"></span>
            </div>
          </div>
          <div class="form-group">
              <div class="col-sm-offset-4 col-sm-8">
                  <button type="submit" class="btn btn-primary">Sign Up</button>
              </div>
            </div>
        </form>
      </div>
    </div>
  </div>
  
  
    </div>
    
{/block}

{* Place additional javascript here so that it runs after General JS includes *}
{block name=tail}
{literal}
<script type="text/javascript">
    $(document).ready(function(){

      $('#login-form').validate();
      $('#register-form').validate();
      $('#reset-pass-form').validate({
        onkeyup: false,
        onclick: false
      });
      
      $('#confirm_password').rules("add", {
            required: true,
            equalTo: '#password',
            messages: {
              equalTo: "The passwords you have entered do not match. Please check them."
            }
       });

      $('#confirm_email').rules("add", {
            required: true,
            equalTo: '#reg-email',
            messages: {
              equalTo: "The emails you have entered do not match. Please check them."
            }
       }); 
    });

    function FBlogin(){
		var datastring = $("#facebook-form").serialize();
		$.ajax({
			type: "POST",
		    url: "/process/user",
			cache: false,
			data: datastring,
			dataType: "html",
		    success: function(data) {
		    	try{
		    		var obj = $.parseJSON(data);
				 	var msg = obj.message;
				 	var login_url = obj.login_url;  
				 	var error = obj.error;  

				 	if (login_url) {
				 		if (obj.new_window) {
					 		var left  = ($(window).width()/2)-(500/2),
					 	    	top   = ($(window).height()/2)-(270/2),
					 	     	popup = window.open (login_url, "Login_with_Facebook", "width=500, height=270, top="+top+", left="+left);
				 		} else {
				 			window.location.href = login_url;
						}    
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
  </script>{/literal}
{/block}


                    
            