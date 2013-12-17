{block name=body}
<div class="row-fluid">
	<div class="span12">
		<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" action="/admin/includes/processes/processes-record.php" method="post">
			<div class="row-fluid">
				<div class="span12">
					<fieldset>
						<legend> {if $fields.admin_id neq ""}Edit{else}New{/if} Admin {if $cnt eq ""}{assign var=cnt value=0}{/if} </legend>
					</fieldset>
					<input type="hidden" value="{$fields.admin_id}" name="field[1][tbl_admin][{$cnt}][admin_id]" id="admin_id" /> <input type="hidden" value="admin_id" name="field[1][tbl_admin][{$cnt}][id]" id="id" /> <input type="hidden" value="{$fields.admin_username}" name="field[1][tbl_admin][{$cnt}][admin_username]"
						id="admin_username"> <input type="hidden" value="{$fields.admin_password}" name="field[1][tbl_admin][{$cnt}][admin_password]" id="admin_password">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="admin_name">Name</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.admin_name}" name="field[1][tbl_admin][{$cnt}][admin_name]" id="admin_name" class="req">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="admin_surname">Surname</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.admin_surname}" name="field[1][tbl_admin][{$cnt}][admin_surname]" id="admin_surname">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="admin_email">Email</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.admin_email}" name="field[1][tbl_admin][{$cnt}][admin_email]" id="admin_email" class="req email">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="admin_reemail">Retype Email</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.admin_email}" id="admin_reemail" class="req email">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="password">Password</label>
				</div>
				<div class="span9 controls">
					<input type="password" value="" id="password">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="re_password">Retype Password</label>
				</div>

				<div class="span9 controls">
					<input type="password" value="" id="re_password">
				</div>
			</div>
			<div class="row-fluid control-group" style="display: none">
				<div class="span3">
					<label class="control-label" for="admin_level">Admin Level</label>
				</div>
				<div class="span9 controls">
					<select name="field[1][tbl_admin][{$cnt}][admin_level]" id="admin_level">
						<option value="1" {if $fields.admin_level eq 1}selected="selected"{/if}>Admin</option>
						<option value="2" {if $fields.admin_level eq 2}selected="selected"{/if}>Sudo-admin</option>
						<option value="3" {if $fields.admin_level eq 3}selected="selected"{/if}>Member</option>
					</select>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span8 offset4">
					<input type="hidden" id="error" name="error" value="0" />
					<script type="text/javascript">
				function validate(){
					$('body').css('cursor','wait');
					var pass = validateForm();
					if(!pass){
						$('body').css('cursor','pointer');
						return false;
					}else{
						if($('#admin_email').val() !== $('#admin_reemail').val()){
							$('#admin_email').closest('.row-fluid').addClass('error');
							$('#admin_reemail').closest('.row-fluid').addClass('error');
						}else{
							$('#admin_email').closest('.row-fluid').removeClass('error');
							$('#admin_reemail').closest('.row-fluid').removeClass('error');
							if($('#password').val() != "" && $('#password').val() !== $('#re_password').val()){
								$('#password').closest('.row-fluid').addClass('error');
								$('#re_password').closest('.row-fluid').addClass('error');
							}else{
								$('#admin_username').val($('#admin_email').val());
								$('#password').closest('.row-fluid').removeClass('error');
								$('#re_password').closest('.row-fluid').removeClass('error');
							{if $fields.admin_id eq ""}
								$.ajax({
									type: "POST",
								    url: "/admin/includes/processes/checkEmail.php",
									cache: false,
									data: "username="+$('#admin_email').val(),
									dataType: "json",
								    success: function(res, textStatus) {
									    if(res.email == "true"){
								    			alert('Email needs to be unique, other user is already using that email address');
								    			$('#error').val('1');
								    			return false;
								    		}else{
								    			$('#error').val('0');
								    		}
									    }
								});
							{/if}
								if($('#password').val() != "" && ($('#error').val() == "0" || $('#error').val() == "")){

									$.ajax({
										type: "POST",
									    url: "/admin/includes/processes/createPass.php",
										cache: false,
										data: "username="+$('#admin_email').val()+"&password="+$('#password').val(),
										dataType: "json",
									    success: function(res, textStatus) {
									    	try{
									    		$('#admin_password').val(res.password);
									    		console.log('1');
												$('#Edit_Record').submit();

											}catch(err){ }
									    }
									});
								}
							}
						}
						$('body').css('cursor','pointer');
						return false;
					}
				}
			</script>
				</div>

				<div class="form-actions">
					<a href="javascript:void(0)" class="btn btn-primary" onClick="validate()">Submit</a>
				</div>


				<input type="hidden" name="formToken" id="formToken" value="{$token}" />
			</div>
		</form>
	</div>
</div>
{/block}
