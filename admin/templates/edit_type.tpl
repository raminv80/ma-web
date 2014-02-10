{block name=body}
<div class="row">
	<div class="col-sm-12">
		<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" action="/admin/includes/processes/processes-record.php" method="post">
			<div class="row">
				<div class="col-sm-12">
					<fieldset>
						<legend> {if $fields.type_id neq ""}Edit{else}New{/if} Type {if $cnt eq ""}{assign var=cnt value=0}{/if} </legend>
					</fieldset>
					<input type="hidden" value="{$fields.type_id}" name="field[1][tbl_type][{$cnt}][type_id]" id="type_id" /> <input type="hidden" value="type_id" name="field[1][tbl_type][{$cnt}][id]" id="id" />
				</div>
			</div>
			<div class="row form-group">
					<label class="col-sm-3 control-label" for="type_name">Name</label>
				<div class="col-sm-9 controls">
					<input class="form-control" type="text" value="{$fields.type_name}" name="field[1][tbl_type][{$cnt}][type_name]" id="type_name" class="req">
				</div>
			</div>
			<div class="row">
				<div class="col-sm-8 offset4">
					<input type="hidden" id="error" name="error" value="0" />
					<script type="text/javascript">
				function validate(){
					$('body').css('cursor','wait');
					var pass = validateForm();
					if(!pass){
						$('body').css('cursor','pointer');
						return false;
					}else{
						$('#Edit_Record').submit();
					}
				}
			</script>
				</div>

				<div class="row form-group">
					<div class="col-sm-offset-3 col-sm-9">
					<a href="javascript:void(0)" class="btn btn-primary" onClick="$('#Edit_Record').submit();">Submit</a>
					</div>
				</div>


				<input type="hidden" name="formToken" id="formToken" value="{$token}" />
			</div>
		</form>
	</div>
</div>
{/block}
