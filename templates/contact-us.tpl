{block name=body} 

<div id="maincont">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12">
				{include file='breadcrumbs.tpl'}		
			</div>
			<div class="col-sm-12">
			<h2>[contact-us.tpl]</h2>
				{$listing_content1}
			</div>
			<div class="col-sm-12" itemscope itemtype="http://schema.org/LocalBusiness">
	      <b><h3 itemprop="name">{$COMPANY.name}</h3></b><br>
	      <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
	        <span itemprop="streetAddress">{$COMPANY.address.street}</span><br>
	        <span itemprop="addressLocality">{$COMPANY.address.suburb}</span>,
	        <span itemprop="addressRegion">{$COMPANY.address.state}</span>
	        <span itemprop="postalCode">{$COMPANY.address.postcode}</span>
	      </div>
	      <div class="row ulinks">
					<div class="col-sm-3">
			      Free call:
			    </div>
			    <div class="col-sm-9">
			    	{if $COMPANY.freecall}<a href="tel:{$COMPANY.freecall}" itemprop="telephone">{$COMPANY.freecall}</a><br>{/if}
			    </div>
			    
					<div class="col-sm-3">
			      Phone:
			    </div>
			    <div class="col-sm-9">
			    	{if $COMPANY.phone}<a href="tel:{$COMPANY.phone}" itemprop="telephone">{$COMPANY.phone}</a><br>{/if}
			    </div>
			    
			    <div class="col-sm-3">
			      Fax:
			    </div>
			    <div class="col-sm-9">
			    	{if $COMPANY.fax}<a href="tel:{$COMPANY.fax}" itemprop="faxNumber">{$COMPANY.fax}</a><br>{/if}
			    </div>
			    
			    <div class="col-sm-3">
			      Email:<br>
		      </div>
		      <div class="col-sm-9">
			      <a href="mailto:{$COMPANY.email}" itemprop="email">{$COMPANY.email}</a>
		      </div>
		    </div>
	    </div>
			<div class="col-sm-8">
			 	<form class="form-horizontal" id="contact_form" accept-charset="UTF-8" method="post" action="/process/contact-us">
			  	<input type="hidden" value="question" name="action" id="action" /> 
			    <input type="hidden" name="formToken" id="formToken" value="{$token}" />
			  	<input type="hidden" value="Contact" name="form_name" id="form_name" />
					<input type="hidden" name="timestamp" id="timestamp" value="{$timestamp}" />
			  	<div style="height:0;overflow:hidden;">
             <input value="" type="text" name="honeypot" id="honeypot" tabindex="-1">
          </div>
			  	<div class="row form-group">
						<div class="col-sm-4 col-lg-2">
							<label for="name" class="control-label">Name*: </label>
						</div>
						<div class="col-sm-6 col-lg-5">
							<input class="form-control" value="{if $post.name}{$post.name}{else}{$user.gname}{/if}" type="text" name="name" id="name" required>
						</div>
					</div>
			  	<div class="row form-group">
						<div class="col-sm-4 col-lg-2">
							<label for="organisation" class="control-label">Organisation: </label>
						</div>
						<div class="col-sm-6 col-lg-5">
							<input class="form-control" value="{$post.organisation}" type="text" name="organisation" id="organisation">
						</div>
					</div>
					<div class="row form-group">
						<div class="col-sm-4 col-lg-2">
							<label for="email" class="control-label">Email*: </label>
						</div>
						<div class="col-sm-6 col-lg-5">
							<input class="form-control" value="{if $post.email}{$post.email}{else}{$user.email}{/if}" type="email" name="email" id="email" required>
						</div>
					</div>
					<div class="row form-group">
						<div class="col-sm-4 col-lg-2">
							<label for="phone" class="control-label">Phone: </label>
						</div>
						<div class="col-sm-6 col-lg-5">
							<input class="form-control" value="{$post.phone}" type="text" name="phone" id="phone">
						</div>
					</div>
					<div class="row form-group">
						<div class="col-sm-4 col-lg-2">
							<label for="postcode" class="control-label">Postcode*: </label>
						</div>
						<div class="col-sm-6 col-lg-5">
							<input class="form-control" value="{$post.postcode}" type="text" name="postcode" id="postcode" required>
						</div>
					</div>
			  	<div class="row form-group">
						<div class="col-sm-4 col-lg-2">
							<label for="enquiry" class="control-label">Enquiry*: </label>
						</div>
						<div class="col-sm-6 col-lg-5">
							<textarea class="form-control" name="enquiry" id="enquiry" required>{$post.enquiry}</textarea>
						</div>
					</div>
					<div class="aditional-form">
						<input value="" type="text" name="aditional" id="aditional" >
					</div>
			  	<div class="row error-msg" id="form-error">{$error}</div>
			  	<div class="row">
						<div class="col-sm-4 col-lg-2">
						</div>
						<div class="col-sm-4 col-lg-3">
							<input type="button" value="Submit" onclick="$('#contact_form').submit();" class="orange">
						</div>
					</div>
			  </form>
			</div>
			{if $listing_content5}
			<div class="col-sm-4">
				<img src="{$listing_content5}" title="{$listing_name} image" class="img-responsive" alt="{$listing_name} image" />
			</div>
			{/if}
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
