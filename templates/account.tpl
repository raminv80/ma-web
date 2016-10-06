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
			         <img src="/images/dashboard-warning.png" alt="active" /> <span class="warning">Your are protected</span>
                    <div><br>You have <strong>{date_diff date_end=$smarty.now|date_format:"%Y-%m-%d" date_start=$renewalDate date_format='%a'} days</strong> until your reward is due.</div>
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
							<img src="/images/dashboard-password.png" alt="Account" /> <a href="javascript:void(0)" onclick="$('#password-form').show('slow');">Update my password</a>
						</div>
					</div>
					<div class="accrow" id="password-form" style="display: none;">
						<form id="pass_form" accept-charset="UTF-8" method="post" action="/process/" novalidate="novalidate">
						<input type="hidden" name="formToken" id="formToken" value="{$token}" />
						<input type="hidden" value="Share your story" name="pass_form" id="pass_form" />
						<input type="hidden" name="timestamp" id="timestamp" value="{$timestamp}" />
						<div class="col-sm-12 form-group">
						    <label for="pass" class="control-label bold">Current Password:<span>*</span></label>
							<input class="form-control showpassword" type="password" id="password1" name="pass" autocomplete="off" required />
							<a class="showhide" href="javascript:void(0);" onclick="if($(this).html() == 'Show'){ $(this).closest('div').find('input[type=password]').get(0).type='text';$(this).html('Hide'); }else{ $(this).closest('div').find('input[type=text]').get(0).type='password';$(this).html('Show'); }">Show</a>
							<span class="help-block"></span>
						</div>
						<div class="col-sm-12 form-group">
						    <label for="newpassword" class="control-label bold">New Password:<span>*</span></label>
							<input class="form-control showpassword" type="password" id="newpassword" name="newpassword" autocomplete="off" required />
							<a class="showhide" href="javascript:void(0);" onclick="if($(this).html() == 'Show'){ $(this).closest('div').find('input[type=password]').get(0).type='text';$(this).html('Hide'); }else{ $(this).closest('div').find('input[type=text]').get(0).type='password';$(this).html('Show'); }">Show</a>
							<span class="help-block"></span>
						</div>
						<div class="col-sm-12">
							<div class="passnote">Note: Your password must be at least 8 characters in length and contain at least 1 numeral and both upper and lower case letters.</div>
						</div>
						<div class="col-sm-12">
							 <button type="submit" class="btn btn-red">Update password</a>
						</div>
						</form>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>

			<div class="col-sm-12 text-center" id="renewal">
				<h3>Owen, is your membership up to date?</h3>
				<p>It’s important to keep your MedicAlert membership up to date so we can continue to help protect you in an emergency. Don’t wait until it’s too late.  </p>

				<div class="row">
					<div class="col-sm-6 text-center" id="viewup">
						<div>
							<img src="/images/dashboard-viewupdate.png" alt="View / update your profile" class="img-responsive" />
						</div>
						<a href="/update-my-profile" class="btn btn-red">View / update your profile</a>
					</div>
					<div class="col-sm-6 text-center" id="autor">
						<div>
							<img src="/images/dashboard-autorenew.png" alt="Register for auto-renewals" class="img-responsive" />
						</div>
						<a href="/auto-renewal" class="btn btn-red">Register for auto-renewals</a>

						<p>Take the hassle out of remembering to pay your annual MedicAlert membership with auto-renewals. By setting up Direct Debit payments from your nominated bank account, you can rest assured knowing you’ll always be protected.</p>
					</div>
				</div>
			</div>

			<div class="col-sm-12 text-center" id="reminder1">
				<h3>It looks like you haven’t purchased a new product in a while. </h3>
				<p>Now is a good time to check that your engraving is legible and the attachments (e.g. jump rings and clasps) are in good condition. If the engraving is difficult to read, or you’d simply like a new piece of jewelry, order a new one below. Replacement attachments can be purchased by calling Membership Services on <a href="tel://1800 88 22 22">1800 88 22 22</a>.</p>

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
						<a href="/products"	 class="btn btn-red">Find a new product</a>
					</div>
				</div>
			</div>



		</div>
	</div>
