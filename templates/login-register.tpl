{block name="head"}
{printfile file='/includes/css/jquery-ui.css' type='style'}
{/block} {block name=body}
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
      <div class="col-sm-12 col-md-10 col-md-offset-1 text-center">{$listing_content1}</div>
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
               <div id="login-form-wrapper">
               <h3><b>Already a member? Login</b></h3>
                <p>If you already have a MedicAlert medical ID but haven't signed up online, please <a href="javascript:void(0)" onclick="$('#logincont').hide(); $('#existing-member-wrapper').fadeIn();">click here</a> to activate your account.</p>
                <br>
                <form class="form-horizontal" id="login-form" data-attr-id="login-form" role="form" accept-charset="UTF-8" action="" method="post">
                  <input type="hidden" value="login" name="action" id="action" />
                  <input type="hidden" value="{$redirect}" name="redirect" class="redirect" />
                  <input type="hidden" name="formToken" id="formToken" value="{$token}" />

                  <div class="row">
                    <div class="col-sm-12 form-group">
                      <label for="username" class="visible-ie-only">Membership Number</label>
                      <input type="text" value="" class="form-control" id="username" pattern="[0-9]*" name="username" required>
                      <div class="error-msg help-block"></div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-12 form-group">
                      <label for="password" class="visible-ie-only">Password</label>
                      <input class="form-control showpassword" type="password" id="password1" name="pass" autocomplete="off" required />
                      <a class="showhide" href="javascript:void(0);" onclick="if($(this).html() == 'Show'){ $(this).closest('div').find('input[type=password]').get(0).type='text';$(this).html('Hide'); }else{ $(this).closest('div').find('input[type=text]').get(0).type='password';$(this).html('Show'); }">Show</a>
                      <div class="error-msg help-block"></div>
                      <span class="form-help-block"><a href="javascript:void(0)" onclick="$('#reset-username').val($('#username').val());$('#login-form-wrapper').hide();$('#reset-pass').fadeIn('slow');">Forgot your password?</a></span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="error-alert" style="display: none;">
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
            <div id="reset-pass" class="col-sm-10 col-md-8 col-sm-offset-1 col-md-offset-2 text-center" style="display: none;">
              <!-- RESET PASSWORD SECTION - Hidden by default -->
              <h3 id="reset-pass-title">Reset password</h3>
              <form class="form-horizontal" id="reset-pass-form" role="form" accept-charset="UTF-8" action="" method="post">
                <input type="hidden" value="forgotPassword" name="action" id="action" />
                <input type="hidden" name="formToken" id="formToken" value="{$token}" />

                <div class="row">
                  <div class="col-sm-12 form-group">
                    <label for="reset-username" class="control-label">Membership Number</label>
                    <input type="text" value="" class="form-control" id="reset-username" name="username" pattern="[0-9]*" required>
                    <span class="help-block"></span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 form-group">
                    <label for="reset-email" class="control-label">Email</label>
                    <input type="email" value="" class="form-control" id="reset-email" name="email" required>
                    <span class="help-block"></span>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12 error-alert" style="display: none;">
                    <div class="alert alert-danger fade in ">
                      <button class="close" aria-hidden="true" type="button" onclick="$(this).closest('.error-alert').fadeOut('slow');">&times;</button>
                      <strong></strong>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12 success-alert" style="display: none;">
                    <div class="alert alert-success fade in ">
                      <button class="close" aria-hidden="true" type="button" onclick="$(this).closest('.success-alert').fadeOut('slow');">&times;</button>
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
              <div class="row">
                  <div class="col-sm-12">
                    <span class="form-help-block"><a href="javascript:void(0)" onclick="$('#reset-pass').hide();$('#login-form-wrapper').fadeIn('slow');">Back to login</a></span>
                  </div>
                </div>
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
              <h3><b>New member? Join now</b></h3>
            </div>
            <div class="col-sm-10 col-md-8 col-sm-offset-1 col-md-offset-2 text-center">{$listing_content2}</div>
            <div class="col-sm-10 col-md-8 col-sm-offset-1 col-md-offset-2 text-center">
            <br>
              <form class="form-horizontal" id="register-form" data-attr-id="register-form" role="form" accept-charset="UTF-8" action="" method="post">
                <input type="hidden" value="createTemporaryMember" name="action" id="action" />
                <input type="hidden" value="" name="redirect" class="redirect" />
                <input type="hidden" name="formToken" id="formToken" value="{$token}" />

                <div class="row">
                  <div class="col-sm-12 form-group">
                    <label for="gname" class="visible-ie-only">
                      First Name<span>*</span>:
                    </label>
                    <input type="text" value="{if $new_user}{$new_user.gname}{/if}" class="form-control" id="gname" name="gname" required>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12 form-group">
                    <label for="surname" class="visible-ie-only">
                      Last Name<span>*</span>:
                    </label>
                    <input type="text" value="{if $new_user}{$new_user.surname}{/if}" class="form-control" id="surname" name="surname" required>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12 form-group">
                    <label for="dob" class="visible-ie-only">
                      Date of birth<span>*</span>:
                    </label>
                    <input type="text" value="{if $new_user}{$new_user.dob}{/if}" placeholder="DD/MM/YYYY" class="form-control" id="dob" name="dob" required>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12 form-group">
                    <label for="gender" class="visible-ie-only">
                      Gender<span>*</span>:
                    </label>
                    <select class="form-control" id="gender" name="gender" required>
                      <option value="">Select an option</option>
                      <option value="Male" {if $new_user.gender eq 'Male'}selected="selected"{/if}>Male</option>
                      <option value="Female" {if $new_user.gender eq 'Female'}selected="selected"{/if}>Female</option>
                    </select>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12 form-group">
                    <label for="address" class="visible-ie-only">
                      Address<span>*</span>:
                    </label>
                    <input type="text" value="{if $new_user}{$new_user.address}{/if}" class="form-control" id="address" name="address" required>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12 form-group">
                    <label for="suburb" class="visible-ie-only">
                      Suburb<span>*</span>:
                    </label>
                    <input type="text" value="{if $new_user}{$new_user.suburb}{/if}" class="form-control" id="suburb" name="suburb" required>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12 form-group">
                    <label for="state" class="visible-ie-only">
                      State<span>*</span>:
                    </label>
                    <select class="form-control" id="state" name="state" required>
                      <option value="">Please select an option</option>
                      {foreach $options_state as $opt}
                      <option value="{$opt.value}" {if $new_user.state eq $opt.value}selected="selected"{/if}>{$opt.value}</option>
                      {/foreach}
                    </select>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12 form-group">
                    <label for="postcode" class="visible-ie-only">
                      Postcode<span>*</span>:
                    </label>
                    <input type="text" maxlength="4" value="{if $new_user}{$new_user.postcode}{/if}" class="form-control" id="postcode" name="postcode" pattern="[0-9]" required>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12 form-group">
                    <label for="mobile" class="visible-ie-only">
                      Mobile<span>*</span>:
                    </label>
                    <input type="text" maxlength="10" value="{if $new_user}{$new_user.mobile}{/if}" class="form-control" id="mobile" name="mobile" pattern="[0-9]" required>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12 form-group">
                    <label for="email" class="visible-ie-only">
                      Email<span>*</span>:
                    </label>
                    <input type="email" value="{if $new_user}{$new_user.email}{/if}" class="form-control" id="reg-email" name="email" required>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12 form-group">
                    <label for="confirm_email" class="visible-ie-only">
                      Confirm email address<span>*</span>:
                    </label>
                    <input type="text" value="{if $new_user}{$new_user.email}{/if}" class="form-control" id="confirm_email" name="confirm_email" required>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12 form-group">
                    <label for="password" class="visible-ie-only">
                      Password<span>*</span>:
                    </label>
                    <input type="password" value="" class="form-control showpassword" id="password" name="password" autocomplete="off" required>
                    <a class="showhide" href="javascript:void(0);" onclick="if($(this).html() == 'Show'){ $(this).closest('div').find('input[type=password]').get(0).type='text';$(this).html('Hide'); }else{ $(this).closest('div').find('input[type=text]').get(0).type='password';$(this).html('Show'); }">Show</a>
                    <div class="passnote">Note: Your password must be at least 8 characters in length and contain at least 1 numeral and both upper and lower case letters.</div>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12 form-group">
                    <label for="hearabout" class="visible-ie-only">
                      How did you hear about us?<span>*</span>:
                    </label>
                    <select class="form-control" id="hearabout" name="heardabout" required>
                      <option value="">Select an option</option>
                      {foreach $options_hearabout as $opt}
                      <option value="{$opt.value}" {if $new_user.hearabout eq $opt.value}selected="selected"{/if}>{$opt.value}</option>
                      {/foreach}
                    </select>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>
                
                <div class="row seniorscard-wrapper" style="display:none">
                  <div class="col-sm-12 form-group">
                    <label for="seniorscard" class="visible-ie-only">
                      Seniors card:
                    </label>
                    <input type="text" value="{if $new_user}{$new_user.seniorscard}{/if}" class="form-control" id="seniorscard" name="seniorscard">
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12 form-group chkbx">
                    <input type="checkbox" {if $new_user.agree}checked="checked" {/if} value="I agree" class="form-control" id="agree" name="agree" required />
                    <label for="agree" class="chkbox visible-ie-only">
                      I agree to MedicAlert Foundation’s <a href="/terms-and-conditions" title="View our terms and conditions" target="_blank">terms and conditions</a>.<span>*</span>
                    </label>
                    <div class="error-msg help-block"></div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12 form-group">
                    <div class="error-alert" style="display: none;">
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
      <div class="row">
        <!-- CONTINUE AS GUEST SECTION -->
        <a href="/store/checkout" class="btn btn-info">Continue as Guest</a>
      </div>
      {/if}
    </div>
  </div>
