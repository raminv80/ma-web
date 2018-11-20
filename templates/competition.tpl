{block name=head}
<style type="text/css">
</style>
{/block} {block name=body}
<div id="pagehead">
  <div class="bannerout">
    <img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-10 col-md-offset-1  text-center">
        <h1>{if $listing_title}{$listing_title}{else}{$listing_name}{/if}</h1>
        {$listing_content1}
      </div>
    </div>
  </div>
</div>

{if $listing_content2}
<div id="cost-grey" class="pinkh4">
  <div class="container">
    <div class="row">
      <div class="col-md-10 col-md-offset-1 text-center">{$listing_content2}</div>
    </div>
  </div>
</div>
{/if}

<div id="contact">
  <div class="container">
    <div class="row">
      <div class=" col-md-10 col-md-offset-1 text-center" id="contform">
        <form id="competition_form" accept-charset="UTF-8" method="post" action="/process/competition" novalidate="novalidate">
          <input type="hidden" name="formToken" id="formToken" value="{$token}" />
          <input type="hidden" value="Competition" name="form_name" id="form_name" />
          <input type="hidden" name="timestamp" id="timestamp" value="{$timestamp}" />
          <input type="hidden" name="competition_reference_id" id="competition_reference_id" value="{$listing_object_id}" />
          <div class="row">
            <div class="col-sm-6 form-group">
              <label class="visible-ie-only" for="name">Full name<span>*</span>:
              </label>
              <input class="form-control" value="{if $post.name}{$post.name}{/if}" type="text" name="name" id="name" required="">
              <div class="error-msg help-block"></div>
            </div>
            <div class="col-sm-6 form-group">
              <label class="visible-ie-only" for="email">Email<span>*</span>:
              </label>
              <input class="form-control" value="{if $post.email}{$post.email}{/if}" type="email" name="email" id="email" required="">
              <div class="error-msg help-block"></div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 form-group">
              <label class="visible-ie-only" for="phone">Phone<span>*</span>:
              </label>
              <input class="form-control" value="{if $post.phone}{$post.phone}{/if}" type="text" name="phone" id="phone" required="" pattern="[0-9]*">
              <div class="error-msg help-block"></div>
            </div>

            <div class="col-sm-6 form-group">
              <label class="visible-ie-only" for="postcode">Postcode<span>*</span>:
              </label>
              <input class="form-control" value="{if $post.postcode}{$post.postcode}{/if}" maxlength="4" type="text" name="postcode" id="postcode" required="" pattern="[0-9]*">
              <div class="error-msg help-block"></div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-sm-6 form-group">
              <label class="visible-ie-only" for="membership_no">Membership number:<span>*</span></label>
              <input class="form-control" value="{if $post.membership_no}{$post.membership_no}{else}{$user.id}{/if}" type="text" name="membership_no" id="membership_no"  required="" pattern="[0-9]*">
            <div class="error-msg help-block"></div>
            </div>

          <div class="col-sm-6 form-group">
            <label class="visible-ie-only" for="heardabout">How did you hear about us?<span>*</span></label>
              <select class="selectlist-medium" id="heardabout" name="heardabout" required>
                <option value="">Please select</option>
              {foreach $options_hearabout as $ha}
                <option value="{$ha.id}" {if $post.heardabout eq $ha.value} selected="selected"{/if}>{$ha.value}</option>
              {/foreach}
              </select>
             <div class="error-msg help-block"></div>
            </div>
          
          {if $listing_content3}
          <div class="row question">
            <div class="col-sm-12 form-group text-left">
              <label class="visible-ie-only" for="entry">{$listing_content3} (in 25 words or less)<span>*</span>:
              </label>
              <textarea class="form-control" name="entry" id="entry" required="">{$post.entry}</textarea>
              <div class="error-msg help-block"></div>
            </div>
          </div>
          {/if}
        
          <div style="height: 0; overflow: hidden;">
            <input value="" type="text" name="honeypot" id="honeypot" tabindex="-1" autocomplete="off">
          </div>
          <div class="row error-msg" id="form-error" {if !$error}style="display: none"{/if}>{$error}</div>

          <div class="row">
            <div class="col-sm-12 form-group">
              <label class="visible-ie-only" for="user_agree"> <input type="checkbox" {if !$post || $post.user_agree}checked="checked" {/if}name="user_agree" id="user_agree" required/> I agree to the <a href="{$listing_image2}" target="_blank">Terms &amp; Conditions</a>.
              </label>
              <div class="error-msg help-block"></div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <input type="button" value="SUBMIT" onclick="$('#competition_form').submit();" class="btn-red btn" id="fbsub">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
{if $listing_content4}
  <div class="grey-bg-area video-wrapper">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center">{$listing_content4}</div>
      </div>
    </div>
  </div>
</div>

{/if} {/block} {* Place additional javascript here so that it runs after General JS includes *} {block name=tail}
{printfile file='/node_modules/jquery-ui-dist/jquery-ui.min.js' type='script'}
{printfile file='/node_modules/selectboxit/src/javascripts/jquery.selectBoxIt.min.js' type='script'}
<script type="text/javascript">
$(document).ready(function(){
  $('#competition_form').validate({
      rules: {
        user_agree: {
          required: true

        }
      },
      messages: {
        "user_agree":{
            required: "You must agree to the Terms & Conditions to enter the competition."
          }
        }
  });
		$("select").selectBoxIt();  
});

</script>
{/block}
