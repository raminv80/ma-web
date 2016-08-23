{block name=body}

<div id="maincont">
	<div class="container" id="contpage">
	 <div class="row">
      <div class="col-sm-12 text-center" id="listtoptext">
	  		<h1>{$listing_content1}</h1>
      </div>
      <div class="col-sm-8 col-sm-offset-2 text-center">
        <p>{$listing_content2}</p>
      </div>
	 </div>
     <div class="row"></div>
 	 <div class="row">
    	<div class="col-sm-6" id="contacttext">
            <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
              <span itemprop="streetAddress">{$COMPANY.address.street}</span><br>
              <span itemprop="addressLocality">{$COMPANY.address.suburb}</span>
              <span itemprop="addressRegion"> {$COMPANY.address.state}</span>
              <span itemprop="postalCode"> {$COMPANY.address.postcode}</span>
            </div>
            <p>T <a class="tel" href="tel:{$COMPANY.toll_free}">{$COMPANY.toll_free}</a></p>
            <p>F <a class="tel" href="tel:{$COMPANY.fax}">{$COMPANY.fax}</a></p>
            <p class="small"><i>Please note: calls from mobile phones may attreact extra charges.</i></p>
            <p>T <a class="tel" href="tel:{$COMPANY.phone}">{$COMPANY.phone}</a></p>
            <p>E <a href="mailto:{$COMPANY.email_contact}">{$COMPANY.email_contact}</a></p>
            <p>Office hours:<br>Monday - Friday, 9am - 5pm CST</p>
            <p><hr></p>
            <p>Join the community:</p>
    	</div>
      
		<div class="col-sm-6 text-center" id="contform">
    	 	<form id="contact_form" accept-charset="UTF-8" method="post" action="/process/contact-us" novalidate="novalidate">
    			<input type="hidden" value="get in touch" name="action" id="action" /> 
        	    <input type="hidden" name="formToken" id="formToken" value="{$token}" />
        	  	<input type="hidden" value="Contact" name="form_name" id="form_name" />
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
                      <label class="visible-ie-only" for="nature_enquiry">Nature of enquiry*</label>
                      <select class="selectlist-medium" id="nature_enquiry" name="nature_enquiry" required="">
                    		<option value="Update my information">Update my information</option>
                    		<option value="Product/Price enquiry">Product/Price enquiry</option>
                    		<option value="Order/Delivery status">Order/Delivery status</option>
                    		<option value="Deceased member notification">Deceased member notification</option>
                    		<option value="General enquiry" selected="selected">General enquiry</option>
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
    					<input type="button" value="SEND" onclick="$('#contact_form').submit();" class="btn-blue btn">
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
