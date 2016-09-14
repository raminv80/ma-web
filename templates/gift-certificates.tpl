{block name="head"}
<link href="/includes/css/jquery-ui.css" rel="stylesheet" media="screen">
{/block}
{block name=body}
<div id="pagehead">
	<div class="bannerout">
		<img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
	</div>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-8 col-md-offset-2 text-center">
				<h1>{$listing_title}</h1>
				{$listing_content1}
			</div>
		</div>
	</div>
</div>

<div id="giftgrey">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<h3>Purchase your gift certificate now</h3>
				<p>Please select a gift certificate amount:</p>

				<form id="gift_form" accept-charset="UTF-8" method="post" action="/process/contact-us" novalidate="novalidate">
				<div class="row">
					<div class="col-sm-3">
						<div class="giftoption">
							<label for="gift32">
							<input type="radio" id="gift32" value="$32" name="giftval" />
							<div class="giftopin">
								<div class="giftopimg">
									<img src="/images/gift-1.jpg" class="img-responsive" alt="$32" title="$32" />
								</div>
								<div class="giftoptext">
								<h3><span>$</span>32</h3>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna</p>
								<a href="javascript:void(0);" class="btn btn-red">Select</a>
								</div>
							</div>
							</label>
						</div>
					</div>

					<div class="col-sm-3">
						<div class="giftoption">
							<label for="gift47">
							<input type="radio" id="gift47" value="$47" name="giftval" />
							<div class="giftopin">
								<div class="giftopimg">
									<img src="/images/gift-2.jpg" class="img-responsive" alt="$47" title="$47" />
								</div>
								<div class="giftoptext">
								<h3><span>$</span>47</h3>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna</p>
								<a href="javascript:void(0);" class="btn btn-red">Select</a>
								</div>
							</div>
							</label>
						</div>
					</div>

					<div class="col-sm-3">
						<div class="giftoption">
							<label for="gift52">
							<input type="radio" id="gift52" value="$52" name="giftval" />
							<div class="giftopin">
								<div class="giftopimg">
									<img src="/images/gift-3.jpg" class="img-responsive" alt="$52" title="$52" />
								</div>
								<div class="giftoptext">
								<h3><span>$</span>52</h3>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna</p>
								<a href="javascript:void(0);" class="btn btn-red">Select</a>
								</div>
							</div>
							</label>
						</div>
					</div>

					<div class="col-sm-3">
						<div class="giftoption">
							<label for="gift120">
							<input type="radio" id="gift120" value="$120" name="giftval" />
							<div class="giftopin">
								<div class="giftopimg">
									<img src="/images/gift-4.jpg" class="img-responsive" alt="$120" title="$120" />
								</div>
								<div class="giftoptext">
								<h3><span>$</span>120</h3>
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
			    				<div class="col-sm-6 form-group">
			    				  <label class="visible-ie-only" for="rname">Recipient's name<span>*</span>:</label>
			    					<input class="form-control" value="{$post.rname}" type="text" name="rname" id="rname" required="">
									<div class="error-msg help-block"></div>
			    				</div>
			    				<div class="col-sm-6 form-group">
			    				  <label class="visible-ie-only" for="remail">Recipient's email<span>*</span>:</label>
			    					<input class="form-control" value="{$post.remail}" type="email" name="remail" id="remail" required="">
									<div class="error-msg help-block"></div>
			    				</div>
			    			</div>
			    			<div class="row">
			    				<div class="col-sm-6 form-group">
			    				  <label class="visible-ie-only" for="sname">Your name<span>*</span>:</label>
			    				  <input class="form-control" value="{$post.sname}" type="text" name="sname" id="sname" required="">
									<div class="error-msg help-block"></div>
			    				</div>

			                    <div class="col-sm-6 form-group">
			                      <label class="visible-ie-only" for="semail">Your email<span>*</span>:</label>
			                      <input class="form-control" value="{$post.semail}" type="text" name="semail" id="semail"  required="">
									<div class="error-msg help-block"></div>
			                    </div>
			    			</div>
			    			<div class="row">
								<div class="col-sm-12">
									<input type="checkbox" class="form-control" value="Send anonymously" id="anonymous" name="anonymous" /><label for="anonymous">Send anonymously</label>
								</div>
			    			</div>
			    			<div class="row">
			    				<div class="col-sm-12 form-group">
			    				  <label class="visible-ie-only" for="message">Message (optional):</label>
			    					<textarea class="form-control" name="message" id="message">{$post.message}</textarea>
									<div class="error-msg help-block"></div>
			    				</div>
			    			</div>
			    			<div class="row">
								<div class="col-sm-6">
									<div class="clearl">
									<input type="radio" class="form-control" value="Send now" id="sendnow" name="sendtime" /><label for="sendnow">Send now</label>
									</div>
									<div class="clearl">
									<input type="radio" class="form-control" value="Select a day to send" id="selectday" name="sendtime" /><label for="selectday">Select a day to send</label></div>
									<br /><br />
									<input type="text" id="sendday" name="sendday" />
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
									<p>*All certificates with a set delivery date will be sent at 9.00 am AEST.<br />
									Please ensure you have read our <a href="#">Terms and Conditions</a> before purchasing a gift certificate.</p>
									<br />
				    			</div>
			    			</div>
			    			<div style="height:0;overflow:hidden;">
			                   <input value="" type="text" name="honeypot" id="honeypot" tabindex="-1">
			                </div>
			    			<div class="row error-msg" id="form-error" {if !$error}style="display:none"{/if}>{$error}</div>
			    			<div class="row">
			    				<div class="col-sm-12 text-center">
			    					<input type="button" value="Buy now" onclick="$('#gift_form').submit();" class="btn-red btn" id="fbsub">
			    				</div>
			    			</div>
					</div>
				</div>
		    	</form>
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


		$("#selectday").click(function(){
			$("#sendday").show();
		});

	 	$('#gift_form').validate();

});


</script>
{/block}
