{block name="head"}
<link href="/includes/css/jquery-ui.css" rel="stylesheet" media="screen">
{/block}
{block name=body}
<div id="maincart">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-10 col-md-offset-1 text-center" id="checkout">
        <h1>Login / Join</h1>
      </div>
    </div>
  </div>
</div>

<div id="carttext">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-10 col-md-offset-1 text-center">
        {$listing_content1}
      </div>
    </div>
  </div>
</div>

<div id="logincont">
      <div class="container">
      <div class="row">
      <!-- LOGIN SECTION  -->
      <div class="col-sm-6" id="login">
	    <div id="loginin">
	  	<div class="row">
		  	<div class="col-sm-10 col-md-8 col-sm-offset-1 col-md-offset-2  text-center">
		  		<h3>Already a member? Login</h3>
		  	</div>
		  	<div class="col-sm-10 col-md-8 col-sm-offset-1 col-md-offset-2  text-center">
		  		<div id="login">
		  		<form class="form-horizontal" id="login-form" data-attr-id="login-form" role="form" accept-charset="UTF-8" action="" method="post">
		  		<input type="hidden" value="login" name="action" id="action" />
		  		<input type="hidden" value="{$redirect}" name="redirect" class="redirect" />
		  		<input type="hidden" name="formToken" id="formToken" value="{$token}" />

		  	<div class="row">
		  		<div class="col-sm-12 form-group">
	              <label for="username" class="visible-ie-only">Membership Number</label>
	              <input type="text" value="" class="form-control" id="username" name="username" required>
				  <div class="error-msg help-block"></div>
	          </div>
		  	</div>

		  	<div class="row">
	          <div class="col-sm-12 form-group">
	              <label for="password" class="visible-ie-only">Password</label>
	                <input class="form-control showpassword" type="password" id="password1" name="pass" autocomplete="off" required/>
	                <a class="showhide" href="javascript:void(0);" onclick="if($(this).html() == 'Show'){ $(this).closest('div').find('input[type=password]').get(0).type='text';$(this).html('Hide'); }else{ $(this).closest('div').find('input[type=text]').get(0).type='password';$(this).html('Show'); }">Show</a>
					<div class="error-msg help-block"></div>
	            <span class="form-help-block col-sm-12"><a href="javascript:void(0)" onclick="$('#reset-email').val($('#email').val());$('#reset-pass').show('slow');">Forgot your password?</a></span>
	          </div>
		  	</div>

		  	<div class="row">
	          <div class="error-alert" style="display:none;">
	          	<div class="alert alert-danger fade in ">
					<button class="close" aria-hidden="true" type="button" onclick="$(this).closest('.error-alert').fadeOut('slow');">&times;</button>
					<strong></strong>
				</div>
	          </div>
		  	</div>

		  	<div class="row">
	          <div class="col-sm-12 form-group">
	              <div class="col-sm-offset-2 col-sm-8">
	                  <button type="submit" class="btn btn-red">Log In</button>
	              </div>
	            </div>
		  	</div>
	        </form>
		  	</div>
	      </div>
		  <div id="reset-pass" class="col-sm-10 col-md-8 col-sm-offset-1 col-md-offset-2 text-center" style="display:none;"><!-- RESET PASSWORD SECTION - Hidden by default -->
	        <h3 id="reset-pass-title">Reset password</h3>
	        <form class="form-horizontal" id="reset-pass-form" data-attr-id="reset-pass-form" role="form" accept-charset="UTF-8" action="" method="post">
	          <input type="hidden" value="resetPasswordToken" name="action" id="action" />
	          <input type="hidden" name="formToken" id="formToken" value="{$token}" />

		  	<div class="row">
	          <div class="col-sm-12 form-group">
	              <label for="email" class="control-label">Email</label>
	                  <input type="email" value="{if $post}{$post.email}{/if}" class="form-control" id="reset-email" name="email" required>
	              <span class="help-block"></span>
	          </div>
		  	</div>

		  	<div class="row">
	          <div class="col-sm-offset-3 error-alert" style="display:none;">
	          	<div class="alert alert-danger fade in ">
								<button class="close" aria-hidden="true" type="button" onclick="$(this).closest('.error-alert').fadeOut('slow');">&times;</button>
								<strong></strong>
							</div>
	          </div>
		  	</div>

		  	<div class="row">
            <div class="col-sm-offset-3 success-alert" style="display:none;">
            	<div class="alert alert-success fade in ">
								<button class="close" aria-hidden="true" type="button"  onclick="$(this).closest('.success-alert').fadeOut('slow');">&times;</button>
								<strong></strong>
							</div>
            </div>
		  	</div>

		  	<div class="row">
	          <div class="col-sm-12 form-group">
		          <div class="col-sm-12">
		              <button type="submit" class="btn btn-red">Reset Password</button>
		          </div>
	          </div>
		  	</div>
	        </form>
	      </div>
      </div>
	    </div>
      </div>


      <!-- REGISTER SECTION - Hidden by default -->
      <div class="col-sm-6" id="register">
	    <div id="registerin">
        <div class="row error-msg" id="register-form-error"></div>
        <div class="row">
		  	<div class="col-sm-10 col-md-8 col-sm-offset-1 col-md-offset-2 text-center">
		  		<h3>New member? Join now</h3>
		  	</div>
		  	<div class="col-sm-10 col-md-8 col-sm-offset-1 col-md-offset-2 text-center">
		  		{$listing_content2}
		  	</div>
		  	<div class="col-sm-10 col-md-8 col-sm-offset-1 col-md-offset-2 text-center">

		  	<form class="form-horizontal" id="register-form" data-attr-id="register-form" role="form" accept-charset="UTF-8" action="" method="post">
		  	<input type="hidden" value="create" name="action" id="action" />
		  	<input type="hidden" value="{$redirect}" name="redirect" class="redirect" />
		  	<input type="hidden" name="formToken" id="formToken" value="{$token}" />

		  	<div class="row">
		  	<div class="col-sm-12 form-group">
              <label for="gname" class="visible-ie-only">First Name<span>*</span>:</label>
                  <input type="text" value="{if $post}{$post.gname}{/if}" class="form-control" id="gname" name="gname" required>
				  <div class="error-msg help-block"></div>
			</div>
		  	</div>

		  	<div class="row">
			<div class="col-sm-12 form-group">
              <label for="surname" class="visible-ie-only">Last Name<span>*</span>:</label>
                  <input type="text" value="{if $post}{$post.surname}{/if}" class="form-control" id="surname" name="surname" required>
				  <div class="error-msg help-block"></div>
			</div>
		  	</div>

		  	<div class="row">
			<div class="col-sm-12 form-group">
              <label for="address" class="visible-ie-only">Address<span>*</span>:</label>
                  <input type="text" value="{if $post}{$post.address}{/if}" class="form-control" id="address" name="address" required>
				  <div class="error-msg help-block"></div>
			</div>
		  	</div>

		  	<div class="row">
			<div class="col-sm-12 form-group">
              <label for="suburb" class="visible-ie-only">Suburb<span>*</span>:</label>
                  <input type="text" value="{if $post}{$post.suburb}{/if}" class="form-control" id="suburb" name="suburb" required>
				  <div class="error-msg help-block"></div>
			</div>
		  	</div>

		  	<div class="row">
			<div class="col-sm-12 form-group">
              <label for="state" class="visible-ie-only">State<span>*</span>:</label>
                  <select class="form-control" id="state" name="state" required>
	                  <option value="">Select an option</option>
	                  <option value="ACT">ACT</option>
	                  <option value="NSW">NSW</option>
	                  <option value="NT">NT</option>
	                  <option value="QLD">QLD</option>
	                  <option value="SA">SA</option>
	                  <option value="VIC">VIC</option>
	                  <option value="WA">WA</option>
                  </select>
				  <div class="error-msg help-block"></div>
			</div>
		  	</div>

		  	<div class="row">
			<div class="col-sm-12 form-group">
              <label for="postcode" class="visible-ie-only">Postcode<span>*</span>:</label>
                  <input type="text" value="{if $post}{$post.postcode}{/if}" class="form-control" id="postcode" name="postcode" required>
				  <div class="error-msg help-block"></div>
			</div>
		  	</div>

		  	<div class="row">
			<div class="col-sm-12 form-group">
              <label for="email" class="visible-ie-only">Email<span>*</span>:</label>
                  <input type="email" value="{if $post}{$post.email}{/if}" class="form-control" id="reg-email" name="email" required>
				  <div class="error-msg help-block"></div>
			</div>
		  	</div>

		  	<div class="row">
			<div class="col-sm-12 form-group">
              <label for="confirm_email" class="visible-ie-only">Confirm email address<span>*</span>:</label>
                  <input type="text" value="{if $post}{$post.confirm_email}{/if}" class="form-control" id="confirm_email" name="confirm_email" required>
				  <div class="error-msg help-block"></div>
			</div>
		  	</div>

		  	<div class="row">
			<div class="col-sm-12 form-group">
              <label for="phone" class="visible-ie-only">Phone<span>*</span>:</label>
                  <input type="text" value="{if $post}{$post.phone}{/if}" class="form-control" id="phone" name="phone" required>
				  <div class="error-msg help-block"></div>
			</div>
		  	</div>

		  	<div class="row">
			<div class="col-sm-12 form-group">
              <label for="dob" class="visible-ie-only">Date of birth<span>*</span>:</label>
                  <input type="text" value="{if $post}{$post.dob}{/if}" class="form-control" id="dob" name="dob" required>
				  <div class="error-msg help-block"></div>
			</div>
		  	</div>

		  	<div class="row">
			<div class="col-sm-12 form-group">
              <label for="gender" class="visible-ie-only">Gender<span>*</span>:</label>
                  <select class="form-control" id="gender" name="gender" required>
	                  <option value="">Select an option</option>
	                  <option value="Male">Male</option>
	                  <option value="Female">Female</option>
                  </select>
				  <div class="error-msg help-block"></div>
			</div>
		  	</div>

		  	<div class="row">
			<div class="col-sm-12 form-group">
              <label for="password" class="visible-ie-only">Password<span>*</span>:</label>
                <input type="password" value="" class="form-control showpassword" id="password" name="password" autocomplete="off" required>
                <a class="showhide" href="javascript:void(0);" onclick="if($(this).html() == 'Show'){ $(this).closest('div').find('input[type=password]').get(0).type='text';$(this).html('Hide'); }else{ $(this).closest('div').find('input[type=text]').get(0).type='password';$(this).html('Show'); }">Show</a>
                <div class="passnote">Note: Your password must be at least 8 characters in length and contain at least 1 numeral and both upper and lower case letters.</div>
				<div class="error-msg help-block"></div>
			</div>
		  	</div>

		  	<div class="row">
			<div class="col-sm-12 form-group">
              <label for="hearabout" class="visible-ie-only">How did you hear about us?<span>*</span>:</label>
                  <select class="form-control" id="hearabout" name="hearabout" required>
	                  <option value="">Select an option</option>
	                  <option value="Social media">Social media</option>
	                  <option value="Radio">Radio</option>
	                  <option value="Newspaper">Newspaper</option>
                  </select>
				  <div class="error-msg help-block"></div>
			</div>
		  	</div>

		  	<div class="row">
			<div class="col-sm-12 form-group chkbx">
				<input type="checkbox" value="I agree" class="form-control" id="agree" name="agree" required />
				<label for="agree" class="chkbox visible-ie-only">I agree to MedicAlert Foundation’s <a href="#">terms and conditions</a>.<span>*</span></label>
				<div class="error-msg help-block"></div>
			</div>
		  	</div>

		  	<div class="row">
			<div class="col-sm-12 form-group">
			<div class="error-alert" style="display:none;">
          	<div class="alert alert-danger fade in ">
							<button class="close" aria-hidden="true" type="button" onclick="$(this).closest('.error-alert').fadeOut('slow');">&times;</button>
							<strong></strong>
						</div>
			</div>
			</div>
		  	</div>

		  	<div class="row">
			<div class="col-sm-12 form-group">
              <div class="col-sm-12">
                  <button type="submit" class="btn btn-red">Sign Up</button>
              </div>
            </div>
		  	</div>

			</form>
		  	</div>
      </div>
	    </div>
    </div>

    {if $allowGuest}
			<hr class="or-separator">
			<div class="row">	<!-- CONTINUE AS GUEST SECTION -->
				<a href="/store/checkout" class="btn btn-info" >Continue as Guest</a>
			</div>
		{/if}

		</div>
      </div>

    </div>

