{block name=body}

<div id="pagehead"> 
  <div class="bannerout"> 
    <img alt="Accessibility banner" src="{$listing_image}">
  </div> 
  <div class="container" id="contpage">
	 <div class="row">
        <div class="col-sm-12 text-center" id="listtoptext">
  	  		<h1>{$listing_title}</h1>
        </div>
        <div class="col-sm-8 col-sm-offset-2 text-center">
          <p>{$listing_content1}</p>
        </div>
	 </div>
   </div>
</div>
<div id="greyblock1">
  <div class="container">
     <div class="row">
        <div class="col-sm-12 text-center">
          <h3>Website</h3>
        </div>
        <div class="col-sm-8 col-sm-offset-2 text-center">
          <p>{$listing_content2}</p>
        </div>
     </div>
     <div class="row">
        <div class="col-sm-4 text-center">
          <img alt="Call" src="">
          <p>Call</p>
          <p>Free call: <a class="tel" href="tel:{$COMPANY.toll_free}">{$COMPANY.toll_free}</a></p>
          <p>Phone (outside Australia): <a class="tel" href="tel:{$COMPANY.phone}">{$COMPANY.phone}</a></p>
        </div>
        <div class="col-sm-4 text-center">
          <img alt="Email" src="">
          <p>Email</p>
          <p><a href="mailto:{$COMPANY.email_contact}">{$COMPANY.email_contact}</a></p>
        </div>
        <div class="col-sm-4 text-center">
          <img alt="Office hours" src="">
          <p>Office hours</p>
          <p>Monday - Friday, 9am - 5pm (ACST)</p>
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
