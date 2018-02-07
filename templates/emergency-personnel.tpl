{block name=head}
<style type="text/css">
{literal}
#newsletter{display:none;}
{/literal}
</style>
{/block}
{block name=body}
<div id="pagehead">
  <div class="bannerout">
    <img alt="Emergency personnel banner" src="{$listing_image}">
  </div>
  <div class="container" id="contpage">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 text-center" id="listtoptext">
          <h1>{$listing_title}</h1>
        </div>
        <div class="col-sm-8 col-sm-offset-2 text-center">
          {$listing_content1}
        </div>
    </div>
   </div>
</div>

<div class="emergency-grey">
  <div class="container">
     <div class="row">
        <div class="col-sm-8 col-sm-offset-2 text-center">
          {$listing_content2}
        </div>
     </div>
   </div>
</div>
<div class="container nobotmargin" id="landing-newsart">
  <div class="row">
  {if $latest_news_articles}
  {foreach $latest_news_articles as $a}
    <div class="col-sm-6 col-md-4">
      <div class="newsres">
        <a href="{if $a.parent_listing_url}/{$a.parent_listing_url}{/if}/{$a.listing_url}">
          <img src="{if $a.listing_image}{$a.listing_image}{else}/images/medic-alert-logo.jpg{/if}" alt="{$a.listing_name}" class="img-responsive fullwidth">
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

<div id="stay-loop" class="emergency-whitebg">
  <div class="container">
     <div class="row subscribe_form_wrapper">
        <div class="col-sm-12 text-center">
	      <h3>Subscribe to receive MedicAlert and industry updates</h3>
        </div>
        <div class="col-md-offset-1 col-md-10 text-center" id="stayloop">
    	 	<form id="contact_form1" accept-charset="UTF-8" method="post" action="" novalidate="novalidate">
        	    <input type="hidden" name="formToken" id="formToken" value="{$token}" />
        	  	<input type="hidden" value="Newsletter subscription - emergency personal" name="form_name" id="form_name" />
    			    <input type="hidden" name="timestamp" id="timestamp" value="{$timestamp}" />
    	  		<div class="row">
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="loop-fname">First name<span>*</span>:</label>
    					<input class="form-control" value="" type="text" name="loop-fname" id="loop-fname" required="">
              <div class="error-msg help-block"></div>
    				</div>
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="loop-lname">Surname<span>*</span>:</label>
    					<input class="form-control" value="" type="text" name="loop-lname" id="loop-lname" required="">
              <div class="error-msg help-block"></div>
    				</div>
    			  </div>

            <div class="row">
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="loop-position">Position:</label>
    				  <input class="form-control" value="" type="text" name="loop-position" id="loop-position" >
              <div class="error-msg help-block"></div>
    				</div>
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="loop-email">Email<span>*</span>:</label>
    					<input class="form-control" value="" type="email" name="loop-email" id="loop-email" required="">
						<div class="error-msg help-block"></div>
						<!--<div>By providing your email address, you consent to receive promotional and health related material.</div>-->
    				</div>
    			  </div>
    			<div class="row">
            <div class="col-sm-12 error-alert" style="display: none;">
              <div class="alert alert-danger fade in ">
                <button class="close" aria-hidden="true" type="button" onclick="$(this).closest('.error-alert').fadeOut('slow');">&times;</button>
                <strong></strong>
              </div>
            </div>
          </div>

    			<div class="row">
    				<div class="col-sm-12">
    					<input type="button" value="Register now" onclick="$('#contact_form1').submit();" class="btn-red btn" id="fbsub1">
    				</div>
    			</div>


    	 	</form>
        </div>
     </div>
     <div class="row">
        <div class="col-sm-12 success-alert" id="subscribe-success-message" style="display: none;">
          <div class="alert alert-success fade in text-center">
            <button class="close" aria-hidden="true" type="button" onclick="$(this).closest('.success-alert').fadeOut('slow');">&times;</button>
            <strong></strong>
          </div>
        </div>
      </div>
  </div>
</div>