</div>
<div id="existing-member-wrapper" style="display:none">
  <div class="container">
    <div class="row">
      <!-- EXISTING MEMBER SECTION  -->
      <div class="col-sm-10 col-md-6 col-sm-offset-1 col-md-offset-3 text-center">
        <div id="existing_member">
        <h3 id="existing-member-title"><b>Online account activation</b></h3>

          <form class="" id="existing-member-form" role="form" accept-charset="UTF-8" action="" method="post">
            <input type="hidden" value="Online account activation" name="form_name" />
            <input type="hidden" value="activateOnlineAccount" name="action" />
            <input type="hidden" name="formToken" id="formToken" value="{$token}" />
            <input type="hidden" name="timestamp" id="timestamp" value="{$timestamp}" />
            <div class="row">
          <div class="col-sm-6 form-group">
            <label class="visible-ie-only" for="name">Full name<span>*</span>:</label>
            <input class="form-control" value="{if $post.name}{$post.name}{else}{$user.gname} {$user.surname}{/if}" type="text" name="name" id="name" required="">
          <div class="error-msg help-block"></div>
          </div>
          <div class="col-sm-6 form-group">
            <label class="visible-ie-only" for="email">Email<span>*</span>:</label>
            <input class="form-control" value="{if $post.email}{$post.email}{else}{$user.email}{/if}" type="email" name="email" id="email" required="">
          <div class="error-msg help-block"></div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6 form-group">
            <label class="visible-ie-only" for="phone">Phone<span>*</span>:</label>
            <input class="form-control" value="{if $post.phone}{$post.phone}{else}{$user.maf.main.user_mobile}{/if}" type="text" name="phone" id="phone" required="" pattern="[0-9]*">
          <div class="error-msg help-block"></div>
          </div>

                <div class="col-sm-6 form-group">
            <label class="visible-ie-only" for="membership_no">Membership number<span>*</span>:</label>
            <input class="form-control" value="{if $post.membership_no}{$post.membership_no}{else}{$user.id}{/if}" type="text" name="membership_no" id="membership_no" required="" pattern="[0-9]*">
          <div class="error-msg help-block"></div>
            </div>
            </div>
            <div style="height:0;overflow:hidden;">
              <input value="" type="text" name="honeypot" id="honeypot" tabindex="-1" autocomplete="off">
            </div>
            <div class="row">
              <div class="error-alert" style="display: none;">
                <div class="alert alert-danger fade in">
                  <button class="close" aria-hidden="true" type="button" onclick="$(this).closest('.error-alert').fadeOut('slow');">&times;</button>
                  <strong></strong>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12 success-alert" style="display: none;">
                <div class="alert alert-success fade in">
                  <button class="close" aria-hidden="true" type="button" onclick="$(this).closest('.success-alert').fadeOut('slow');">&times;</button>
                  <strong></strong>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12 form-group">
                <div class="col-sm-offset-2 col-sm-8">
                  <button type="submit" class="btn btn-red">Request activation</button>
                </div>
              </div>
            </div>
          </form>
          <div class="row">
                  <div class="col-sm-12">
                    <a href="javascript:void(0)" onclick="$('#existing-member-wrapper').hide(); $('#logincont').fadeIn();">Back to login</a>
                  </div>
                </div>
          
        </div>
      </div>
    </div>
  </div>
