{block name=head}
<style type="text/css">

</style>
{/block}

{block name=body}
<div id="pagehead">
	<div class="bannerout">
      <img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2 text-center">
				<h1>{$listing_name}</h1>
				{$listing_content1}
			</div>
		</div>
	</div>
</div>


<div id="giftgrey">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<h3>Make a donation to MedicAlert Foundation</h3>
				<h4>Donation details</h4>
				<p>Please select a donation amount:</p>

				<form id="gift_form" accept-charset="UTF-8" method="post" action="/process/contact-us" novalidate="novalidate">
				<div class="row">
					<div class="col-sm-3">
						<div class="giftoption">
							<label for="gift25">
							<input type="radio" id="gift25" value="$25" name="giftval" />
							<div class="giftopin">
								<div class="giftopimg">
									<img src="/images/donate-1.jpg" class="img-responsive" alt="$25" title="$25" />
								</div>
								<div class="giftoptext">
								<h3><span>$</span>25</h3>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna</p>
								<a href="javascript:void(0);" class="btn btn-red">Select</a>
								</div>
							</div>
							</label>
						</div>
					</div>

					<div class="col-sm-3">
						<div class="giftoption">
							<label for="gift50">
							<input type="radio" id="gift50" value="$50" name="giftval" />
							<div class="giftopin">
								<div class="giftopimg">
									<img src="/images/donate-2.jpg" class="img-responsive" alt="$50" title="$50" />
								</div>
								<div class="giftoptext">
								<h3><span>$</span>50</h3>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna</p>
								<a href="javascript:void(0);" class="btn btn-red">Select</a>
								</div>
							</div>
							</label>
						</div>
					</div>

					<div class="col-sm-3">
						<div class="giftoption">
							<label for="gift100">
							<input type="radio" id="gift100" value="$100" name="giftval" />
							<div class="giftopin">
								<div class="giftopimg">
									<img src="/images/donate-3.jpg" class="img-responsive" alt="$100" title="$100" />
								</div>
								<div class="giftoptext">
								<h3><span>$</span>100</h3>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna</p>
								<a href="javascript:void(0);" class="btn btn-red">Select</a>
								</div>
							</div>
							</label>
						</div>
					</div>

					<div class="col-sm-3">
						<div class="giftoption">
							<label for="giftother">
							<input type="radio" id="giftother" value="other" name="giftval" />
							<div class="giftopin">
								<div class="giftopimg">
									<img src="/images/donate-4.jpg" class="img-responsive" alt="Other" title="Other" />
								</div>
								<div class="giftoptext">
								<h3>Other</h3>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna</p>
								<a href="javascript:void(0);" class="btn btn-red">Select</a>
								</div>
							</div>
							</label>
						</div>
					</div>
				</div>
				<br /><br />
				<div class="row">
					<div class="col-sm-12 col-md-8 col-md-offset-2">
			        	    <input type="hidden" name="formToken" id="formToken" value="{$token}" />
			        	  	<input type="hidden" value="Contact" name="form_name" id="form_name" />
			    			<input type="hidden" name="timestamp" id="timestamp" value="{$timestamp}" />
			    	  		<div class="row">
			    				<div class="col-sm-12 form-group" id="otheram">
			    				  <label class="visible-ie-only" for="amount">Please specify an amount:</label>
			    					<input class="form-control" value="{$post.amount}" type="text" name="amount" id="amount" >
									<div class="error-msg help-block"></div>
			    				</div>
			    			</div>
			    	  		<div class="row">
			    				<div class="col-sm-6 form-group">
			    				  <label class="visible-ie-only" for="fname">First name<span>*</span>:</label>
			    					<input class="form-control" value="{$post.fname}" type="text" name="fname" id="fname" required="">
									<div class="error-msg help-block"></div>
			    				</div>
			    				<div class="col-sm-6 form-group">
			    				  <label class="visible-ie-only" for="lname">Last email<span>*</span>:</label>
			    					<input class="form-control" value="{$post.lname}" type="text" name="lname" id="lname" required="">
									<div class="error-msg help-block"></div>
			    				</div>
			    			</div>
			    			<div class="row">
			    				<div class="col-sm-6 form-group">
			    				  <label class="visible-ie-only" for="email">Your email<span>*</span>:</label>
			    				  <input class="form-control" value="{$post.email}" type="email" name="email" id="email" required="">
									<div class="error-msg help-block"></div>
			    				</div>

			                    <div class="col-sm-6 form-group">
			                      <label class="visible-ie-only" for="pcode">Postcode<span>*</span>:</label>
			                      <input class="form-control" value="{$post.pcode}" type="text" name="pcode" id="pcode"  required="">
									<div class="error-msg help-block"></div>
			                    </div>
			    			</div>
							<br /><br />
			    			<div class="row">
								<div class="col-sm-12">
									<h3>Account information</h3>
								</div>
			    			</div>
			    			<div class="row">
			    				<div class="col-sm-6 form-group">
			    				  <label class="visible-ie-only" for="cardno">Card number<span>*</span>:</label>
			    				  <input class="form-control"  type="text" name="cardno" id="cardno" required="">
									<div class="error-msg help-block"></div>
			    				</div>

			                    <div class="col-sm-6 form-group">
			                      <label class="visible-ie-only" for="cardname">Cardholder name<span>*</span>:</label>
			                      <input class="form-control" type="text" name="cardname" id="cardname"  required="">
									<div class="error-msg help-block"></div>
			                    </div>
			    			</div>
			    			<div class="row">
			    				<div class="col-sm-12 form-group text-left">
									Payment accepted: <img src="/images/gift-cards.jpg" alt="Payment accepted" title="Payment accepted" id="accepted" />
			    				</div>
			    			</div>
			    			<div class="row">
			    				<div class="col-sm-3 form-group">
			    				  <label class="visible-ie-only">Expiry<span>*</span>:</label>
			    				  <div class="newl">
			    				  <select class="form-control" name="month" id="month" required="">
				    				  <option value=""></option>
				    				  <option value="01">01</option>
				    				  <option value="02">02</option>
				    				  <option value="03">03</option>
				    				  <option value="04">04</option>
				    				  <option value="05">05</option>
				    				  <option value="06">06</option>
				    				  <option value="07">07</option>
				    				  <option value="08">08</option>
				    				  <option value="09">09</option>
				    				  <option value="10">10</option>
				    				  <option value="11">11</option>
				    				  <option value="12">12</option>
			    				  </select>
			    				  <div class="flleft">/</div>
			    				  <select class="form-control" name="month" id="month" required="">
				    				  <option value=""></option>
				    				  <option value="16">16</option>
				    				  <option value="17">17</option>
				    				  <option value="18">18</option>
				    				  <option value="19">19</option>
				    				  <option value="20">20</option>
				    				  <option value="21">21</option>
				    				  <option value="22">22</option>
				    				  <option value="23">23</option>
				    				  <option value="24">24</option>
				    				  <option value="25">25</option>
				    				  <option value="26">26</option>
			    				  </select>
			    				  </div>
								  <div class="error-msg help-block"></div>
			    				</div>

			                    <div class="col-sm-3 form-group">
			                      <label class="visible-ie-only" for="securitycode">Security code<span>*</span>:</label>
			    				  <div class="newl">
			                      <input class="form-control" type="text" name="securitycode" id="securitycode"  required="">
			                      <img src="/images/gift-scode.jpg" alt="Security code" title="Security code" id="scodeimg" />
			    				  </div>
								  <div class="error-msg help-block"></div>
			                    </div>
			    			</div>
			    			<div class="row">
				    			<div class="col-sm-12 text-center">
					    			<br />
									<p>By selecting ‘Donate now’ you are agreeing to MedicAlert Foundation’s <a href="#">terms &amp; conditions</a> and <a href="#">privacy policy</a>.</p>
									<br />
				    			</div>
			    			</div>
			    			<div style="height:0;overflow:hidden;">
			                   <input value="" type="text" name="honeypot" id="honeypot" tabindex="-1">
			                </div>
			    			<div class="row error-msg" id="form-error" {if !$error}style="display:none"{/if}>{$error}</div>
			    			<div class="row">
			    				<div class="col-sm-12 text-center">
			    					<input type="button" value="Donate now" onclick="$('#gift_form').submit();" class="btn-red btn" id="fbsub">
			    				</div>
			    			</div>
					</div>
				</div>
		    	</form>
			</div>
		</div>
	</div>
