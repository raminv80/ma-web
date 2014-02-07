{block name=body}
	<header>
		<div id="headout" class="headerbg">
				<div id="videobox">
					<div class="container">
						<div class="row-fluid">
							<div class="span7">
					  			{include file='breadcrumbs.tpl'}
					  			<h3 class="toptitle">{$product_name}</h3>
				  			</div>
						</div>
					</div>
				</div>
			</div>
	</header>
	<div class="container">
		<div class="row" style="margin:40px; ">
              <div class="row">
                  <h3>My Order History</h3>
              </div>
              <div class="row">
             	{if orders }
	                  {foreach $orders as $order}
		                  <div class="row" style="margin:10px;">
		                  	<div class="row">
		                  		Order placed: <b> {$order.cart_closed_date|date_format:"%e %B %Y"}</b>
		                  	</div>
		                  	<div class="row">
		                  		Total: <b> ${$order.cart_total}</b>
		                  	</div>
		                  	{foreach $order.items as $item}
			                  	<ul>
			                  		<li>{$item.cartitem_quantity} x {$item.cartitem_product_name} 
			                  		{if $item.attributes} 
				                  		{foreach $item.attributes as $attr}
						                  		<small>/ {$attr.cartitem_attr_attribute_name}: {$attr.cartitem_attr_attr_value_name}</small>
					                  	{/foreach}
				                  	{/if}
				                  	</li>
			                  	</ul>
		                  	{/foreach}
	                  	</div>
	                  {/foreach}
                  {else}
                  		No orders found.
                  {/if}
              </div>
        </div>


        {if $user.social_id}                          
        <div class="row" id="update-pass" style="margin:40px; ">
              <div class="row">
                  <h3>{$user.gname}, you're logged in using Facebook!</h3>
              </div>
              <div class="row">
                  <a title="Log Out"  href="/process/user?logout=true"><img src="/images/logoutFB.png" alt="Log out Facebook"></a>
              </div>
        </div>
        {else}
        <div class="row" id="update-pass" style="margin:40px;">
       		<div class="row">
	        	<h3>Account Settings: <small>{$user.email}</small></h3>
	        </div>
	        {if $error}
			<div class="row" style="margin:20px; color:#ff0000">{$error}</div>
	        {/if}
			<!-- UPDATE NAME SECTION  -->
			<div class="row" id="update-details" style="margin:40px;">
	             <div class="row">
	                 <h4>Update Details</h4>
	             </div>
	                    
				<form class="form-horizontal" id="update-details-form" role="form" accept-charset="UTF-8" action="/process/user" method="post">
					<input type="hidden" value="updateDetails" name="action" id="action" /> 
					<input type="hidden" name="formToken" id="formToken" value="{$token}" />
					<div class="form-group">
					    <label for="gname" class="col-sm-2 control-label">Given Name</label>
					    <div class="col-sm-10">
					      	<input type="text" value="{if $post}{$post.gname}{else}{$user.gname}{/if}" class="form-control" id="gname" name="gname" required>
							<span class="help-block"></span>
						</div>
					</div>
					<div class="form-group">
					    <label for="surname" class="col-sm-2 control-label">Surname</label>
					    <div class="col-sm-10">
					      	<input type="text" value="{if $post}{$post.surname}{else}{$user.surname}{/if}" class="form-control" id="surname" name="surname" required>
							<span class="help-block"></span>
						</div>
					</div>
				 	<div class="form-group">
				    	<div class="col-sm-offset-2 col-sm-10">
				      		<button onclick="$('#update-details-form').submit();" class="btn btn-primary">Update</button>
				    	</div>
				  	</div>
				</form>
			</div>
			
			<!-- UPDATE PASSWORD SECTION  -->
			<div class="row" id="update-pass" style="margin:40px;">
	             <div class="row">
	                 <h4>Change Password</h4>
	             </div>
	                    
				<form class="form-horizontal" id="update-pass-form" role="form" accept-charset="UTF-8" action="/process/user" method="post">
					<input type="hidden" value="updatePassword" name="action" id="action" /> 
					<input type="hidden" name="formToken" id="formToken" value="{$token}" />
					<div class="form-group">
					    <label for="password" class="col-sm-2 control-label">Old Password</label>
					    <div class="col-sm-10">
					    	<input type="password" value="" class="form-control" id="old_password" name="old_password" required>
					    	<span class="help-block"></span>
                        </div>
                    </div>
                        <div class="form-group">
					    <label for="password" class="col-sm-2 control-label">New Password</label>
					    <div class="col-sm-10">
					    	<input type="password" value="" class="form-control" id="password" name="password" required>
					    	<span class="help-block"></span>
				    	</div>
					</div>
					<div class="form-group">
					    <label for="confirm_password" class="col-sm-2 control-label">Re-enter New Password</label>
					    <div class="col-sm-10">
					    	<input type="password" class="form-control req" id="confirm_password" name="confirm_password" required>
					    	<span class="help-block"></span>
						</div>
					</div>
				 	<div class="form-group">
				    	<div class="col-sm-offset-2 col-sm-10">
				      		<button onclick="$('#update-pass-form').submit();" class="btn btn-primary">Update</button>
				    	</div>
				  	</div>
				</form>
			</div>
		</div>
        {/if}	
        
	</div>
	
	<script type="text/javascript">
		if (jQuery.validator) {
		  jQuery.validator.setDefaults({
		    debug: true,
		    errorClass: 'has-error',
		    validClass: 'has-success',
		    ignore: "",
		    highlight: function (element, errorClass, validClass) {
		      $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		      $('#error-text').html('<label class="control-label">Error, please check the red highlighted fields and submit again.</label>');
		    },
		    unhighlight: function (element, errorClass, validClass) {
		      $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
		      $(element).closest('.form-group').find('.help-block').text('');
		    },
		    errorPlacement: function (error, element) {
		      $(element).closest('.form-group').find('.help-block').text(error.text());
		    },
		    submitHandler: function (form) {
		      if ($(form).valid()) {
			      $('#submit-register').hide();
			     /*  $('#processing-btn').show(); */
		          form.submit();
		      }
		    }
		  });
		}

		$(document).ready(function(){

			$('#update-pass-form').validate();
			$('#update-details-form').validate();
			
			$('#confirm_password').rules("add", {
			      required: true,
			      equalTo: '#password',
			      messages: {
			        equalTo: "The passwords you have entered do not match. Please check them."
			      }
			 });

		})
		
		

		
		
	</script>
{/block}
