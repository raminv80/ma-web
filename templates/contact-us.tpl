{block name=body} 
<div id="banners">
	<div id="banner-top" class="carousel slide" data-ride="carousel">
  	<!-- Wrapper for slides -->
		<div class="carousel-inner">
			<div class="item active">
				<img src="{if $listing_image}{$listing_image}{else}/images/bannerin.jpg{/if}" alt="banner">
				<div class="carousel-caption">
					<div class="container">
						<div class="row">
			  			<div class="col-sm-7">
				  			<h1><span>{$listing_name}</span></h1>
			  			</div>
						</div>
					</div>
				</div>	        
			</div>        			    
		</div>
	</div>
	<div id="what">
 		<h3>What are you looking for?</h3>
 		<ul>
 			<li><a href="/wood-heaters/how-to-choose-a-wood-heater" title="Information on how to choose a wood heater">Information on how to choose a wood heater</a></li>
			<li><a href="/manufacturers-suppliers-and-services/local-suppliers-and-service-providers" title="Find retailers, suppliers and services in your area">Find retailers, suppliers and services in your area</a></li>
 		</ul>
 	</div>
</div>

<div id="maincont">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12">
				{include file='breadcrumbs.tpl'}		
			</div>
			<div class="col-sm-12">
				{$listing_content1}
			</div>
			<div class="col-sm-8">
			 	<form class="form-horizontal" id="contact_form" accept-charset="UTF-8" method="post" action="/process/contact-us">
			  	<input type="hidden" value="question" name="action" id="action" /> 
			    <input type="hidden" name="formToken" id="formToken" value="{$token}" />
			  	
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
