{block name=body}
<div class="row">
	<div class="col-sm-12">
		<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" action="/admin/includes/processes/processes-record.php" method="post">
			<div class="row">
				<div class="col-sm-12">
					<fieldset>
						<legend> {if $fields.admin_id neq ""}Edit{else}New{/if} Admin {if $cnt eq ""}{assign var=cnt value=0}{/if} </legend>
					</fieldset>
					<input type="hidden" value="{$fields.admin_id}" name="field[1][tbl_admin][{$cnt}][admin_id]" id="admin_id" /> 
					<input type="hidden" value="admin_id" name="field[1][tbl_admin][{$cnt}][id]" id="id" /> 
					<input type="hidden" value="{$fields.admin_username}" name="field[1][tbl_admin][{$cnt}][admin_username]" id="admin_username"> 
					<input type="hidden" value="{$fields.admin_password}" name="field[1][tbl_admin][{$cnt}][admin_password]" id="admin_password">
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="admin_name">Name</label>
				<div class="col-sm-5">
					<input class="form-control" type="text" value="{$fields.admin_name}" name="field[1][tbl_admin][{$cnt}][admin_name]" id="admin_name" required>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="admin_surname">Surname</label>
				<div class="col-sm-5">
					<input class="form-control" type="text" value="{$fields.admin_surname}" name="field[1][tbl_admin][{$cnt}][admin_surname]" id="admin_surname">
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="admin_email">Email</label>
				<div class="col-sm-5">
					<input class="form-control" type="email" value="{$fields.admin_email}" name="field[1][tbl_admin][{$cnt}][admin_email]" id="admin_email" required>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="admin_reemail">Retype Email</label>
				<div class="col-sm-5">
					<input class="form-control" type="text" value="{$fields.admin_email}" id="admin_reemail" required>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="password">Password</label>
				<div class="col-sm-5">
					<input class="form-control" type="password" value="" id="password" required>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="re_password">Retype Password</label>
				<div class="col-sm-5">
					<input class="form-control" type="password" value="" id="re_password" required>
				</div>
			</div>
			<div class="row form-group" style="display: none">
				<label class="col-sm-3 control-label" for="admin_level">Admin Level</label>
				<div class="col-sm-5">
					<select class="form-control" name="field[1][tbl_admin][{$cnt}][admin_level]" id="admin_level">
						<option value="1" {if $fields.admin_level eq 1}selected="selected"{/if}>Admin</option>
						<option value="2" {if $fields.admin_level eq 2}selected="selected"{/if}>Sudo-admin</option>
						<option value="3" {if $fields.admin_level eq 3}selected="selected"{/if}>Member</option>
					</select>
				</div>
			</div>
			<div class="row">
				<input type="hidden" id="error" name="error" value="0" />

				<div class="row form-group">
					<div class="col-sm-offset-3 col-sm-5">
						<a href="javascript:void(0)" class="btn btn-primary" onClick="$('#Edit_Record').submit();">Submit</a>
					</div>
				</div>


				<input type="hidden" name="formToken" id="formToken" value="{$token}" />
			</div>
		</form>
	</div>
</div>

{include file='jquery-validation.tpl'}

<script type="text/javascript">
$(document).ready(function(){

	$('#Edit_Record').validate();
	
	$('#re_password').rules("add", {
	      required: true,
	      equalTo: '#password',
	      messages: {
	        equalTo: "The passwords you have entered do not match. Please check them."
	      }
	 });

	$('#admin_reemail').rules("add", {
	      required: true,
	      equalTo: '#admin_email',
	      messages: {
	        equalTo: "The emails you have entered do not match. Please check them."
	      }
	 });
});
</script>

{/block}