</div>

<div id="greyblock1" class="login">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 text-center">
        <p class="bigtxt">For just {$CONFIG_VARS.membership_fee} a year, your MedicAlert membership gives you:</p>
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
        <p>Secure online access to your electronic health record</p>
      </div>
      <div class="col-sm-4 text-center benefits">
        <img src="/images/benefit6.png" alt="Support from our Membership Services team" class="img-responsive" />
        <p>Support from our Membership Services team</p>
      </div>
    </div>
  </div>
</div>

{/block} {* Place additional javascript here so that it runs after General JS includes *} {block name=tail} {literal}
<script src="/includes/js/jquery-ui.js"></script>
<script src="/includes/js/jquery.selectBoxIt.min.js"></script>
<script type="text/javascript">
 jQuery.validator.addMethod("validDate", function(value, element) {
        return this.optional(element) || /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/.test(value);
    }, "Please enter a valid date");

  $(document).ready(function() {
    $("select").selectBoxIt();

    $('#login-form').validate({
      submitHandler: function(form) {
        SubmitLoginRegisterForm($(form).attr('id'));
      }
    });

    $('#register-form').validate({
      submitHandler: function(form) {
        SubmitLoginRegisterForm($(form).attr('id'));
      }
    });


    $('#reset-pass-form').validate({
      submitHandler: function(form) {
        $('body').css('cursor', 'wait');
        var formId = $(form).attr('id')
        $('#' + formId).find('.error-alert').hide();
        var datastring = $('#' + formId).serialize();
        $.ajax({
          type: "POST",
          url: "/process/user",
          cache: false,
          data: datastring,
          dataType: "json",
          success: function(obj) {
            try{
              if(obj.error){
                $('#' + formId).find('.success-alert').hide();
                $('#' + formId).find('.error-alert').find('strong').html(obj.error);
                $('#' + formId).find('.error-alert').fadeIn('slow');
              }else{
                $('#' + formId).find('.error-alert').hide();
                $('#' + formId).find('.success-alert').find('strong').html(obj.success);
                $('#' + formId).find('.success-alert').fadeIn('slow');
                $('#' + formId).find('.form-group').hide();
                $('#reset-pass-title').hide();
              }
            }catch(err){
              console.log('TRY-CATCH error');
            }
            $('body').css('cursor', 'default');
          },
          error: function(jqXHR, textStatus, errorThrown) {
            $('#' + formId).find('.error-alert').find('strong').html('Undefined error.<br>Please refresh the page and try again or <a href="/contact-us">contact us</a>.');
            $('#' + formId).find('.error-alert').fadeIn('slow');
            $('body').css('cursor', 'default');
            console.log('AJAX error:' + errorThrown);
          }
        });
      }
    });

    $("#dob").datepicker({
      dateFormat: "dd/mm/yy",
      changeMonth: true,
      changeYear: true,
      yearRange: "-120:+0",
      maxDate: "-1D"
    });

    $('#dob').rules("add", {
      required: true,
      validDate: true
    });

    $('#confirm_email').rules("add", {
      required: true,
      equalTo: '#reg-email',
      messages: {
        equalTo: "The emails you have entered do not match. Please check them."
      }
    });

    $('#mobile').rules("add", {
      required: true,
      minlength: 10,
      maxlength: 10,
      digits: true,
      messages: {
        equalTo: "Please verify your mobile number"
      }
    });

    $('#password').rules("add", {
      minlength: 8,
      hasLowercase: true,
      hasUppercase: true,
      hasDigit: true
    });
    
    $('#existing-member-form').validate({
      submitHandler: function(form) {
        $('body').css('cursor', 'wait');
        var formId = $(form).attr('id')
        $('#' + formId).find('.error-alert').hide();
        var datastring = $('#' + formId).serialize();
        $.ajax({
          type: "POST",
          url: "/process/user",
          cache: false,
          data: datastring,
          dataType: "json",
          success: function(obj) {
            try{
              if(obj.error){
                $('#' + formId).find('.success-alert').hide();
                $('#' + formId).find('.error-alert').find('strong').html(obj.error);
                $('#' + formId).find('.error-alert').fadeIn('slow');
              }else{
                $('#' + formId).find('.error-alert').hide();
                $('#' + formId).find('.success-alert').find('strong').html(obj.success);
                $('#' + formId).find('.success-alert').fadeIn('slow');
                $('#' + formId).find('.form-group').hide();
                $('#existing-member-title').hide();
              }
            }catch(err){
              console.log('TRY-CATCH error');
            }
            $('body').css('cursor', 'default');
          },
          error: function(jqXHR, textStatus, errorThrown) {
            $('#' + formId).find('.error-alert').find('strong').html('Undefined error.<br>Please refresh the page and try again or <a href="/contact-us">contact us</a>.');
            $('#' + formId).find('.error-alert').fadeIn('slow');
            $('body').css('cursor', 'default');
            console.log('AJAX error:' + errorThrown);
          }
        });
      }
    });

    $('#phone').rules("add", {
      digits: true,
      minlength: 8
    });
    
    $("#dob").change(function(){
      var member_age = getAge(convert_to_mysql_date_format($(this).val()));
      console.log(member_age);
      if(member_age > 59){
        $('.seniorscard-wrapper').show();
      }else{
        $('.seniorscard-wrapper').hide();
      }
    });
  });

  function SubmitLoginRegisterForm(FORM) {
    $('body').css('cursor', 'wait');
    $('#' + FORM).find('.error-alert').hide();
    var datastring = $('#' + FORM).serialize();
    $.ajax({
      type: "POST",
      url: "/process/user",
      cache: false,
      data: datastring,
      dataType: "json",
      success: function(obj) {
        try{
          if(obj.success && obj.url){
            window.location.href = obj.url;
          }else if(obj.error){
            $('#' + FORM).find('.error-alert').find('strong').html(obj.error);
            $('#' + FORM).find('.error-alert').fadeIn('slow');
          }
        }catch(err){
          console.log('TRY-CATCH error');
        }
        $('body').css('cursor', 'default');
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $('#' + FORM).find('.error-alert').find('strong').html('Undefined error.<br>Please refresh the page and try again or <a href="/contact-us">contact us</a>.');
        $('#' + FORM).find('.error-alert').fadeIn('slow');
        $('body').css('cursor', 'default');
        console.log('AJAX error:' + errorThrown);
      }
    });
  }
  
  function getAge(dateString) {
    var today = new Date();
    var birthDate = new Date(dateString);
    var age = today.getFullYear() - birthDate.getFullYear();
    var m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    return age;
  }
  
  function convert_to_mysql_date_format(str){
    var dateArr = str.split("/");
    return dateArr[2]+'-'+dateArr[1]+'-'+dateArr[0]; 
  }

  /*   function FBlogin() {
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

   if(login_url){
   if(obj.new_window){
   var left = ($(window).width() / 2) - (500 / 2), top = ($(window).height() / 2) - (270 / 2), popup = window.open(login_url, "Login_with_Facebook", "width=500, height=270, top=" + top + ", left=" + left);
   }else{
   window.location.href = login_url;
   }
   }else if(msg && error){
   alert(msg);
   }
   }catch(err){
   console.log('TRY-CATCH error');
   }
   },
   error: function() {
   console.log('AJAX error');
   }
   });

   } */

  function redirectWin(url) {
    window.location.replace(url);
  }
</script>
{/literal} {/block}




