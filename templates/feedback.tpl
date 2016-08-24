{block name=body}
<div id="pagehead"> 
  <div class="bannerout"> 
    <img alt="Feedback banner" src="{$listing_image}">
  </div> 
  <div class="container" id="contpage">
    <div class="row">
      <div class="col-sm-12 text-center" id="listtoptext">
      	<h1>{$listing_content1}</h1>
      </div>
      <div class="col-sm-8 col-sm-offset-2 text-center">
        <p>{$listing_content2}</p>
      </div>
    </div>
  </div>
</div>
<div id="greyblock1">
  <div class="container">
 	 <div class="row">
		<div class="col-sm-6 col-sm-offset-3 text-center" id="contform">
    	 	<form id="contact_form" accept-charset="UTF-8" method="post" action="/process/contact-us" novalidate="novalidate">
    			<input type="hidden" value="get in touch" name="action" id="action" /> 
        	    <input type="hidden" name="formToken" id="formToken" value="{$token}" />
        	  	<input type="hidden" value="Feedback" name="form_name" id="form_name" />
    			<input type="hidden" name="timestamp" id="timestamp" value="{$timestamp}" />
    	  		<div class="row">
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="name">Full name*</label>
    					<input class="form-control" value="{$post.name}" type="text" name="name" id="name" required="">
    				</div>
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="email">Email*</label>
    					<input class="form-control" value="{$post.email}" type="email" name="email" id="email" required="">
    				</div>
    			</div>
    			<div class="row">
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="phone">Phone*</label>
    				  <input class="form-control" value="{$post.phone}" type="text" name="phone" id="phone" required="">
    				</div>
    
                    <div class="col-sm-6 form-group">
                      <label class="visible-ie-only" for="postcode">Postcode*</label>
                      <input class="form-control" value="{$post.postcode}" type="text" name="postcode" id="postcode"  required="">
                    </div>
    			</div>
    			<div class="row">
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="membership_no">Membership number</label>
    				  <input class="form-control" value="{$post.membership_no}" type="text" name="membership_no" id="membership_no" >
    				</div>
    
  				    <div class="col-sm-6 form-group">
                      <label class="visible-ie-only" for="nature_enquiry">How do you hear about us?*</label>
                      <select class="selectlist-medium" id="nature_enquiry" name="nature_enquiry" required="">
                          {foreach $heardaboutus as $ha}
                            <option value="{$ha.heardabout_value}" {if $post.nature_enquiry eq $ha.heardabout_value} selected="selected"{/if}>{$ha.heardabout_name}</option>
                          {/foreach}
                      </select>
    				</div>
    			</div>
    
    			<div class="row">
    				<div class="col-sm-12 form-group">
    				  <label class="visible-ie-only" for="enquiry">message*</label>
    					<textarea class="form-control" name="enquiry" id="enquiry" required="">{$post.enquiry}</textarea>
    				</div>
    			</div>
    			<div style="height:0;overflow:hidden;">
                   <input value="" type="text" name="honeypot" id="honeypot" tabindex="-1">
                </div>
    			<div class="row error-msg" id="form-error" {if $error neq ""}style="display:block"{/if}>{$error}</div>
    			<div class="row">
    				<div class="col-sm-12">
    					<input type="button" value="SEND" onclick="$('#contact_form').submit();" class="btn-red btn">
    				</div>
    			</div>
    	  </form>
		</div>
		</div>
	</div>
</div>

<div id="orangebox" class="visible-xs">

</div>
{/block}

{block name=tail}
<script type="text/javascript">
$(document).ready(function(){

	 	$('#contact_form').validate();

	 	$('#postcode').rules("add", {
			digits: true,
			minlength: 4
		});

	 	$('#email').rules("add", {
			email: true
		});

});

</script>
{/block}