<div class="emergency-grey">
  <div class="container">
    <div class="row">
	    <div class="col-sm-12 text-center">
          <h3>Know what to do in an emergency</h3>
          <p>In the first instance of an emergency, you should follow these four simple steps.</p>
        </div>
        <div class="col-sm-3 text-center step">
	      <div class="col-xs-3 col-sm-12">
          <img alt="Check" src="/images/emergency-check.png" class="img-responsive">
	      </div>
	      <div class="col-xs-9 col-sm-12">
          <div class="bold">Check</div>
          <div>around your patients' wrists or neck (pulse point) for the genuine MedicAlert ID. If patient is conscious, ask if they are a MedicAlert member.</div>
	      </div>
        </div>
        <div class="col-sm-3 text-center step">
	      <div class="col-xs-3 col-sm-12">
          <img alt="Read" src="/images/emergency-read.png" class="img-responsive">
	      </div>
	      <div class="col-xs-9 col-sm-12">
          <div class="bold">Read</div>
          <div>the medical and personal information engraved on the reverse of the patient's MedicAlert ID.</div>
	      </div>
        </div>
        <div class="col-sm-3 text-center step">
	      <div class="col-xs-3 col-sm-12">
          <img alt="Call" src="/images/emergency-call.png" class="img-responsive">
	      </div>
	      <div class="col-xs-9 col-sm-12">
          <div class="bold">Call</div>
          <div>the 24/7 Emergency Response Service number engraved on the ID (<a href="tel://08 8272 8822">08 8272 8822</a>) to receive more detailed information about a patient’s conditions, medications, allergies or personal contacts.</div>
	      </div>
        </div>
        <div class="col-sm-3 text-center step">
	      <div class="col-xs-3 col-sm-12">
          <img alt="Advice" src="/images/emergency-advice.png" class="img-responsive">
	      </div>
	      <div class="col-xs-9 col-sm-12">
          <div class="bold">Advise</div>
          <div>on handover that your patient is wearing a MedicAlert ID.</div>
	      </div>
        </div>
     </div>
   </div>
</div>
<div class="emergency-whitebg">
  <div class="container">
   <div class="row">
      <div class="col-sm-12 text-center" id="listtoptext">
        <h3>Know what MedicAlert ID to look for</h3>
      </div>
      <div class="col-sm-8 col-sm-offset-2 text-center">
        {$listing_content3}
      </div>
   </div>
  </div>
</div>


