{block name=head}
<style type="text/css">
</style>
{/block} {block name=body}
<div id="headgrey">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h2>Welcome back, {$user.gname}</h2><div><a href="/process/user?logout=true" title="Click to log out">Log out</a></div>
			</div>
		</div>
	</div>
</div>
<div id="pagehead">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 text-left">
			{if $error}
			<div class="alert alert-danger fade in">
				{$error}
			</div>
			{/if}
	  		{if $notice}
			<div class="alert alert-success fade in">
				<button class="close" aria-hidden="true" data-dismiss="alert" type="button">&times;</button>
				{$notice}
			</div>
			{/if}
      </div>
      <div class="col-sm-12 text-center" id="listtoptext">
      	<h1>{$listing_title}</h1>
        {$listing_content1}
      </div>
    </div>
  </div>
</div>

<div id="account">
	<div class="container">
		<div class="row">
			<div class="col-sm-6 col-md-7 col-lg-8">
				<div id="daysrenew" class=" text-center">
                    {$renewalDate = $user.maf.main.user_RenewalDate}
                    {if $renewalDate gt $smarty.now|date_format:"%Y-%m-%d"}
			         <img src="/images/active-icon.png" alt="active" /> <span class="warning">Your are protected</span>
                    <div><br>You have <strong>{date_diff date_end=$smarty.now|date_format:"%Y-%m-%d" date_start=$renewalDate date_format='%a'} days</strong> until your renewal is due.</div>
                    {else}
                    <img src="/images/dashboard-warning.png" alt="Warning" /> <span class="warning">Your membership has expired</span>
					<div class="days">{date_diff date_end=$smarty.now|date_format:"%Y-%m-%d" date_start=$renewalDate date_format='%a'}</div>
					<div>days overdue</div>
					<a href="/quick-renew" title="Click to renew your membership" class="btn btn-red">Renew now</a>
                    {/if}
				</div>
			</div>
			<div class="col-sm-6 col-md-5 col-lg-4">
				<div id="accinfo">
					<div class="accrow">
						<div class="col-xs-8 col-sm-8">
							<img src="/images/dashboard-name.png" alt="Account" /> Member name
						</div>
						<div class="col-xs-4 col-sm-4">
							{$user.maf.main.user_firstname} {$user.maf.main.user_lastname}
						</div>
					</div>
					<div class="accrow">
						<div class="col-xs-8 col-sm-8">
							<img src="/images/dashboard-memberno.png" alt="Account" /> Member number
						</div>
						<div class="col-xs-4 col-sm-4">
							{$user.maf.main.user_id}
						</div>
					</div>
					<div class="accrow">
						<div class="col-xs-8 col-sm-8">
							<img src="/images/dashboard-membersince.png" alt="Account" /> Member since
						</div>
						<div class="col-xs-4 col-sm-4">
                            {$user.maf.main.user_memberJoinDate|date_format:"%d/%m/%Y"}
						</div>
					</div>
					<div class="accrow">
						<div class="col-xs-8 col-sm-8">
							<img src="/images/dashboard-renewal.png" alt="Account" /> Your next renewal is due
						</div>
						<div class="col-xs-4 col-sm-4">
                          {$user.maf.main.user_RenewalDate|date_format:"%d/%m/%Y"}
						</div>
					</div>
					<div class="accrow">
						<div class="col-sm-12">
							<img src="/images/dashboard-password.png" alt="Account" /> <a href="javascript:void(0)" onclick="$('#password-form').fadeIn('slow');">Update my password</a>
						</div>
					</div>
					<div class="accrow" id="password-form" style="display: none;">
						<form id="pass_form" accept-charset="UTF-8" method="post" action="/process/user" novalidate="novalidate">
						<input type="hidden" name="formToken" id="formToken" value="{$token}" />
						<input type="hidden" value="updatePassword" name="action" />
						<input type="hidden" name="timestamp" id="timestamp" value="{$timestamp}" />
						<div class="col-sm-12 form-group">
						    <label for="password1" class="control-label bold">Current Password:<span>*</span></label>
							<input class="form-control showpassword" type="password" id="password1" name="current" autocomplete="off" required />
							<a class="showhide" href="javascript:void(0);" onclick="if($(this).html() == 'Show'){ $(this).closest('div').find('input[type=password]').get(0).type='text';$(this).html('Hide'); }else{ $(this).closest('div').find('input[type=text]').get(0).type='password';$(this).html('Show'); }">Show</a>
							<span class="help-block"></span>
						</div>
						<div class="col-sm-12 form-group">
						    <label for="newpassword" class="control-label bold">New Password:<span>*</span></label>
							<input class="form-control showpassword" type="password" id="newpassword" name="new" autocomplete="off" required />
							<a class="showhide" href="javascript:void(0);" onclick="if($(this).html() == 'Show'){ $(this).closest('div').find('input[type=password]').get(0).type='text';$(this).html('Hide'); }else{ $(this).closest('div').find('input[type=text]').get(0).type='password';$(this).html('Show'); }">Show</a>
							<span class="help-block"></span>
						</div>
						<div class="col-sm-12">
							<div class="passnote">Note: Your new password must be at least 8 characters in length and contain at least 1 numeral and both upper and lower case letters.</div>
						</div>
                        <div class="col-sm-12 form-group">
                          <div class="error-alert" style="display: none;">
                            <div class="alert alert-danger fade in ">
                              <button class="close" aria-hidden="true" type="button" onclick="$(this).closest('.error-alert').fadeOut('slow');">&times;</button>
                              <strong></strong>
                            </div>
                          </div>
                          <div class="success-alert" style="display: none;">
                            <div class="alert alert-success fade in ">
                              <button class="close" aria-hidden="true" type="button" onclick="$(this).closest('.success-alert').fadeOut('slow');">&times;</button>
                              <strong></strong>
                            </div>
                          </div>
                        </div>
						<div class="col-sm-12">
							 <button type="submit" class="btn btn-red">Update password</button>
						</div>
						</form>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>

			<div class="col-sm-12 text-center" id="renewal">
				<h3>{$user.maf.main.user_firstname}, is your membership up to date?</h3>
				<p>It’s important to keep your MedicAlert membership up to date so we can continue to help protect you in an emergency. Don’t wait until it’s too late.  </p>

				<div class="row">
					<div class="col-sm-{if $user.maf.main.auto_billing_active eq 't'}12{else}6{/if} text-center" id="viewup">
						<div>
							<img src="/images/dashboard-viewupdate.png" alt="View / update your profile" class="img-responsive" />
						</div>
						<a href="/update-my-profile" title="Click to view/update your profile " class="btn btn-red">View / update your profile</a>
					</div>
          {if $user.maf.main.auto_billing_active eq 'f'}
					<div class="col-sm-6 text-center" id="autor">
						<div>
							<img src="/images/dashboard-autorenew.png" alt="Register for auto-renewals" class="img-responsive" />
						</div>
						<a href="/register-for-auto-renewal" title="Click to register for auto-renewal" class="btn btn-red">Register for auto-renewals</a>

						<p>Take the hassle out of remembering to pay your annual MedicAlert membership with auto-renewals. By setting up Direct Debit payments from your nominated bank account, you can rest assured knowing you’ll always be protected.</p>
					</div>
          {/if}
				</div>
			</div>
{if $displayReminder1 ||  ($smarty.now|date_format:'%Y%m%d' - $user.maf.main.user_RenewalDate|date_format:'%Y%m%d') gt 800}
			<div class="col-sm-12 text-center {$user.maf.main.user_RenewalDate|date_format:'%Y%m%d' - $smarty.now|date_format:'%Y%m%d'}" id="reminder1">
				<h3>It looks like you haven’t purchased a new product in a while. </h3>
				<p>Now is a good time to check that your engraving is legible and the attachments (e.g. jump rings and clasps) are in good condition. If the engraving is difficult to read, or you’d simply like a new piece of jewelry, order a new one below. Replacement attachments can be purchased by calling Membership Services on <a href="tel:{$COMPANY.toll_free}">{$COMPANY.toll_free}</a>.</p>

				<div class="row">
					<div class="col-sm-6 text-center" id="viewup1">
						<div>
							<img src="/images/dashboard-exclamation.png" alt="warning" /> <h3 class="inline">Reminder to check your medical ID</h3>
						</div>
						<p>If it is not clear to read it is time to purchase a new one.</p>
						<div>
							<img src="/images/dashboard-bracelet.png" alt="Check your medical ID" class="img-responsive bracelet" />
						</div>
						<p>If it is not clear to read it is time to purchase a new one.</p>
					</div>
					<div class="col-sm-6 text-center" id="autor1">
						<div>
							<img src="/images/dashboard-prods.png" alt="Latest products" class="img-responsive" />
						</div>
						<p class="bold">Our latest products</p>
					</div>
					<div class="col-sm-12 text-center">
						<a href="/products"	title="Click to view our product range" class="btn btn-red">Find a new product</a>
					</div>
				</div>
			</div>
{/if}


		</div>
	</div>
