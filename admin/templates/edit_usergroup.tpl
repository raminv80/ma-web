{block name=body}
<div class="row">
	<div class="col-sm-12">
		<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" method="post">
			<div class="row">
				<div class="col-sm-12">
					<fieldset>
						<legend> {if $fields.usergroup_id neq ""}Edit{else}New{/if} {$zone} {if $cnt eq ""}{assign var=cnt value=0}{/if} 
							<a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right" style="margin-left: 38px;">Save</a>
						</legend>
					</fieldset>
					<input type="hidden" value="usergroup_id" name="primary_id" id="primary_id"/> 
					<input type="hidden" value="{$fields.usergroup_id}" name="field[1][tbl_usergroup][{$cnt}][usergroup_id]" id="usergroup_id" /> 
					<input type="hidden" value="usergroup_id" name="field[1][tbl_usergroup][{$cnt}][id]" id="id" /> 
					<input type="hidden" name="formToken" id="formToken" value="{$token}" />
					<input type="hidden" id="error" name="error" value="0" />
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="usergroup_name">Name *</label>
				<div class="col-sm-5">
					<input class="form-control" type="text" value="{$fields.usergroup_name}" name="field[1][tbl_usergroup][{$cnt}][usergroup_name]" id="usergroup_name" required>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-offset-3 col-sm-9">
					<a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right" style="margin-top: 50px;"> Save</a>
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
});
</script>

{/block}
