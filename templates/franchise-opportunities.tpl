{block name=body}
	<header>
		{include file='mobilemenu.tpl'}
		<div id="headout" class="headerbg">
				{include file='desktopmenu.tpl'}
				<div id="videobox">
					<div class="container">
						<div class="row-fluid">
							<div class="span7">
					  			{include file='breadcrumbs.tpl'}
					  			<h3 class="toptitle">{$listing_title}</h3>
					  			<div class="toptext">
					  				{$listing_content1}
					  			</div>
				  			</div>
						</div>
					</div>
				</div>
			</div>
	</header>
	<div id="orangebox">
		<div class="container">
			<div class="row-fluid orangecontent">
				<div class="span3">
		  			{foreach $gallery as $item}
		  				<img src="{$item.gallery_link}" alt="{$item.gallery_file}">
					{/foreach}
		  		</div>
		  		<div class="span8">
		  			{$listing_content2}
		  		</div>
	  		</div>
		</div>
	</div>
	
	<div id="whitebox">
	  	<div class="container">
	  		<div class="row-fluid frantext">
		  		<h3 class="title">Franchise enquiries</h3>
		  		<p>Please fill in your details below and we will get in touch</p>
	  		</div>
	  		<div class="row-fluid">
	  			<div class="span8"  id="franform">
	  				<form id="franchise_form" accept-charset="UTF-8" action="/includes/processes/processes-franchise.php" method="post">
		  				<div class="row-fluid">
		  					<div class="span6">
		  						<div class="row-fluid control-group">
		  							<div class="span3"><label class="control-label" for="franchisename">Name*:</label></div>
		  							<div class="span9 controls"><input type="text" name="franchisename" id="franchisename" class="req" /></div>
		  						</div>
		  						<div class="row-fluid control-group">
		  							<div class="span3"><label class="control-label" for="franchisephone">Phone*:</label></div>
		  							<div class="span9 controls"><input type="text" name="franchisephone" id="franchisephone" class="req" /></div>
		  						</div>
		  						<div class="row-fluid control-group">
		  							<div class="span3"><label class="control-label" for="franchiseemail">Email*:</label></div>
		  							<div class="span9 controls"><input type="text" name="franchiseemail" id="franchiseemail" class="req" /></div>
		  						</div>
		  						<div class="row-fluid control-group">
		  							<div class="span3"><label class="control-label" for="franchisepostcode">Postcode*:</label></div>
		  							<div class="span9 controls"><input type="text" name="franchisepostcode" id="franchisepostcode" class="req" /></div>
		  						</div>	  						
		  						
		  						
		  					</div>
		  					<div class="span6">
		  						<div class="row-fluid control-group">
		  							<div class="span3"><label class="control-label" for="franchiseenquiry">Enquiry:</div>
		  							<div class="span9 controls"><textarea name="franchiseenquiry" id="franchiseenquiry"></textarea></div>
		  						</div>
		  						<div class="row-fluid control-group">
		  							<div class="span3"></div>
		  							<div class="span9"><a href="javascript:void(0)" class="btn button1" onClick="validate_franchise_form()" >Submit</a></div>
		  						</div>
		  					</div>
		  				</div>
	  				</form>
	  				<script type="text/javascript">
						function validate_franchise_form(){
							$('body').css('cursor','wait');
							var pass = validateForm($('#franchise_form'));
							if(!pass){
								$('body').css('cursor','pointer');
								return false;
							}else{
								$('#franchise_form').submit();
							}
						}
					</script>
	  			</div>
	  		</div>
	  	</div>
	  </div>
	{include file='signup.tpl'} {include file='footer.tpl'}
{/block}
