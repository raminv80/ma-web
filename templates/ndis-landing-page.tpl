{block name=head}
<style type="text/css">

</style>
{/block}

{block name=body}
{if $listing_content2}
<div id="cost-grey" class="pinkh4">
  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center">
        {$listing_content2}
      </div>
    </div>
  </div>
</div>
{/if}
<div id="pagehead">
{*	<div class="bannerout">
      <img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
	</div>
  *}
	<div class="container">
		<div class="row">
			<div class="{if $listing_object_id eq 10}col-md-12{else}col-md-8 col-md-offset-2{/if} text-center">
				<h1>{if $listing_title}{$listing_title}{else}{$listing_name}{/if}</h1>
				{$listing_content1}
			</div>
		</div>
	</div>
</div>
<div id="faqs" class="emergency-grey ndis">
  <div class="container">
		<div class="row">
			<div class="{if $listing_object_id eq 10}col-md-12{else}col-md-8 col-md-offset-2{/if} text-center">
	  <div id="accordion">
			<h3>
				<div class="head-text">
					<div class="head-title">Who is eligible for the NDIS?</div>
				</div>
			</h3>
			<div>
        <p>The NDIS is progressively being rolled out throughout Australia and will provide complete national coverage by the end of 2018. Everything you need to know about the NDIS, including eligibility, how to apply and what supports are funded are available at <a href="http://www.ndis.gov.au" target="_blank">www.ndis.gov.au</a>.</p>
			</div>
			<h3>
				<div class="head-text">
					<div class="head-title">How is MedicAlert membership and ID funded under the NDIS?</div>
				</div>
			</h3>
			<div>
        <p>If you have communication issues due to your disability, such as autism, being non-verbal or other health conditions, your membership and ID may be supplied through your NDIS plan.</p>
        <p>Please note - MedicAlert Foundation is currently registered in the Capital Support (Assistive Technology) stream for personal care and safety, and communication and information equipment.</p>
			</div>
			<h3>
				<div class="head-text">
					<div class="head-title">How does your MedicAlert membership and ID support you in achieving your NDIS goals?</div>
				</div>
			</h3>
			<div>
  			<ul>
          <li>A personalised MedicAlert ID is a reasonable and necessary support that helps to protect you and communicate vital information on your behalf.</li>
          <li>Membership provides out of home support by keeping you safe and enabling positive engagement and inclusion through support of your personal goals, aspirations and activities within the community. Such as the confidence and independence to attend a therapy activity.</li>
          <li>Your ID will protect you during daily personal activities and provide peace of mind knowing that special needs can be identified and communicated quickly, assisting in greater social participation, independence, and health and well-being.</li>
          <li>Access to our 24/7 Emergency Response Service ensures the safe return of your loved ones if they abscond or wander. This includes a personal and secure electronic health record that contains information about any health, medical or personal conditions. Providing your loved ones or carer with greater peace of mind to allow you more independence, such as attending a friend's birthday party.</li>
  			</ul>
			</div>
			<h3>
				<div class="head-text">
					<div class="head-title">Step by step guide to claiming your MedicAlert membership and ID through the NDIS</div>
				</div>
			</h3>
			<div>
  			<ol>
    			<li>Contact MedicAlert for assistance – We’re here to help you understand how we can support you.</li>
          <li>Your Planning meeting – Ensure that you reference to your planner how MedicAlert can support you in reaching your goals.  It’s important to ensure you have appropriate funding in the Capital Support (Assistive Technology).</li>
          <li>View our <a href="/products/all-products">range of products</a> and select your ID.</li>
          <li>If you’re wanting to claim the service and product:<br>
          Ensure that MedicAlert supports your NDIS goals. Depending on what type of plan you have there are a few different processes for claiming your service and product.<br>
          <i>Plan Managed</i> – Get a quote from MedicAlert, check with your Plan Manager if you need a specialist (such as an Occupational Therapist) recommendation. Once this is complete, select your ID, provide us with your NDIS number and order online. We’ll then send you a tax invoice to have your funds reimbursed by your Plan Manager.<br>
          <i>Self-Managed</i> – Once you’ve selected your ID, provide us with your NDIS number and order online. We’ll then send you a tax invoice to have your funds reimbursed by the NDIS.<br>
          <i>NDIS Managed</i> – We’ll check you have enough funds in your Capital Support (Assistive Technology) stream and then process your order, on your behalf, via the NDIS portal.
          </li>
          <li>Your membership is activated immediately and your ID will be customised with your personal information. Your order will take approximately 5 days to be customised and will be sent to you via Australia Post.</li>
          <li>Once you receive your ID, you’re now fully protected by MedicAlert. We can assist with increasing your independence, accessing the community and communicating your health conditions to your community.</li>
  			</ol>
			</div>
	  </div>
	  <div class="clearfix"></div>
    <br>
    <p>If you have any queries regarding MedicAlert and their NDIS provider status, please email us at <a href="mailto:ndis@medicalert.org.au">ndis@medicalert.org.au</a></p>
    <p>Note: if you’re an existing MedicAlert member, please <a href="/contact-us">contact us</a> to update your profile and ensure you receive these benefits.</p>

			</div>
		</div>
	</div>