</div>
{if $popular_products}
<div id="popprod">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<h3>Popular products</h3>
			</div>
		</div>
		<div class="row">
            {foreach $popular_products as $k => $item}
			<div class="col-xs-6 col-sm-3 col-md-2 prod{if $k eq 0} col-md-offset-2{/if}">
				<a href="/{$item.product_url}" title="{$item.product_name} image" >
					<img src="{$item.general_details.image}?width=770&height=492&crop=1" alt="{$item.product_name} image" class="img-responsive"/>
				</a>
			</div>
              {if $k gt 1}{break}{/if} 
			{/foreach}
			<div class="col-xs-6 col-sm-3 col-md-2 text-center prod">
    			<a href="/products" title="Click to view our product range">
    				<img src="/images/dashboard-findnew.png" class="img-responsive" alt="Find a new product" title="Find a new product" />
    			</a>
			</div>
		</div>
	</div>
</div>
{/if}
{if $banner1 || $banner2 || $banner3}
<div id="specialoffer">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<h3>Special offers just for you</h3>
			</div>
		</div>
		<div class="row text-center">
			{if $banner1}
            <div class="col-sm-4 specials">
              <a href="{$banner1.0.banner_link}" {if $banner1.0.banner_link|strstr:"http"} target="_blank"{/if} title="{$banner1.0.banner_name}">
                <img src="{$banner1.0.banner_image2}" alt="{$banner1.0.banner_name}" class="img-responsive" />
              </a>
			</div>
            {/if}
            {if $banner2}
            <div class="col-sm-4 specials">
              <a href="{$banner2.0.banner_link}" {if $banner2.0.banner_link|strstr:"http"} target="_blank"{/if} title="{$banner2.0.banner_name}">
                <img src="{$banner2.0.banner_image2}" alt="{$banner2.0.banner_name}" class="img-responsive" />
              </a>
            </div>
            {/if}
            {if $banner3}
            <div class="col-sm-4 specials">
              <a href="{$banner3.0.banner_link}" {if $banner3.0.banner_link|strstr:"http"} target="_blank"{/if} title="{$banner3.0.banner_name}">
                <img src="{$banner3.0.banner_image2}" alt="{$banner3.0.banner_name}" class="img-responsive" />
              </a>
            </div>
            {/if}
			
		</div>
	</div>
