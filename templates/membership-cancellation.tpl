{block name=body}
<style>
  .has-error .acknowledgement_wrapper{
    border: solid 1px #e9003b;
  }
</style>
<div id="pagehead">
  <div class="bannerout">
  <img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
  </div>
  <div class="container" id="contpage">
    <div class="row">
      <div class="col-sm-12 text-center" id="listtoptext">
        <h1>{$listing_title}</h1>
      </div>
      <div class="col-md-8 col-md-offset-2 text-center">
        {$listing_content1}
      </div>
    </div>
  </div>
</div>
<div id="contact" style="padding-top: 0px;">
  <div class="container">
   <div class="row">
    <div class=" col-sm-offset-2 col-sm-8 col-md-offset-0 col-md-12 text-center" id="contform">
        <form id="contact_form" accept-charset="UTF-8" method="post" action="/process/membership-cancellation" novalidate="novalidate">
              <input type="hidden" name="formToken" id="formToken" value="{$token}" />
              <input type="hidden" value="Membership cancellation request" name="form_name" id="form_name" />
          <input type="hidden" name="timestamp" id="timestamp" value="{$timestamp}" />
          <div class="row">
            <div class="col-sm-6 form-group">
              <label class="visible-ie-only" for="name">Member full name<span>*</span>:</label>
              <input class="form-control" value="{if $post.name}{$post.name}{else}{$user.gname} {$user.surname}{/if}" type="text" name="name" id="name" required="">
            <div class="error-msg help-block"></div>
            </div>
            <div class="col-sm-6 form-group">
              <label class="visible-ie-only" for="membership_no">Membership number<span>*</span>:</label>
              <input class="form-control" value="{if $post.membership_no}{$post.membership_no}{else}{$user.id}{/if}" type="text" name="membership_no" id="membership_no"  pattern="[0-9]*" required="">
              <div class="error-msg help-block"></div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 form-group">
              <label class="visible-ie-only" for="email">Member email:</label>
              <input class="form-control" value="{if $post.email}{$post.email}{else}{$user.email}{/if}" type="email" name="email" id="email" >
              <div class="error-msg help-block"></div>
            </div>
            <div class="col-sm-6 form-group">
              <label class="visible-ie-only" for="phone">Member phone:</label>
              <input class="form-control" value="{if $post.phone}{$post.phone}{else}{$user.maf.main.user_mobile}{/if}" type="text" name="phone" id="phone" pattern="[0-9]*">
              <div class="error-msg help-block"></div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 form-group">
              <label class="visible-ie-only" for="cancellation_reason">Reason for cancellation<span>*</span>:</label>
              <select class="form-control selectlist-medium" id="cancellation_reason" name="cancellation_reason" required>
                <option value="">Please select</option>
                <option value="Membership cost too high">Membership cost too high</option>
                <option value="Doesn't want to be part of membership / pay annual fee">Doesn't want to be part of membership / pay annual fee</option>
                <option value="No longer has medical need for membership / ID">No longer has medical need for membership / ID</option>
                <option value="ID is enough, no need for membership">ID is enough, no need for membership</option>
                <option value="Lack of First Responder attention to ID">Lack of First Responder attention to ID</option>
                <option value="No longer wearing ID (but medical conditions still present)">No longer wearing ID (but medical conditions still present)</option>
                <option>Other</option>
              </select>
              <div class="error-msg help-block"></div>
            </div>
            <div class="col-sm-6 form-group other_reason_wrapper" style="display: none;">
              <label class="visible-ie-only" for="other_reason">Please specify<span>*</span>:</label>
              <input class="form-control" type="text" name="other_reason" id="other_reason">
              <div class="error-msg help-block"></div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 form-group">
              <label class="visible-ie-only" for="requested_for_someone"><b>Requesting for someone?</b></label>
              <input value="yes" type="checkbox" name="requested_for_someone" id="requested_for_someone">
              <div class="error-msg help-block"></div>
            </div>
          </div>
          <div class="row requesting_for_someone_fields" style="display: none;">
            <div class="col-sm-6 form-group">
              <label class="visible-ie-only" for="requested_by">Your Name<span>*</span>:</label>
              <input class="form-control" value="" type="text" name="requested_by" id="requested_by" >
            <div class="error-msg help-block"></div>
            </div>
            <div class="col-sm-6 form-group">
              <label class="visible-ie-only" for="requested_by_phone">Your Phone<span>*</span>:</label>
              <input class="form-control" value="" type="text" name="requested_by_phone" id="requested_by_phone"  pattern="[0-9]*" >
              <div class="error-msg help-block"></div>
            </div>
          </div>
          <div class="row requesting_for_someone_fields" style="display: none;">
            <div class="col-sm-6 form-group">
              <label class="visible-ie-only" for="relation_to_member">Relationship to member<span>*</span>:</label>
              <select class="form-control selectlist-medium" id="relation_to_member" name="relation_to_member">
                <option value="">Please select</option>
                <option>Parent</option>
                <option>Guardian</option>
                <option>Other</option>
              </select>
              <div class="error-msg help-block"></div>
            </div>
            <div class="col-sm-6 form-group other_relation_wrapper" style="display: none;">
              <label class="visible-ie-only" for="other_relation">Please specify<span>*</span>:</label>
              <input class="form-control" type="text" name="other_relation" id="other_relation">
              <div class="error-msg help-block"></div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 form-group text-left">
              <p>
                <ol style="padding-left: 18px;">
                  <li>My personal information will be removed from the database used by the 24/7 MedicAlert Emergency Response Service and will not be available for use in conjunction with that service;</li>
                  <li>I will no longer be entitled to any other member benefits;</li>
                  <li>Although I may continue to wear a MedicAlert ID, carry a MedicAlert wallet card or use a personalised MedicAlert product, no 24/7 MedicAlert Emergency Response Service or other service will be provided in respect of such products.</li>
                </ol>
              </p>
              <div class="acknowledgement_wrapper">
                <input value="yes" type="checkbox" name="acknowledgement" id="acknowledgement" required>
                <label class="visible-ie-only" for="acknowledgement"><b>I acknowledge and agree that I want to cancel the MedicAlert membership?</b></label>
              </div>
              <div class="error-msg help-block"></div>
            </div>
          </div>
          <div style="height:0;overflow:hidden;">
                   <input value="" type="text" name="honeypot" id="honeypot" tabindex="-1" autocomplete="off">
                </div>
          <div class="row error-msg" id="form-error" {if !$error}style="display:none"{/if}>{$error}</div>
          <div class="row">
            <div class="col-sm-12">
              <input type="button" value="SEND" onclick="$('#contact_form').submit();" class="btn-red btn" id="fbsub">
            </div>
          </div>
        </form>
          <br>
          <div>{$listing_content2}</div>
          <br><br>
    </div>

    </div>
  </div>