</div>

<div class="container" id="landing-newsart">
  <div class="row">
  {if $latest_news_articles}
  {foreach $latest_news_articles as $a}
    <div class="col-sm-6 col-md-4">
      <div class="newsres">
        <a href="{if $a.parent_listing_url}/{$a.parent_listing_url}{/if}/{$a.listing_url}">
          <img src="{if $a.listing_image}{$a.listing_image}{else}/images/medic-alert-logo.jpg{/if}?width=368&height=200&crop=1" alt="{$a.listing_name}" class="img-responsive fullwidth">
        </a>
        <div class="newsrestext">
          <div class="date">{$a.news_start_date|date_format:"%d %B %Y"}</div>
          <h3>
            <a href="{if $a.parent_listing_url}/{$a.parent_listing_url}{/if}/{$a.listing_url}">{$a.listing_name}</a>
          </h3>
          <div class="newstext">
            <p>
              {$a.listing_content1}
            </p>
          </div>
          <a href="{if $a.parent_listing_url}/{$a.parent_listing_url}{/if}/{$a.listing_url}" class="readart">Read article</a>
        </div>
      </div>
    </div>
  {/foreach}
  {/if}
  </div>
</div>
<div class="emergency-grey" id="contact">
<div class="container">
	<div class="row">
		<div class="col-md-12 text-center">
      {include file='templates/_form-ndis.tpl'}
    </div>
	</div>
</div>
</div>
{if $listing_content3 || $listing_content4}
<div>
<div id="landing-page-middle" class="container">
    <br>
    <div class="row">
      <div class="col-md-12 text-center">
        {$listing_content3}
      </div>
    </div>
</div>
</div>

<br>
<!--
<div class="grey-bg-area video-wrapper">
<div class="container">
    <div class="row">
      <div class="col-md-12 text-center">
        {$listing_content4}
      </div>
    </div>
  </div>
</div>
{/if}
-->


{if $popular_products && $listing_flag1}
<div id="popular">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 text-center">
      <br><br>
        <h2>Popular Products</h2>
      </div>
    </div>

    <div class="row">
      <div id="popslide" class="flexslider">
        <ul class="slides">
    {foreach $popular_products as $item}
          <li>
            <div class="prod">
                <a href="/{$item.product_url}"> <img src="{$item.general_details.image}?width=770&height=492&crop=1" alt="{$item.product_name} image" class="img-responsive" title="{$item.product_name} image" />
        </a>
        <div class="prod-labels">
              {if $item.general_details.limitedstock.flag eq 1}
          <div class="prod-label btn btn-white">Limited stock</div>
          {/if}
          {if $item.general_details.new.flag eq 1}
          <div class="prod-label btn btn-white">New</div>
          {/if}
          {if $item.general_details.sale.flag eq 1}
          <div class="prod-label btn btn-red">Sale</div>
          {/if}
        </div>
        <div class="prod-info">
                  <div class="prod-name">
          <a href="/{$item.product_url}">{$item.product_name}</a>
          </div>
          <div class="prod-price">${$item.general_details.price.min|number_format:0:'.':','}{if $item.general_details.price.min neq $item.general_details.price.max} - ${$item.general_details.price.max|number_format:0:'.':','}{/if}</div>
          {if $item.general_details.has_attributes.2}
          {$hasColour = 0}
          {foreach $item.general_details.has_attributes.2 as $colour}{if $colour.values.attr_value_image}{$hasColour = 1}{break}{/if}{/foreach}
          {if $hasColour eq 1}
          <div class="colours">Available colours
            <div class="colourbox">
            {foreach $item.general_details.has_attributes.2 as $colour}
            <img src="{$colour.values.attr_value_image}?height=22&width=22" alt="{$colour.values.attr_value_name}" title="{$colour.values.attr_value_name}" />
            {/foreach}
          </div>
          </div>
          {/if}{/if}
        <div class="prod-wishlist"><a href="javascript:void(0)" title="Your wish list" data-pid="{$item.product_object_id}" class="prodwishlist prodwishlist-{$item.product_object_id}{if $item.product_object_id|in_array:$wishlist} active{/if}"><img src="/images/prod-wishlist{if $item.product_object_id|in_array:$wishlist}-selected{/if}.png" alt="Wishlist"></a></div>
            </div>
          </li>
    {/foreach}
        </ul>
      </div>
    </div>
  </div>
