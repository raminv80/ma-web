{block name=body}
	<div class="row-fluid">
		<div class="span16">
			<div class="content-left">
			    {image field=$top_image}
			</div>
		</div>
	</div>
      
      <div class="row-fluid">
        <div class="span16">
        	<div class="row-fluid">
			 <div class="span16">	
		          <div class="content-left">
			     <p class="breadcrumbs"><a href="/">Home</a> / {$page_name}</p>
		            <h1>{$page_name}</h1>
		           
		           </div><!-- /.content-left -->
			  </div>
			</div>
        </div><!-- /.span16-->
      </div><!-- /.row-fluid -->

	<div class="row-fluid">

	<div class="span8">
          <div class="content-left">
           
		 {$content}

		<div class="span16 warranty">
			<form id="emailform" name="emailform" action="/includes/processes/processes-contactus.php" method="post" onsubmit="return validateForm()">
			    <div class="row-fluid">
					<div class="span4">Name*:</div>
					<div class="span12"><input type="text" name="fname" class="req" /></div>
				</div>
			      <div class="row-fluid">
					<div class="span4">Email*:</div>
					<div class="span12"><input type="text" name="email" class="req email" /></div>
				</div>
			      <div class="row-fluid">
					<div class="span4">Enquiry:</div>
					<div class="span12"><textarea name="enquiry"></textarea></div>
				</div>
			      <div class="row-fluid">
					<div class="span4">Receive newsletter:</div>
					<div class="span12"><input type="checkbox" checked="" name="newsletter" /></div>
				</div>
				<div class="row-fluid">
					<div class="span4"></div>
					<div class="span12"><span class="note">Note: fields marked with an asterisk(*) are required to submit this form.</span></div>
				</div>
			    <div class="row-fluid">
					<div class="span4">{$token}</div>
					<div class="span12"><input type="submit" value="SUBMIT" ></div>
				</div>
			</form>
			<script type="text/javascript" src="/includes/js/validation.js"></script>
		</div>
	   </div>


	</div>
	<div class="span8">
		<div class="row-fluid">
			<div class="span15">
			<img alt="map" src="images/map.jpg">
			</div>
		</div>
	</div>

	<div class="span16">
			<span displaytext="ShareThis" class="st_sharethis_large" st_processed="yes"><span style="text-decoration:none;color:#000000;display:inline-block;cursor:pointer;" class="stButton"><span class="stLarge" style="background-image: url(&quot;http://w.sharethis.com/images/sharethis_32.png&quot;);"></span><img src="http://w.sharethis.com/images/check-big.png" style="position: absolute; top: -7px; right: -7px; width: 19px; height: 19px; max-width: 19px; max-height: 19px; display: none;"></span></span>
			<span displaytext="Facebook" class="st_facebook_large" st_processed="yes"><span style="text-decoration:none;color:#000000;display:inline-block;cursor:pointer;" class="stButton"><span class="stLarge" style="background-image: url(&quot;http://w.sharethis.com/images/facebook_32.png&quot;);"></span><img src="http://w.sharethis.com/images/check-big.png" style="position: absolute; top: -7px; right: -7px; width: 19px; height: 19px; max-width: 19px; max-height: 19px; display: none;"></span></span>
			<span displaytext="Tweet" class="st_twitter_large" st_processed="yes"><span style="text-decoration:none;color:#000000;display:inline-block;cursor:pointer;" class="stButton"><span class="stLarge" style="background-image: url(&quot;http://w.sharethis.com/images/twitter_32.png&quot;);"></span><img src="http://w.sharethis.com/images/check-big.png" style="position: absolute; top: -7px; right: -7px; width: 19px; height: 19px; max-width: 19px; max-height: 19px; display: none;"></span></span>
			<span displaytext="Email" class="st_email_large" st_processed="yes"><span style="text-decoration:none;color:#000000;display:inline-block;cursor:pointer;" class="stButton"><span class="stLarge" style="background-image: url(&quot;http://w.sharethis.com/images/email_32.png&quot;);"></span><img src="http://w.sharethis.com/images/check-big.png" style="position: absolute; top: -7px; right: -7px; width: 19px; height: 19px; max-width: 19px; max-height: 19px; display: none;"></span></span>
	</div>

      </div><!-- /.row-fluid -->
      
    <div class="row-fluid">
        <div class="span16">
		</div>
	</div>
{/block}