<div id="emergency-white" class="emergency-grey">
   <div class="container">
     <div class="row">
        <div class="col-sm-12 text-center">
	      <h3>Order your free resource pack</h3>
        </div>
		<div class="col-md-offset-1 col-md-10 text-center" id="sharestory">
    	 	<form id="contact_form" accept-charset="UTF-8" method="post" action="/process/resource-contact" novalidate="novalidate">
        	    <input type="hidden" name="formToken" id="formToken" value="{$token}" />
        	  	<input type="hidden" value="Order resource pack" name="form_name" id="form_name" />
    			<input type="hidden" name="timestamp" id="timestamp" value="{$timestamp}" />
    	  		<div class="row">
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="fname">First name<span>*</span>:</label>
    					<input class="form-control" value="{$post.fname}" type="text" name="fname" id="fname" required="">
						<div class="error-msg help-block"></div>
    				</div>
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="lname">Surname<span>*</span>:</label>
    					<input class="form-control" value="{$post.lname}" type="text" name="lname" id="lname" required="">
						<div class="error-msg help-block"></div>
    				</div>
    			</div>
    			<div class="row">
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="jobtitle">Job title:</label>
    				  <input class="form-control" value="{$post.jobtitle}" type="text" name="jobtitle" id="jobtitle" >
						<div class="error-msg help-block"></div>
    				</div>

                    <div class="col-sm-6 form-group">
                      <label class="visible-ie-only" for="company">Company name<span>*</span>:</label>
                      <input class="form-control" value="{$post.company}" type="text" name="company" id="company"  required="">
						<div class="error-msg help-block"></div>
                    </div>
    			</div>
    			<div class="row">
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="department">Department:</label>
    				  <input class="form-control" value="{$post.department}" type="text" name="department" id="department">
						<div class="error-msg help-block"></div>
    				</div>
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="address">Postal address<span>*</span>:</label>
    				  <input class="form-control" value="{$post.address}" type="text" name="address" id="address" required="">
						<div class="error-msg help-block"></div>
    				</div>
    			</div>
    			<div class="row">
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="suburb">Suburb<span>*</span>:</label>
    				  <input class="form-control" value="{$post.suburb}" type="text" name="suburb" id="suburb" required="">
						<div class="error-msg help-block"></div>
    				</div>
                    <div class="col-sm-6 form-group">
                      <label class="visible-ie-only" for="state">State<span>*</span>:</label>
                      <select class="form-control" name="state" id="state"  required="">
	                      <option value="">Please select</option>
	                      <option {if $post.state eq 'ACT'}selected = "selected"{/if} value="ACT">ACT</option>
	                      <option {if $post.state eq 'NSW'}selected = "selected"{/if} value="NSW">NSW</option>
	                      <option {if $post.state eq 'NT'}selected = "selected"{/if} value="NT">NT</option>
	                      <option {if $post.state eq 'QLD'}selected = "selected"{/if} value="QLD">QLD</option>
	                      <option {if $post.state eq 'SA'}selected = "selected"{/if} value="SA">SA</option>
	                      <option {if $post.state eq 'TAS'}selected = "selected"{/if} value="TAS">TAS</option>
	                      <option {if $post.state eq 'VIC'}selected = "selected"{/if} value="VIC">VIC</option>
	                      <option {if $post.state eq 'WA'}selected = "selected"{/if} value="WA">WA</option>
                      </select>
						<div class="error-msg help-block"></div>
                    </div>
    			</div>
    			<div class="row">
                    <div class="col-sm-6 form-group">
                      <label class="visible-ie-only" for="postcode">Postcode<span>*</span>:</label>
                      <input class="form-control" value="{$post.postcode}" maxlength="4" type="text" name="postcode" id="postcode" pattern="[0-9]*" required="">
						<div class="error-msg help-block"></div>
                    </div>
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="phone">Phone:</label>
    					<input class="form-control" value="{$post.phone}" type="text" name="phone" id="phone" pattern="[0-9]*">
						<div class="error-msg help-block"></div>
    				</div>
    			</div>
    			<div class="row">
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="email">Email<span>*</span>:</label>
    					<input class="form-control" value="{$post.email}" type="email" name="email" id="email" required="">
						<div class="error-msg help-block"></div>
						<!--<div>By providing your email address, you consent to receive promotional and health related material.</div>-->
    				</div>
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="category">Category<span>*</span>:</label>
                      <select class="form-control" name="category" id="category"  required="">
	                      <option value="">Please select</option>
	                      <option {if $post.category eq 'Hospital'}selected = "selected"{/if} value="Hospital">Hospital</option>
	                      <option {if $post.category eq 'Pharmacy'}selected = "selected"{/if} value="Pharmacy">Pharmacy</option>
	                      <option {if $post.category eq 'Medical practice'}selected = "selected"{/if} value="Medical practice">Medical practice</option>
	                      <option {if $post.category eq 'Specialist - Cardiac'}selected = "selected"{/if} value="Specialist - Cardiac">Specialist - Cardiac</option>
	                      <option {if $post.category eq 'Specialist - Diabetes'}selected = "selected"{/if} value="Specialist - Diabetes">Specialist - Diabetes</option>
	                      <option {if $post.category eq 'Specialist - Other'}selected = "selected"{/if} value="Specialist - Other">Specialist - Other</option>
	                      <option {if $post.category eq 'Other'}selected = "selected"{/if} value="Other">Other</option>
                      </select>
						<div class="error-msg help-block"></div>
    				</div>
    			</div>
    			<div class="row">
    				<div class="col-sm-12 form-group text-left">
						<input type="checkbox" {if !$post || $post.resource_pack}checked="checked"{/if} name="resource_pack" id="resourcepack" />
						<label class="visible-ie-only" for="resourcepack">I need a resource pack</label>
						<div class="error-msg help-block"></div>
						<!-- <div>(Resource pack contains 1 x A3 poster, a supply of Membership catalogues, Code of Conduct booklet and a sample membership card)</div> -->
    				</div>
    			</div>

    			<div class="row">
	    			<div class="col-sm-12">
		    			<h4 class="bold">Additional resources</h4>
	    			</div>
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="membershipcat">Membership catalogues:</label>
                      <select class="form-control" name="membership_catalogues" id="membershipcat">
	                      <option value="0">Please select</option>
	                      <option {if $post.membership_catalogues eq '20'}selected = "selected"{/if} value="20">20</option>
	                      <option {if $post.membership_catalogues eq '40'}selected = "selected"{/if} value="40">40</option>
	                      <option {if $post.membership_catalogues eq '100'}selected = "selected"{/if} value="100">100</option>
                      </select>
						<div class="error-msg help-block"></div>
    				</div>
    				<div class="col-sm-6 form-group">
    				  <label class="visible-ie-only" for="a3posters">A3 posters:</label>
                      <select class="form-control" name="a3_posters" id="a3posters">
	                      <option value="0">Please select</option>
	                      <option {if $post.a3_posters eq '1'}selected = "selected"{/if} value="1">1</option>
	                      <option {if $post.a3_posters eq '2'}selected = "selected"{/if} value="2">2</option>
	                      <option {if $post.a3_posters eq '3'}selected = "selected"{/if} value="3">3</option>
                      </select>
						<div class="error-msg help-block"></div>
    				</div>
    			</div>

          <div class="row">
            <div class="col-sm-12 form-group text-left">
              <label class="visible-ie-only" for="user_want_promo">
              <input type="checkbox" {if !$post || $post.user_want_promo}checked="checked" {/if}name="user_want_promo" id="user_want_promo" />
             Please keep me up to date with the latest information from MedicAlert.</label> <span class="hidden-xs"><a href="privacy-policy" target="_blank">Privacy Policy</a></span>
              <div class="error-msg help-block"></div>
              <span class="text-center visible-xs"><a href="privacy-policy" target="_blank">Privacy Policy</a></span>
            </div>
          </div>

    			<div style="height:0;overflow:hidden;">
                   <input value="" type="text" name="honeypot" id="honeypot" tabindex="-1">
                </div>
    			<div class="row error-msg" id="form-error" {if !$error}style="display:none"{/if}>{$error}</div>
    			<div class="row">
    				<div class="col-sm-12">
    					<input type="button" value="Order resource pack" onclick="$('#contact_form').submit();" class="btn-red btn" id="fbsub">
    				</div>
    			</div>
    			<br /><br />
    			<br />
    	  </form>
		</div>
     </div>
   </div>