</div>

<div id="popprod">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<h3>Popular products</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-6 col-sm-3 col-md-2 col-md-offset-2 prod">
				<a href="#">
					<img src="/images/dashboard-popular1.jpg" alt="Popular product" class="img-responsive" />
				</a>
			</div>
			<div class="col-xs-6 col-sm-3 col-md-2 prod">
				<a href="#">
					<img src="/images/dashboard-popular2.jpg" alt="Popular product" class="img-responsive" />
				</a>
			</div>
			<div class="col-xs-6 col-sm-3 col-md-2 prod">
				<a href="#">
					<img src="/images/dashboard-popular3.jpg" alt="Popular product" class="img-responsive" />
				</a>
			</div>
			<div class="col-xs-6 col-sm-3 col-md-2 text-center prod">
						<a href="/products">
							<img src="/images/dashboard-findnew.png" class="img-responsive" alt="Find a new product" title="Find a new product" />
						</a>
			</div>
		</div>
	</div>
</div>


<div id="specialoffer">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<h3>Special offers just for you</h3>
			</div>
		</div>
		<div class="row text-center">
			<div class="col-sm-4 specials">
				<a href="#">
					<img src="/images/ad-dad-gift.jpg" alt="Special product" class="img-responsive" />
				</a>
			</div>

			<div class="col-sm-4 specials">
				<a href="#">
					<img src="/images/ad-gorgeous-gold.jpg" alt="Special product" class="img-responsive" />
				</a>
			</div>

			<div class="col-sm-4 specials">
				<a href="#">
					<img src="/images/ad-stay-protected.jpg" alt="Special product" class="img-responsive" />
				</a>
			</div>
		</div>
	</div>
</div>

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
				<h3>Help us to help others</h3>
				<p>While you’re here, why not make a small donation to our not-for-profit organisation? Just a few dollars can help provide our life-saving service, and allow us to educate Australians about the importance of MedicAlert Foundation. Donations over $2 are tax deductible. </p>

				<h4>Select a donation amount:</h4>
				<div class="donbtn">
				  	<label for="donate25">
	                    <input type="radio" value="25" name="donate" id="donate25">
                    	<div id="donate25-btn" class="donate-btn btn btn-grey">$25</div>
                    </label>
                </div>
				<div class="donbtn">
				  	<label for="donate50">
	                    <input type="radio" value="50" name="donate" id="donate50">
                    	<div id="donate50-btn" class="donate-btn btn btn-grey">$50</div>
                    </label>
                </div>
				<div class="donbtn">
				  	<label for="donate100">
	                    <input type="radio" value="100" name="donate" id="donate100">
                    	<div id="donate100-btn" class="donate-btn btn btn-grey">$100</div>
                    </label>
                </div>
				<div class="donbtn">
				  	<label for="donateother">
	                    <input type="radio" value="other" name="donate" id="donateother">
                    	<div id="donateother-btn" class="donate-btn btn btn-grey">Other</div>
                    </label>
                </div>
                <div class="clearl" id="othervalout">
                    <input type="text" id="otherval" name="otherval" placeholder="Please specify an amount" />
					<div class="text-center small">
                    Please only specify a whole dollar amount.
					</div>
                </div>
				<div class="clearl">
					<a href="#" class="btn btn-red">Add to cart</a>
				</div>
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

	 	$('#pass_form').validate();

	 	$('#postcode').rules("add", {
			digits: true,
			minlength: 4
		});

	 	$('#email').rules("add", {
			email: true
		});

		$("input[name=donate]").change(function()
		{
        if ( $("#donateother").is(':checked'))
            $("#othervalout").show();
        else
            $("#othervalout").hide();
		});


    $('input[name="donate"]').change(function(){
      $('.donate-btn').removeClass('active');
      $('#donate'+ $(this).val()+ '-btn').addClass('active');
    });

});

</script>
{/block}