</div>

<div id="orangebox" class="visible-xs">

</div>
{/block}

{block name=tail}
{printfile file='/includes/js/jquery-ui.js' type='script'}
{printfile file='/includes/js/jquery.selectBoxIt.min.js' type='script'}
<script type="text/javascript">
$(document).ready(function(){

    $('#contact_form').validate();

    $('#phone').rules("add", {
        digits: true,
          minlength: 8
        });

    $("select").selectBoxIt();


    $('#email').rules("add", {
      email: true
    });
    
    $('#cancellation_reason').change(function(){
      if($(this).val() == 'Other'){ 
        $('.other_reason_wrapper').show();
        $('#other_reason').attr('required','');
      } else{
        $('.other_reason_wrapper').hide();
        $('.other_reason_wrapper').removeClass('has-error');
        $('.other_reason_wrapper').find('.error-msg').text('');
        $('#other_reason').removeAttr('required');
      }
    });
    
    $('#requested_for_someone').change(function(){
      if($(this).is(':checked')){
        $('.requesting_for_someone_fields').show();
        $('#requested_by, #requested_by_phone, #relation_to_member').attr('required', '');
        $('#requested_by_phone').rules("add", {
          digits: true,
          minlength: 8
        });
      } else{
        $('.requesting_for_someone_fields').hide();
        $('#requested_by, #requested_by_phone, #relation_to_member').removeAttr('required');
        $('.requesting_for_someone_fields .form-group').removeClass('has-error');
        $('.requesting_for_someone_fields .error-msg').text('');
      }
    });
    
    $('#relation_to_member').change(function(){
      if($(this).val() == 'Other'){ 
        $('.other_relation_wrapper').show();
        $('#other_relation').attr('required','');
      } else{
        $('.other_relation_wrapper').hide();
        $('.other_relation_wrapper').removeClass('has-error');
        $('.other_relation_wrapper').find('.error-msg').text('');
        $('#other_relation').removeAttr('required');
      }
    });

});

</script>
{/block}
