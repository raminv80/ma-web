{block name="head"}
{printfile file='/includes/css/jquery-ui.css' type='style'}
{/block} {block name=body}
<div id="maincart">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-10 col-md-offset-1 text-center" id="checkout">
        <h1>{if $listing_title}{$listing_title}{else}{$listing_name}{/if}</h1>
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
      <div class="col-sm-offset-3 col-sm-6" id="login">
        <div id="loginin">
          <div class="row">
            <div class="col-sm-10 col-md-8 col-sm-offset-1 col-md-offset-2  text-center">
              <div id="login-form-wrapper">
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
                    <input type="text" value="" class="form-control" id="reset-username" name="username" required>
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

<script type="text/javascript">

  $(document).ready(function() {

    $('#login-form').validate({
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
        $('#' + FORM).find('.error-alert').find('strong').html('Undefined error');
        $('#' + FORM).find('.error-alert').fadeIn('slow');
        $('body').css('cursor', 'default');
        console.log('AJAX error:' + errorThrown);
      }
    });
  }

</script>
{/literal} {/block}




