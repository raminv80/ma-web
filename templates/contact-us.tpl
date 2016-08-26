{block name=body}

<div id="pagehead">
  <div class="bannerout">
	<img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
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
<div id="contact">
  <div class="container">
 	 <div class="row">
    	<div class="col-sm-4" id="contacttext">
	    	<div class="contline">
		    	<img src="/images/contact-location.png" alt="Location" />
	            <div class="cont-text" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
	              <span itemprop="streetAddress">{$COMPANY.address.street}</span><br>
	              <span itemprop="addressLocality">{$COMPANY.address.suburb}</span>
	              <span itemprop="addressRegion"> {$COMPANY.address.state}</span>
	              <span itemprop="postalCode"> {$COMPANY.address.postcode}</span>
	            </div>
	    	</div>
	    	<div class="contline">
		    	<img src="/images/contact-phone.png" alt="Location" />
		    	<div class="cont-text">
				<p>Free call <a class="tel" href="tel:{$COMPANY.toll_free}">{$COMPANY.toll_free}</a></p>
		    	</div>
	    	</div>
	    	<div class="contline">
		    	<img src="/images/contact-fax.png" alt="Location" />
		    	<div class="cont-text">
				<p>Free fax <a class="tel" href="tel:{$COMPANY.fax}">{$COMPANY.fax}</a></p>
				</div>
	    	</div>
	    	<div class="contline">
		    	<div class="contliner">
					<p class="small"><i>Please note: calls from mobile phones may attract extra charges.</i></p>
		    	</div>
	    	</div>
	    	<div class="contline">
		    	<img src="/images/contact-phone.png" alt="Location" />
		    	<div class="cont-text">
				<p><a class="tel" href="tel:{$COMPANY.phone}">{$COMPANY.phone}</a> (outside Australia)</p>
		    	</div>
	    	</div>
	    	<div class="contline">
		    	<img src="/images/contact-email.png" alt="Location" />
				<div class="cont-text">
				<p><a href="mailto:{$COMPANY.email}">{$COMPANY.email}</a></p>
				</div>
	    	</div>
	    	<div class="contline">
		    	<img src="/images/contact-time.png" alt="Location" />
				<div class="cont-text">
				<p>Office hours:<br>Monday - Friday, 9am - 5pm CST</p>
				</div>
	    	</div>
	    	<div class="contline">
            	<hr />
				<div class="contliner">
				<p>Join the community:</p>
				<div>
					<a href="#"><img src="/images/contact-fb.png" alt="Facebook" /></a>
					<a href="#"><img src="/images/contact-twitter.png" alt="Twitter" /></a>
					<a href="#"><img src="/images/contact-yt.png" alt="YouTube" /></a>
					<a href="#"><img src="/images/contact-insta.png" alt="Instagram" /></a>
				</div>
				</div>
	    	</div>
    	</div>

		<div class="col-sm-7 col-sm-offset-1 text-center" id="contform">
    	 	<form id="contact_form" accept-charset="UTF-8" method="post" action="/process/contact-us" novalidate="novalidate">
    			<input type="hidden" value="get in touch" name="action" id="action" />
        	    <input type="hidden" name="formToken" id="formToken" value="{$token}" />
        	  	<input type="hidden" value="Contact" name="form_name" id="form_name" />
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
                      <input class="form-control" value="{$post.postcode}" type="text" name="postcode" id="postcode"  required="">
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
                      <label class="visible-ie-only" for="nature_enquiry">How do you hear about us?<span>*</span></label>
                      <select class="selectlist-medium" id="nature_enquiry" name="nature_enquiry" required="">
                            <option>Please select</option>
                          {foreach $heardaboutus as $ha}
                            <option value="{$ha.heardabout_value}" {if $post.nature_enquiry eq $ha.heardabout_value} selected="selected"{/if}>{$ha.heardabout_name}</option>
                          {/foreach}
                      </select>
						<div class="error-msg help-block"></div>
    				</div>
    			</div>

    			<div class="row">
    				<div class="col-sm-12 form-group">
    				  <label class="visible-ie-only" for="enquiry">Message<span>*</span>:</label>
    					<textarea class="form-control" name="enquiry" id="enquiry" required="">{$post.enquiry}</textarea>
						<div class="error-msg help-block"></div>
    				</div>
    			</div>
    			<div style="height:0;overflow:hidden;">
                   <input value="" type="text" name="honeypot" id="honeypot" tabindex="-1">
                </div>
    			<div class="row error-msg" id="form-error" {if !$error}style="display:none"{/if}>{$error}</div>
    			<div class="row">
    				<div class="col-sm-12">
    					<input type="button" value="SEND" onclick="$('#contact_form').submit();" class="btn-red btn" id="fbsub">
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
