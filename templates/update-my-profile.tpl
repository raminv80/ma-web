{block name="head"}
<link href="/includes/css/jquery-ui.css" rel="stylesheet" media="screen">
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
      <div id="update-profile-wrapper">
          <div class="row">
            <div class="col-sm-10 col-md-8 col-sm-offset-1 col-md-offset-2 text-center">
              <form class="form-horizontal" id="update-profile-form"  role="form" accept-charset="UTF-8" action="" method="post">
                <input type="hidden" value="" name="action" id="action" />
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
                      <option value="">Select an option</option>
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
                    <label for="phone" class="visible-ie-only">
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
                      <button type="submit" class="btn btn-red">Update details</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
    </div>
  </div>
</div>


{/block} {* Place additional javascript here so that it runs after General JS includes *} {block name=tail} {literal}
<script src="/includes/js/jquery-ui.js"></script>
<script src="/includes/js/jquery.selectBoxIt.min.js"></script>
<script type="text/javascript">
 
  $(document).ready(function() {
    $("select").selectBoxIt();
    
    $('#update-profile-form').validate({
      submitHandler: function(form) {
        SubmitProfileForm($(form).attr('id'));
      }
    });

    
    $("#dob").datepicker({
      dateFormat: "dd/mm/yy",
      changeMonth: true,
      changeYear: true,
      yearRange: "-100:+0", 
      maxDate: "-1D"
    });
    
    $('#dob').rules("add", {
      required: true
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
  });
  
  function SubmitProfileForm(FORM) {
    $('body').css('cursor', 'wait');
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
        $('#' + FORM).find('.error-alert').find('strong').html('Undefined error');
        $('#' + FORM).find('.error-alert').fadeIn('slow');
        $('body').css('cursor', 'default');
        console.log('AJAX error:' + errorThrown);
      }
    });
  }


  function redirectWin(url) {
    window.location.replace(url);
  }
</script>
{/literal} {/block}