</div>
{/if}
<div id="greyblock1" class="dashboard">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<h3>For just {$CONFIG_VARS.membership_fee} a year, your MedicAlert membership gives you:</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-md-10 col-md-offset-1">
			<div class="row">
				<div class="col-sm-4 text-center benefits">
					<img src="/images/benefit1.png" alt="Protection for 12 months" class="img-responsive" />
					<p>Protection for 12 months</p>
				</div>
				<div class="col-sm-4 text-center benefits">
					<img src="/images/benefit2.png" alt="24/7 emergency service access to your medical information" class="img-responsive" />
					<p>24/7 emergency service access to your medical information</p>
				</div>
				<div class="col-sm-4 text-center benefits">
					<img src="/images/benefit3.png" alt="Exclusive member only offers" class="img-responsive" />
					<p>Exclusive member only offers</p>
				</div>
				<div class="col-sm-4 text-center benefits">
					<img src="/images/benefit4.png" alt="Unlimited wallet and fridge cards listing your details" class="img-responsive" />
					<p>Unlimited wallet and fridge cards listing your details</p>
				</div>
				<div class="col-sm-4 text-center benefits">
					<img src="/images/benefit5.png" alt="Secure online access to your electronic health record " class="img-responsive" />
					<p>Secure online access to your electronic health record </p>
				</div>
				<div class="col-sm-4 text-center benefits">
					<img src="/images/benefit6.png" alt="Support from our Membership Services team" class="img-responsive" />
					<p>Support from our Membership Services team</p>
				</div>
			</div>
			</div>
		</div>
	</div>
