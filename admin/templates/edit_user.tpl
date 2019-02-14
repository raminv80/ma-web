{block name=body}
<div class="row">
	<div class="col-sm-12">
		<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" method="post">
			<div class="row">
				<div class="col-sm-12">
					<fieldset>
						<legend> {if $fields.user_id neq ""}Edit{else}New{/if} {$zone} {if $cnt eq ""}{assign var=cnt value=0}{/if} 
							<a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right" style="margin-left: 38px;"><span class="glyphicon glyphicon-floppy-saved"></span> Save</a>
						</legend>
					</fieldset>
					<input type="hidden" value="user_id" name="primary_id" id="primary_id"/> 
					<input type="hidden" value="{$fields.user_id}" name="field[1][tbl_user][{$cnt}][user_id]" id="user_id" /> 
					<input type="hidden" value="user_id" name="field[1][tbl_user][{$cnt}][id]" id="id" /> 
					<input type="hidden" value="{$fields.user_username}" name="field[1][tbl_user][{$cnt}][user_username]" id="user_username"> 
					<input type="hidden" value="{$fields.user_password}" name="field[1][tbl_user][{$cnt}][user_password]" id="user_password">
					<input type="hidden" name="formToken" id="formToken" value="{$token}" />
					
					<input type="hidden" id="error" name="error" value="0" />
					
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="user_gname">Name *</label>
				<div class="col-sm-5">
					<input class="form-control" type="text" value="{$fields.user_gname}" name="field[1][tbl_user][{$cnt}][user_gname]" id="user_gname" required>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="user_surname">Surname</label>
				<div class="col-sm-5">
					<input class="form-control" type="text" value="{$fields.user_surname}" name="field[1][tbl_user][{$cnt}][user_surname]" id="user_surname">
					<span class="help-block"></span>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="user_email">Email *</label>
				<div class="col-sm-5">
					<input class="form-control" type="email" value="{$fields.user_email}" name="field[1][tbl_user][{$cnt}][user_email]" id="user_email" onchange="$('#user_username').val(this.value);createPassword();" required>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="password">Password *</label>
				<div class="col-sm-5">
					<input class="form-control" type="text" value="" name="field1" id="password" onchange="createPassword();">
					<span class="help-block"></span>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="user_group">User Group</label>
				<div class="col-sm-5">
					<select class="form-control" name="field[1][tbl_user][{$cnt}][user_group]" id="user_group">
					  <option value="0">Select one</option> {foreach $fields.options.user_group as $opt}
            <option value="{$opt.id}" {if $fields.user_group eq $opt.id}selected="selected"{/if}>{$opt.value}</option> {/foreach}
					</select>
				</div>
			</div>
					
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
var init_pass = "{if $fields.user_password}{$fields.user_password}{/if}";

$(document).ready(function(){

	$('#Edit_Record').validate({
		onkeyup: false
	});
	
	$('#user_email').rules("add", {
		uniqueEmail: { id: "{if $fields.user_id}{$fields.user_id}{else}0{/if}" }
	 });

});

function createPassword() {

 	if ($('#password').val() != '' && $('#user_email').val() != '') {
		$.ajax({
			type: "POST",
		    url: "/admin/includes/processes/createPass.php",
			cache: false,
			data: "username="+$('#user_email').val()+"&password="+$('#password').val(),
			dataType: "json",
		    success: function(res, textStatus) {
		    	try{
		    		$('#user_password').val(res.password);
				}catch(err){ }
		    }
		});
	} else {
		$('#user_password').val(init_pass);
	} 
}


</script>

{/block}