</div>

<div id="seconds-count" class="emergency-whitebg">
  <div class="container">
    <div class="row">
      <div class="col-sm-10 col-sm-offset-1 text-center"><h3>View our extensive range of MedicAlert IDs</h3></div>
      <div class="col-sm-10 col-sm-offset-1 text-center">
        <img src="/images/dashboard-prods.png" alt="Products" class="img-responsive" />
      </div>
      <div class="col-sm-10 col-sm-offset-1 text-center">
        <a href="/products" title="Click to view our product range" class="btn btn-red">View more</a>
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

    $('#contact_form1').validate({
      submitHandler: function(form) {
        SubmitSubscriptionForm($(form).attr('id'));
      }
    });

	 	$('#contact_form').validate();

	 	$('#postcode').rules("add", {
			digits: true,
			minlength: 4
		});

	 	$('#phone').rules("add", {
     		digits: true,
     		minlength: 8
     	});

		$("select").selectBoxIt();


	 	$('#email').rules("add", {
			email: true
		});

});

function SubmitSubscriptionForm(FORM) {
    $('body').css('cursor', 'wait');
    $('#' + FORM).find('.error-alert').hide();
    var datastring = $('#' + FORM).serialize();
    $.ajax({
      type: "POST",
      url: "/process/subscribe",
      cache: false,
      data: datastring,
      dataType: "json",
      success: function(obj) {
        try{
          if(obj.success){
            $('#subscribe-success-message').find('strong').html(obj.success);
            $('#subscribe-success-message').fadeIn('slow');
            $('.subscribe_form_wrapper').hide();
          }else if(obj.error){
            $('#' + FORM).find('.error-alert').find('strong').html(obj.error);
            $('#' + FORM).find('.error-alert').fadeIn('slow');
          }
        }catch(err){
          console.log('TRY-CATCH error');
        }
        $('body').css('cursor', 'default');
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $('#' + FORM).find('.error-alert').find('strong').html('Undefined error.<br>Please refresh the page and try again or <a href="/contact-us">contact us</a>.');
        $('#' + FORM).find('.error-alert').fadeIn('slow');
        $('body').css('cursor', 'default');
        console.log('AJAX error:' + errorThrown);
      }
    });
  }

</script>
{/block}