<div id="greyblock1" class="login">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<p class="bigtxt">For just $32 a year, your MedicAlert membership gives you:</p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4 text-center benefits">
				<img src="/images/benefit1.png" alt="Protection for 12 months" class="img-responsive" />
				<p>Protection for 12 months</p>
			</div>
			<div class="col-sm-4 text-center benefits">
				<img src="/images/benefit2.png" alt="24/7 emergency service access to your medical information" class="img-responsive" />
				<p>24/7 emergency service access to your medical information</p>
			</div>
			<div class="col-sm-4 text-center benefits">
				<img src="/images/benefit3.png" alt="Exclusive member only offers" class="img-responsive" />
				<p>Exclusive member only offers</p>
			</div>
			<div class="col-sm-4 text-center benefits">
				<img src="/images/benefit4.png" alt="Unlimited wallet and fridge cards listing your details" class="img-responsive" />
				<p>Unlimited wallet and fridge cards listing your details</p>
			</div>
			<div class="col-sm-4 text-center benefits">
				<img src="/images/benefit5.png" alt="Secure online access to your electronic health record " class="img-responsive" />
				<p>Secure online access to your electronic health record </p>
			</div>
			<div class="col-sm-4 text-center benefits">
				<img src="/images/benefit6.png" alt="Support from our Membership Services team" class="img-responsive" />
				<p>Support from our Membership Services team</p>
			</div>
		</div>
	</div>
</div>

{/block}

{* Place additional javascript here so that it runs after General JS includes *}
{block name=tail}
{literal}
<script src="/includes/js/jquery-ui.js"></script>
<script src="/includes/js/jquery.selectBoxIt.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){

      $('#login-form').validate();
      $('#register-form').validate();
      $('#reset-pass-form').validate({
        onkeyup: false,
        onclick: false
      });
		$("select").selectBoxIt();
	    $( "#dob" ).datepicker( {  dateFormat: "dd/mm/yy"} );


/*
      $('#confirm_email').rules("add", {
            required: true,
            equalTo: '#reg-email',
            messages: {
              equalTo: "The emails you have entered do not match. Please check them."
            }
       });  */
    });

    function FBlogin(){
		var datastring = $("#facebook-form").serialize();
		$.ajax({
			type: "POST",
		    url: "/includes/processes/processes-facebook.php",
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




