{block name=body}
	<header>
		{include file='mobilemenu.tpl'}
		<div id="headout">
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
	  				<form id="franchise_form" accept-charset="UTF-8" action="/includes/processes/processes-contactus.php" method="post">
		  				<div class="row-fluid">
		  					<div class="span6">
		  						<div class="row-fluid">
		  							<div class="span3">Name*:</div>
		  							<div class="span9"><input type="text" name="franchisename" class="req" /></div>
		  						</div>
		  						<div class="row-fluid">
		  							<div class="span3">Phone*:</div>
		  							<div class="span9"><input type="text" name="franchisephone" class="req" /></div>
		  						</div>
		  						<div class="row-fluid">
		  							<div class="span3">Email*:</div>
		  							<div class="span9"><input type="text" name="franchiseemail" class="req" /></div>
		  						</div>
		  						<div class="row-fluid">
		  							<div class="span3">Postcode*:</div>
		  							<div class="span9"><input type="text" name="franchisepostcode" class="req" /></div>
		  						</div>	  						
		  						
		  						
		  					</div>
		  					<div class="span6">
		  						<div class="row-fluid">
		  							<div class="span3">Enquiry:</div>
		  							<div class="span9"><textarea name="franchiseenquiry"></textarea></div>
		  						</div>
		  						<div class="row-fluid">
		  							<div class="span3"></div>
		  							<div class="span9"><a href="javascript:void(0)" class="btn button1" onClick="validate()" >Submit</a></div>
		  						</div>
		  					</div>
		  				</div>
	  				</form>
	  				<script type="text/javascript">
						function validate(){
							$('body').css('cursor','wait');
							var pass = validateForm();
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
