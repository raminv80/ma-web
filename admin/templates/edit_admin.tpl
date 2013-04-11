{block name=body}
<form id="Edit_Record" accept-charset="UTF-8" action="/new_admin/includes/processes/processes-record.php" method="post">
<div class="grid_12 right">
	<p>{if $fields.admin_id neq ""}Edit{else}New{/if} Admin</p>
	{if $cnt eq ""}{assign var=cnt value=0}{/if}
		
		<input type="hidden" value="{$fields.admin_id}" name="field[tbl_admin][{$cnt}][admin_id]" id="admin_id" />
		<input type="hidden" value="admin_id" name="field[tbl_admin][{$cnt}][id]" id="id" />
		<input type="hidden" value="{$fields.admin_username}" name="field[tbl_admin][{$cnt}][admin_username]" id="admin_username">
		<input type="hidden" value="{$fields.admin_password}" name="field[tbl_admin][{$cnt}][admin_password]" id="admin_password">
		<div class="grid_4 alpha omega left">name</div>
				<div class="grid_8 alpha omega right"><input type="text" value="{$fields.admin_name}" name="field[tbl_admin][{$cnt}][admin_name]" id="admin_name" class="req"></div>
		<div class="grid_4 alpha omega left">surname</div>
				<div class="grid_8 alpha omega right"><input type="text" value="{$fields.admin_surname}" name="field[tbl_admin][{$cnt}][admin_surname]" id="admin_surname"></div>
		<div class="grid_4 alpha omega left">email</div>
				<div class="grid_8 alpha omega right"><input type="text" value="{$fields.admin_email}" name="field[tbl_admin][{$cnt}][admin_email]" id="admin_email" class="req email"></div>
		<div class="grid_4 alpha omega left">retype email</div>
				<div class="grid_8 alpha omega right"><input type="text" value="{$fields.admin_email}" id="admin_reemail" class="req email"></div>
		<div class="grid_4 alpha omega left">password</div>
				<div class="grid_8 alpha omega right"><input type="password" value="" id="password"></div>
		<div class="grid_4 alpha omega left">retype password</div>
				<div class="grid_8 alpha omega right"><input type="password" value="" id="re_password"></div>
				
		<div class="grid_4 alpha omega left">admin level</div>
				<div class="grid_8 alpha omega right">
				<select name="field[tbl_admin][{$cnt}][admin_level]" id="admin_level">
					<option value="1" {if $fields.admin_level eq 1}selected="selected"{/if}>Admin</option>
					<option value="2" {if $fields.admin_level eq 2}selected="selected"{/if}>Sudo-admin</option>
					<option value="3" {if $fields.admin_level eq 3}selected="selected"{/if}>Member</option>
				</select>
				</div>
		<script type="text/javascript">
			function validate(){
				var pass = validateForm();
				if(!pass){
					return false;
				}else{
					$('#admin_username').val($('#admin_email').val());
					if($('#password').val() != "" && $('#password').val() !== $('#re_password').val()){
						$('#password').addClass('error');
						$('#admin_re_password').addClass('error');
					}else{
						$('#password').removeClass('error');
						$('#re_password').removeClass('error');
						if($('#password').val()){
							$.ajax({ 
							    type: "POST", url: "/new_admin/createPass.php", cache: false, data: "username="+$('#admin_email').val()+"&password="+$('#password').val(), dataType: "html",
							    success: function(res, textStatus) {
							    	try{
							    		data = json_parse(res);
							    		$('#admin_password').val(data['password']);
							    		$('#Edit_Record').submit();
							    	}catch(err){ }
							    }
							});
						}else{
							$('#Edit_Record').submit();
						}
					}
					return false;
				}
			}
		</script>
		<a href="javascript:void(0);" onClick="validate()">Submit</a>
</div>
<input type="hidden" name="formToken" id="formToken" value="{$token}" />
</form>
{/block}