</div>


<div id="contact" class="share1">
  <div class="container">
 	 <div class="row">
	 	<div class="col-md-offset-1 col-md-10 text-center">
		 	<h4>Share your story</h4>
		 	<p>We’d love to hear about what your MedicAlert membership means to you. No matter how big or small your story may be, we welcome you to share it with us. Your very own story could inspire others, and help demonstrate the importance of membership when it matters most.</p>
	 	</div>
		<div class="col-md-offset-2 col-md-8 text-center">
    	 	<form id="contact_form" accept-charset="UTF-8" method="post" action="/process/contact-us" novalidate="novalidate">
        	    <input type="hidden" name="formToken" id="formToken" value="{$token}" />
        	  	<input type="hidden" value="Share your story" name="form_name" id="form_name" />
    			<input type="hidden" name="timestamp" id="timestamp" value="{$timestamp}" />
    	  		<div class="row">
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="name">Full name<span>*</span>:</label>
    					<input class="form-control" value="{if $post.name}{$post.name}{else}{$user.gname} {$user.surname}{/if}" type="text" name="name" id="name" required="">
						<div class="error-msg help-block"></div>
    				</div>
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="email">Email<span>*</span>:</label>
    					<input class="form-control" value="{if $post.email}{$post.email}{else}{$user.email}{/if}" type="email" name="email" id="email" required="">
						<div class="error-msg help-block"></div>
    				</div>
    			</div>
    			<div class="row">
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="phone">Phone<span>*</span>:</label>
    				  <input class="form-control" value="{if $post.phone}{$post.phone}{else}{$user.maf.main.user_mobile}{/if}" type="text" name="phone" id="phone" required="">
						<div class="error-msg help-block"></div>
    				</div>

                    <div class="col-sm-6 form-group">
                      <label class="visible-ie-only" for="postcode">Postcode<span>*</span>:</label>
                      <input class="form-control" value="{if $post.postcode}{$post.postcode}{else}{$user.maf.main.user_postcode}{/if}" maxlength="4" type="text" name="postcode" id="postcode"  required="">
						<div class="error-msg help-block"></div>
                    </div>
    			</div>
    			<div class="row">
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="membership_no">Membership number:</label>
    				  <input class="form-control" value="{if $post.membership_no}{$post.membership_no}{else}{$user.id}{/if}" type="text" name="membership_no" id="membership_no" >
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
                   <input value="" type="text" name="honeypot" id="honeypot" tabindex="-1" autocomplete="off">
                </div>
				<div class="row">
					<div class="col-sm-12">
						<span class="bold">Your name and story will not be published without your approval and consent.</span>
						<br /><br />
					</div>
				</div>
    			<div class="row error-msg" id="form-error" {if !$error}style="display:none"{/if}>{$error}</div>
    			<div class="row">
    				<div class="col-sm-12">
    					<input type="button" value="Share your story" onclick="$('#contact_form').submit();" class="btn-red btn" id="fbsub">
    				</div>
    			</div>
    	  </form>
		</div>


		</div>
	</div>
</div>

<div id="whywait">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<h3>If someone you know could benefit from a MedicAlert membership, don’t wait until it’s too late to tell them about it.</h3>
				<a href="/refer-a-friend" class="btn btn-red">Refer a friend</a>
			</div>
		</div>
	</div>
</div>

