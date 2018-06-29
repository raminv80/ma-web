{block name=head}
<style type="text/css">
#heroimg{
background-image: url('{$listing_image}');
background-size: cover;
}
</style>
{/block}


{block name=body}
<div id="heroimg" class="safe-return">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 hidden-xs herotext">
        {$listing_content1}
      </div>
    </div>
  </div>
</div>
<div id="offer" class="visible-xs">
  <div class="container">
    <div class="row">
      <div class="col-xs-12 text-center">
        {$listing_content1}
      </div>
    </div>
  </div>
</div>

<div id="whiteblock1">
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2 text-center">
        {$listing_content2}
      </div>
    </div>
  </div>
</div>

<div class="emergency-grey">
  <div class="container">
     <div class="row">
        <div class="col-sm-8 col-sm-offset-2 text-center">
          {$listing_content3}
        </div>
     </div>
  </div>
</div>

<div id="contact" class="referp">
  <div class="container">
    <div class="row">
      <div class="col-md-offset-1 col-md-10 text-center white">
        <form id="contact_form" accept-charset="UTF-8" method="post" action="/process/employee-wellbeing" novalidate="novalidate">
          <input type="hidden" name="formToken" id="formToken" value="{$token}" />
          <input type="hidden" value="Employee Wellbeing" name="form_name" id="form_name" />
          <input type="hidden" name="timestamp" id="timestamp" value="{$timestamp}" />
          <div class="row">
            <div class="col-sm-12 text-center">
              <h3>Contact us</h3>
              <p>Get in touch with us today to find out more about protecting your employees through our Employee Wellbeing Program.</p>
              <br><br>
            </div>
            
          </div>
          <div class="row">
            <div class="col-sm-6 form-group">
              <label class="visible-ie-only" for="name">Full name<span>*</span>:
              </label>
              <input class="form-control" value="{$post.name}" type="text" name="name" id="name" required="">
              <div class="error-msg help-block"></div>
            </div>
            <div class="col-sm-6 form-group">
              <label class="visible-ie-only" for="email">Email<span>*</span>:
              </label>
              <input class="form-control" value="{$post.email}" type="email" name="email" id="email" required="">
              <div class="error-msg help-block"></div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 form-group">
              <label class="visible-ie-only" for="phone">Phone<span>*</span>:</label>
              <input class="form-control" value="{$post.phone}" type="text" name="phone" id="phone" pattern="[0-9]*" required="">
              <div class="error-msg help-block"></div>
            </div>
            <div class="col-sm-6 form-group">
              <label class="visible-ie-only" for="company">Company name<span>*</span>:
              </label>
              <input class="form-control" value="{$post.company}" type="text" name="company" id="company" required="">
              <div class="error-msg help-block"></div>
            </div>
          </div>
          <div style="height: 0; overflow: hidden;">
            <input value="" type="text" name="honeypot" id="honeypot" tabindex="-1">
          </div>
          <div class="row error-msg" id="form-error" {if !$error}style="display: none"{/if}>{$error}</div>
          <div class="row">
            <div class="col-sm-12">
            <br>
              <input type="submit" value="Contact us now" class="btn-red btn" id="fbsub"><br><br>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{if $listing_content4}
<div id="seconds-count">
  <div class="container">
    <div class="row">
      {$listing_content4}
    </div>
  </div>
</div>
{/if}


{/block} {block name=tail}
<script src="/includes/js/jquery-ui.js"></script>
<script src="/includes/js/jquery.selectBoxIt.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {

    $('#contact_form').validate();

    $('#phone').rules("add", {
      digits: true,
      minlength: 8
    });

    $('#email').rules("add", {
      email: true
    });

  });

</script>
{/block}