</div>
{else}
  {if $products}
    <div id="products">
      <div class="container">
        <div class="row">
          <div class="col-sm-12 text-center">
            <h2>Select your medical ID</h2>
            Choose from our range of genuine MedicAlert IDs
          </div>
          </div>
          <div class="row" id="products-wrapper">
              {$nofollowprod = 1}
              {foreach $products as $item}
                {include file='ec_product_list_struct.tpl'}
              {/foreach}
          </div>
          <div class="row">
          <div class="col-sm-12 text-center small">
            *Selected products only.
          </div>
          <div class="col-sm-12 text-center" id="moreprods">
            <p>Can't find a product you like? See the <a href="/products?setdc={$discount_code}" rel="nofollow" style="color:#e02445;" title="View our range">full product range here</a> or call <a href="tel:{$COMPANY.toll_free}" title="Give us a call" class="phone">{$COMPANY.toll_free}</a>.</p>
          </div>
        </div>
      </div>
    </div>
  {/if}
{/if}

{/block}

{* Place additional javascript here so that it runs after General JS includes *}
{block name=tail}
{printfile file='/node_modules/jquery-ui-dist/jquery-ui.min.js' type='script'}
{printfile file='/node_modules/selectboxit/src/javascripts/jquery.selectBoxIt.min.js' type='script'}
{printfile file='/node_modules/flexslider/jquery.flexslider-min.js' type='script'}
{printfile file='/node_modules/isotope-layout/dist/isotope.pkgd.min.js' type='script'}
{printfile file='/node_modules/jquery-lazyload/jquery.lazyload.js' type='script'}
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

   $('#pmanager-phone').rules("add", {
     digits: true,
     minlength: 8
   });

   $('#pmanager-email').rules("add", {
     email: true
   });

   $("select").selectBoxIt();

    $('input[type="radio"]').change(function(){
      UpdateMemberRadio($(this).val());
    });

    if($('#plan-manager-group').hasClass("hide")){
      $('#plan-manager-group :input').prop('required', false);
    }

  });

  function UpdatePlanType(selectedValue){
      if(selectedValue == 'Plan Managed'){
        $('#plan-manager-group').slideDown().removeClass('hide');
        $('#plan-manager-group :input').prop('required', true);
      } else {

        $('#plan-manager-group').slideUp();

        $('#plan-manager-group :input').val('');
        $('#plan-manager-group :input').prop('required', false);
      }
  }

  function UpdateMemberRadio(selectedValue){
    if(selectedValue == 1){
      $('#maf-no').closest('.form-group').removeClass('hide');
      } else {
        $('#maf-no').closest('.form-group').addClass('hide');
        $('#maf-no').val('');
      }
  }

</script>
<script>
  $( function() {
    var icons = {
      header: "glyphicon glyphicon-plus",
      activeHeader: "glyphicon glyphicon-minus"
    };
    $( "#accordion" ).accordion({
      icons: icons,
	  heightStyle: "content",
      collapsible: true,
      activate: function( event, ui ) {
        if(!$.isEmptyObject(ui.newHeader.offset()) && !isScrolledIntoView(ui.newHeader)) {
            $('html:not(:animated), body:not(:animated)').animate({ scrollTop: ui.newHeader.offset().top }, 'slow');
        }
    }
    });
  } );
</script>

{/block}