<div id="donate">
	<div class="container">
        <div class="row" id="help">
	        <div class="col-sm-12 col-md-8 helpl">
			  <form class="form-horizontal" id="product-form" role="form" accept-charset="UTF-8" action="" method="post">
                <input type="hidden" value="ADDTOCART" name="action" id="action" />
                <input type="hidden" name="formToken" id="formToken" value="{$token}" />
                <input type="hidden" value="{$products.product_object_id}" name="product_id" id="product_id" />
                
                <h3>Help us to help others</h3>
				<p>While you’re here, why not make a small donation to our not-for-profit organisation? Just a few dollars can help provide our life-saving service, and allow us to educate Australians about the importance of MedicAlert Foundation. Donations over $2 are tax deductible. </p>

				<h4>Select a donation amount:</h4>
				{foreach $products.variants as $v}
				<div class="donbtn">
				  	<label for="variant-{$v.variant_id}">
	                    <input type="radio" value="{$v.variant_id}" data-value="{$v.variant_price|number_format:0:'.':','}" class="{if $v.variant_editableprice eq 1}show-otherval{/if}" name="variant_id" id="variant-{$v.variant_id}">
                        <input type="hidden" disabled value="{$v.attr_value_id}" name="attr[{$v.attribute_id}][id]" id="attribute_id-{$v.variant_id}" class="variant-attributes"/>
                    	<div id="variant-{$v.variant_id}-btn" class="donate-btn btn btn-grey">{if $v.variant_editableprice eq 1}Other{else}${$v.variant_price|number_format:0:'.':','}{/if}</div>
                    </label>
                </div>
                {/foreach}
                <div class="clearl" id="othervalout">
                  <div class="form-group">
                    <input type="text" id="otherval" name="price" placeholder="Please specify an amount" required />
                  <div class="help-block text-center small">Please only specify a whole dollar amount.</div>
                  </div>
                </div>
				<div class="clearl">
                    <button id="prod-submit-btn" type="submit" disabled class="btn btn-red">Add to cart</button>
				</div>
                </form>
	        </div>
	        <div class="col-sm-4 hidden-xs hidden-sm helpr">
				<img src="/images/cart-girls.png" class="img-responsive" alt="Help us to help others" />
	        </div>
        </div>
	</div>
</div>
{/block} {* Place additional javascript here so that it runs after General JS includes *} {block name=tail}
<script type="text/javascript">
$(document).ready(function(){

	 	$('#contact_form').validate();

	 	$('#pass_form').validate({
         submitHandler: function(form) {
           var formID = $(form).attr('id');
           $('body').css('cursor', 'wait');
           $('#' + formID).find('.error-alert').hide();
           $('#' + formID).find('.success-alert').hide();
           var datastring = $('#' + formID).serialize();
           $.ajax({
             type: "POST",
             url: "/process/user",
             cache: false,
             data: datastring,
             dataType: "json",
             success: function(obj) {
               try{
                 if(obj.error){
                   $('#' + formID).find('.error-alert').find('strong').html(obj.error);
                   $('#' + formID).find('.error-alert').fadeIn('slow');
                 }else if(obj.success){
                   $('#' + formID).find('.success-alert').find('strong').html(obj.success);
                   $('#' + formID).find('.success-alert').fadeIn('slow');
                   $('#' + formID).find('.showpassword').val('');
                 }
                 if(obj.refresh){
                   setTimeout(function() {
                     location.reload();
                   }, 5000);	
                 }
               }catch(err){
                 console.log('TRY-CATCH error');
               }
               $('body').css('cursor', 'default');
             },
             error: function(jqXHR, textStatus, errorThrown) {
               $('#' + form).find('.error-alert').find('strong').html('Undefined error');
               $('#' + form).find('.error-alert').fadeIn('slow');
               $('body').css('cursor', 'default');
               console.log('AJAX error:' + errorThrown);
             }
           });
         }
       });
	 	
	 	$('#newpassword').rules("add", {
           minlength: 8,
           hasLowercase: true,
           hasUppercase: true,
           hasDigit: true
         });

	 	$('#postcode').rules("add", {
			digits: true,
			minlength: 4
		});

	 	$('#email').rules("add", {
			email: true
		});


	//DONATIONS FORM
   $('#product-form').validate({
     submitHandler: function(form) {
       addCart($(form).attr('id'), true);
     }
   });
 
   $('#otherval').rules("add", {
     required: true,
     digits: true,
     max: 1000
   });

$('input[name="variant_id"]').change(function(){
     $('.donate-btn').removeClass('active'); 
     $('#prod-submit-btn').removeAttr('disabled');
     
     //Set attribute
     $('.variant-attributes').attr('disabled', 'disabled');
     $('#attribute_id-' + $(this).val()).removeAttr('disabled');
     
     //Show/hide/highligth content based on selection
     $('#variant-' + $(this).val() + '-btn').addClass('active');
     if($('#variant-' + $(this).val()).hasClass('show-otherval')){
       $('#othervalout').fadeIn('slow');
       $('#otherval').val('');
     }else{
       $('#othervalout').hide();
       $('#otherval').val( $('#variant-' + $(this).val()).attr('data-value') );
     }
   });

});

</script>
{/block}