</div>

<div id="cost-grey" class="howto donate">
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2 text-center">
				{$listing_content3}
				<br />
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4 text-center ways">
				<img src="/images/access-email.png" alt="Email" class="img-responsive" />
				<div class="grey-text">
					<span class="bold">Online</span><span>*</span><br />
					<a href="#gift_form">Donate online now</a>
				</div>
			</div>
			<div class="col-sm-4 text-center ways">
				<img src="/images/join-post.png" alt="Post" class="img-responsive" />
				<div class="grey-text">
					<span class="bold">Mailing a cheque or money order payable to:</span><br />
					Australia Medic Alert Foundation<br />
					Level 1, 210 Greenhill Road<br />
					Eastwood SA 5063
				</div>
			</div>
			<div class="col-sm-4 text-center ways">
				<img src="/images/access-phone.png" alt="Phone" class="img-responsive" />
				<div class="grey-text">
					<span class="bold">Calling our Membership Services team </span><br />
                  during business hours on <a class="tel" href="tel:{$COMPANY.toll_free}" title="Click to call">{$COMPANY.toll_free}</a>. Please have your credit card details ready.
				</div>
			</div>

		</div>
	</div>
</div>


{/block}

{* Place additional javascript here so that it runs after General JS includes *}
{block name=tail}
<script src="/includes/js/jquery-ui.js"></script>
<script src="/includes/js/jquery.selectBoxIt.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){

	    $( "#sendday" ).datepicker( {  dateFormat: "dd/mm/yy"} );

		$("select").selectBoxIt();

		$("input[name=giftval]").change(function()
		{
        if ( $("#giftother").is(':checked'))
            $("#otheram").show();
        else
            $("#otheram").hide();
		});


	 	$('#gift_form').validate();
});


</script>
{/block}
