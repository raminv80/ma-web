{block name=body}
<div class="row">
	<div class="col-sm-12">
		<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" method="post">
			<div class="row">
				<div class="col-sm-12 edit-page-header">
					<span class="edit-page-title">{if $fields.admin_id neq ""}Edit{else}New{/if} Admin {if $cnt eq ""}{assign var=cnt value=0}{/if}</span> 
					<a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right" style="margin-left: 38px;"><span class="glyphicon glyphicon-floppy-saved"></span> Save</a>
					<input type="hidden" value="admin_id" name="primary_id" id="primary_id"/> 
					<input type="hidden" value="{$fields.admin_id}" name="field[1][tbl_admin][{$cnt}][admin_id]" id="admin_id" /> 
					<input type="hidden" value="admin_id" name="field[1][tbl_admin][{$cnt}][id]" id="id" /> 
					<input type="hidden" value="{$fields.admin_username}" name="field[1][tbl_admin][{$cnt}][admin_username]" id="admin_username"> 
					<input type="hidden" value="{$fields.admin_password}" name="field[1][tbl_admin][{$cnt}][admin_password]" id="admin_password">
					<input type="hidden" name="formToken" id="formToken" value="{$token}" />
					
					<input type="hidden" id="error" name="error" value="0" />
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="admin_name">Name *</label>
				<div class="col-sm-5">
					<input class="form-control" type="text" value="{$fields.admin_name}" name="field[1][tbl_admin][{$cnt}][admin_name]" id="admin_name" required>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="admin_surname">Surname</label>
				<div class="col-sm-5">
					<input class="form-control" type="text" value="{$fields.admin_surname}" name="field[1][tbl_admin][{$cnt}][admin_surname]" id="admin_surname">
					<span class="help-block"></span>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="admin_email">Email *</label>
				<div class="col-sm-5">
					<input class="form-control" type="email" value="{$fields.admin_email}" name="field[1][tbl_admin][{$cnt}][admin_email]" id="admin_email" onchange="$('#admin_username').val(this.value);createPassword();" required>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="admin_reemail">Retype Email *</label>
				<div class="col-sm-5">
					<input class="form-control" type="text" value="{$fields.admin_email}" id="admin_reemail" required>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="password">Password *</label>
				<div class="col-sm-5">
					<input class="form-control" type="password" value="" name="field1" id="password" onchange="createPassword();">
					<span class="help-block"></span>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="re_password">Retype Password *</label>
				<div class="col-sm-5">
					<input class="form-control" type="password" value="" name="field2" id="re_password" >
					<span class="help-block"></span>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="admin_level">Admin Level</label>
				<div class="col-sm-5">
					<select class="form-control" name="field[1][tbl_admin][{$cnt}][admin_level]" id="admin_level">
						<option value="1" {if $fields.admin_level eq 1}selected="selected"{/if}>Admin</option>
<!-- 						<option value="2" {if $fields.admin_level eq 2}selected="selected"{/if}>Store</option> -->
					</select>
				</div>
			</div>
<!-- 			<div class="row form-group">
				<label class="col-sm-3 control-label" for="store_id">Store</label>
				<div class="col-sm-5">
					<input type="hidden" value="admin_id" name="default[access_admin_id]" />
					<input type="hidden" value="access_id" name="field[15][tbl_access][{$cnt}][id]" id="id" />
					<input type="hidden" value="{$fields.accesses.0.access_admin_id}" name="field[15][tbl_access][{$cnt}][access_admin_id]" id="access_admin_id">
					<input type="hidden" value="{$fields.accesses.0.access_id}" name="field[15][tbl_access][{$cnt}][access_id]" >
					<select class="form-control" name="field[15][tbl_access][{$cnt}][access_store_id]" id="store_id">
						<option value="0">Select one</option> {foreach $fields.options.store_id as $opt}
						<option value="{$opt.id}" {if $fields.accesses.0.access_store_id eq $opt.id}selected="selected"{/if}>{$opt.value}</option> {/foreach}
					</select>
				</div>
			</div> -->
					
			<div class="row form-group">
				<div class="col-sm-offset-3 col-sm-9">
					<a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right" style="margin-top: 50px;"><span class="glyphicon glyphicon-floppy-saved"></span> Save</a>
				</div>
			</div>
		</form>
	</div>
</div>

{include file='jquery-validation.tpl'}

<script type="text/javascript">
var init_pass = "{if $fields.admin_password}{$fields.admin_password}{/if}";

$(document).ready(function(){

	$('#Edit_Record').validate({
		onkeyup: false
	});
	
	$('#re_password').rules("add", {
	      equalTo: '#password',
	      messages: {
	        equalTo: "The passwords you have entered do not match. Please check them."
	      }
	 });
	 
	$('#admin_email').rules("add", {
		uniqueEmail: { id: "{if $fields.admin_id}{$fields.admin_id}{else}0{/if}", table:"admin" }
	 });
	 
	$('#admin_reemail').rules("add", {
	      equalTo: '#admin_email',
	      messages: {
	        equalTo: "The emails you have entered do not match. Please check them."
	      }
	 });
});

function createPassword() {

 	if ($('#password').val() != '' && $('#admin_email').val() != '') {
		$.ajax({
			type: "POST",
		    url: "/admin/includes/processes/createPass.php",
			cache: false,
			data: "username="+$('#admin_email').val()+"&password="+$('#password').val(),
			dataType: "json",
		    success: function(res, textStatus) {
		    	try{
		    		$('#admin_password').val(res.password);
				}catch(err){ }
		    }
		});
	} else {
		$('#admin_password').val(init_pass);
	} 
}


</script>

{/block}
