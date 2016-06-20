{block name=body}

<div id="maincont">
	<div class="container" id="contpage">
		<div class="row">
      <div class="col-sm-12 text-center" id="listtoptext">
	  		<h1>{spanify data=$listing_name}</h1>
      </div>
		</div>

<div class="row">
			<div class="col-sm-6" id="contacttext">
				<p>For questions relating to our products or this website please contact:</p>
				<p><strong>{$COMPANY.name}</strong></p>
	      <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
	        <span itemprop="streetAddress">{$COMPANY.address.street}</span><br>
	        <span itemprop="addressLocality">{$COMPANY.address.suburb}</span>,
	        <span itemprop="addressRegion">{$COMPANY.address.state}</span>
	        <span itemprop="postalCode">{$COMPANY.address.postcode}</span>
	      </div>
		  <p class="small">ABN 81 131 165 896</p>
		  <p>T <a class="tel" href="tel:{$COMPANY.phone}">{$COMPANY.phone}</a><br />
		  E <a href="mailto:{$COMPANY.email}">{$COMPANY.email}</a></p>

		  	<div id="map">
			  	<iframe src="https://www.google.com.au/maps/embed?pb=!1m18!1m12!1m3!1d3270.4412794784594!2d138.57730831523875!3d-34.9455469803724!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ab0cf0aa7ba1dc9%3A0x4041fbaa454297b!2s2%2F27+Anzac+Hwy%2C+Keswick+SA+5035%2C+Australia!5e0!3m2!1sen!2sin!4v1445886637056" width="400" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>
		  	</div>

			</div>
			<div class="col-sm-6" id="contform">
				<div class="row">
					<div class="col-sm-12"><h4>Get in touch</h4></div>
				</div>

				<div class="row">
					<div class="col-sm-12 text-right small">*Required fields</div>
				</div>

			 	<form class="form-horizontal" id="contact_form" accept-charset="UTF-8" method="post" action="/process/contact-us" novalidate="novalidate">
					<input type="hidden" value="get in touch" name="action" id="action" /> 
			    <input type="hidden" name="formToken" id="formToken" value="{$token}" />
			  	<input type="hidden" value="Contact" name="form_name" id="form_name" />
					<input type="hidden" name="timestamp" id="timestamp" value="{$timestamp}" />
			  		<div class="row">
						<div class="col-sm-6 form-group">
						  <label class="visible-ie-only" for="name">Name*</label>
							<input class="form-control" value="{$post.name}" type="text" name="name" id="name" required="">
						</div>
						<div class="col-sm-6 form-group">
						  <label class="visible-ie-only" for="Company">Company</label>
							<input class="form-control" value="{$post.organisation}" type="text" name="organisation" id="organisation" >
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12 form-group">
						  <label class="visible-ie-only" for="email">Email address*</label>
							<input class="form-control" value="{$post.email}" type="email" name="email" id="email" required="">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 form-group">
						  <label class="visible-ie-only" for="phone">Phone</label>
							<input class="form-control" value="{$post.phone}" type="text" name="phone" id="phone" >
						</div>

						<div class="col-sm-6 form-group">
              <label class="visible-ie-only" for="postcode">Postcode*</label>
							<input class="form-control" value="{$post.postcode}" type="text" name="postcode" id="postcode"  required="">
						</div>
					</div>

					<div class="row">
						<div class="col-sm-12 form-group">
						  <label class="visible-ie-only" for="enquiry">Your message*</label>
							<textarea class="form-control" name="enquiry" id="enquiry" required="">{$post.enquiry}</textarea>
						</div>
					</div>
					<div style="height:0;overflow:hidden;">
             <input value="" type="text" name="honeypot" id="honeypot" tabindex="-1">
          </div>
					<div class="row error-msg" id="form-error" {if $error neq ""}style="display:block"{/if}>{$error}</div>
					<div class="row">
						<div class="col-sm-12">
							<input type="button" value="Enquire" onclick="$('#contact_form').submit();" class="btn-blue btn">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12 small">
							<a href="/privacy-policy">Privacy Collection Statement</a>
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
