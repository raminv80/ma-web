{block name=body}

<div id="pagehead">
  <div class="bannerout">
	<img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
  </div>
  <div class="container" id="contpage">
    <div class="row">
      <div class="col-sm-12 text-center" id="listtoptext">
      	<h1>{$listing_title}</h1>
      </div>
      <div class="col-md-10 col-md-offset-1 text-center">
        {$listing_content1}
      </div>
    </div>
  </div>
</div>

<div id="contact" class="share">
  <div class="container">
 	 <div class="row">
		<div class="col-md-offset-1 col-md-10 text-center" id="sharestory">
    	 	<form id="contact_form" accept-charset="UTF-8" method="post" action="/process/contact-us" novalidate="novalidate">
        	    <input type="hidden" name="formToken" id="formToken" value="{$token}" />
        	  	<input type="hidden" value="Share your story" name="form_name" id="form_name" />
    			<input type="hidden" name="timestamp" id="timestamp" value="{$timestamp}" />
    	  		<div class="row">
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="name">Full name<span>*</span>:</label>
    					<input class="form-control" value="{$post.name}" type="text" name="name" id="name" required="">
						<div class="error-msg help-block"></div>
    				</div>
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="email">Email<span>*</span>:</label>
    					<input class="form-control" value="{$post.email}" type="email" name="email" id="email" required="">
						<div class="error-msg help-block"></div>
    				</div>
    			</div>
    			<div class="row">
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="phone">Phone<span>*</span>:</label>
    				  <input class="form-control" value="{$post.phone}" type="text" name="phone" id="phone" required="">
						<div class="error-msg help-block"></div>
    				</div>

                    <div class="col-sm-6 form-group">
                      <label class="visible-ie-only" for="postcode">Postcode<span>*</span>:</label>
                      <input class="form-control" value="{$post.postcode}" maxlength="4" type="text" name="postcode" id="postcode"  required="">
						<div class="error-msg help-block"></div>
                    </div>
    			</div>
    			<div class="row">
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="membership_no">Membership number:</label>
    				  <input class="form-control" value="{$post.membership_no}" type="text" name="membership_no" id="membership_no" >
						<div class="error-msg help-block"></div>
    				</div>

  				    <div class="col-sm-6 form-group">
    				</div>
    			</div>

    			<div class="row">
    				<div class="col-sm-12 form-group">
    				  <label class="visible-ie-only" for="enquiry">Your story<span>*</span>:</label>
    					<textarea class="form-control" name="enquiry" id="enquiry" required="">{$post.enquiry}</textarea>
						<div class="error-msg help-block"></div>
    				</div>
    			</div>
    			<div style="height:0;overflow:hidden;">
                   <input value="" type="text" name="honeypot" id="honeypot" tabindex="-1">
                </div>
                <div class="row">
	                <div class="col-sm-12">
						<div>
							<br />
							{$listing_content2}
							<br />
						</div>
	                </div>
                </div>
    			<div class="row error-msg" id="form-error" {if !$error}style="display:none"{/if}>{$error}</div>
    			<div class="row">
    				<div class="col-sm-12">
    					<input type="button" value="Share your story" onclick="$('#contact_form').submit();" class="btn-red btn" id="fbsub">
    				</div>
    			</div>
    	  </form>
          <br><br>
		</div>


		</div>
	</div>
</div>

<div id="orangebox" class="visible-xs">

</div>
{/block}

{block name=tail}
<script src="/includes/js/jquery-ui.js"></script>
<script src="/includes/js/jquery.selectBoxIt.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){

	 	$('#contact_form').validate();

	 	$('#postcode').rules("add", {
			digits: true,
			minlength: 4
		});

		$("select").selectBoxIt();


	 	$('#email').rules("add", {
			email: true
		});

});

</script>
{/block}
