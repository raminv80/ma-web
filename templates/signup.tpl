{block name=signup}
	<div id="vipsignup">
	  	<div class="container">
	  		<div class="row-fluid">
	  			<div class="span5">
	  				<img src="/images/vip.png" alt="Two icecream waffle cones" />
	  			</div>
	  			<div class="span7 signupform">
	  				<form id="vip_form" accept-charset="UTF-8" action="/includes/processes/processes-vip.php" method="post">
		  				<div class="row-fluid">
	  						<h5>Sign up to become a VIP customer at Cocolat</h5>
	  						<div class="newsl"><a title="View previous newsletters" href="#">View previous newsletters</a></div>
		  				</div>
		  				<div class="row-fluid">
		  					<div class="span6">
		  						<div class="row-fluid control-group">
			  						<div class="span4"><label class="control-label" for="name1">Name*:</label></div>
			  						<div class="span8 controls"><input type="text" id="name1" name="name" class="req"/></div>
		  						</div>
		  					</div>
		  					<div class="span6">
			  					<div class="row-fluid control-group">
			  						<div class="span4"><label class="control-label" for="email1">Email*:</label></div>
			  						<div class="span8 controls"><input type="text" id="email1" name="email" class="req"/></div>	  
		  						</div>								
		  					</div>
		  				</div>
		  				<div class="row-fluid">
		  					<div class="span6">
			  					<div class="row-fluid control-group">
			  						<div class="span4"><label class="control-label" for="age1">Age group:</label></div>
			  						<div class="span8 controls">
				  						<select id="age1" name="age">
				  							<option>Please select</option>
				  							<option value="Under 18">Under 18</option>
				  							<option value="18to25">18-25</option>
				  							<option value="26to40">26-40</option>
				  							<option value="Over 40">Over 40</option>	  													
				  						</select>
			  						</div>
		  						</div>
		  					</div>
		  					<div class="span6">
			  					<div class="row-fluid control-group">
			  						<div class="span4"><label class="control-label" for="postcode1">Postcode:</label></div>
			  						<div class="span8 controls"><input type="text" id="postcode1" name="postcode" /></div>	  								
			  					</div>
		  					</div>
		  				</div>
		  				<div class="row-fluid control-group">
		  					<div class="span6">
			  					<div class="span4"></div>
			  					<div class="span8"><a href="javascript:void(0)" class="btn button1" onClick="validate_vip_form()" >Submit</a></div>
		  					</div>
		  				</div>
	  				</form>
	  				<script type="text/javascript">
						function validate_vip_form(){
							$('body').css('cursor','wait');
							var pass = validateForm($('#vip_form'));
							if(!pass){
								$('body').css('cursor','pointer');
								return false;
							}else{
								$('#vip_form').submit();
							}
						}
					</script>
	  			</div>
	  		</div>
	  	</div>
	  </div>
{/block}