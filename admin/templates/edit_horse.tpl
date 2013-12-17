{block name=body}
<div class="row-fluid">
	<div class="span12">
		<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" action="/admin/includes/processes/processes-record.php" method="post">
			<div class="row-fluid">
				<div class="span12">
					<fieldset>
						<legend> 
							{if $fields.horse_id neq ""}Edit{else}New{/if} {$zone} 
							{if $cnt eq ""}{assign var=cnt value=0}{/if} 
							{if $fields.horse_id neq ""} 
								<a class="btn btn-small btn-success right pull-right" href="./"> <i class="icon-plus icon-white"></i>ADD NEW</a> 
							{/if} 
						</legend>
					</fieldset>
					<input type="hidden" value="{$fields.horse_id}" name="field[1][tbl_horse][{$cnt}][horse_id]" id="horse_id" /> 
					<input type="hidden" value="horse_id" name="field[1][tbl_horse][{$cnt}][id]" id="id" /> 
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="horse_name">Name</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.horse_name}" name="field[1][tbl_horse][{$cnt}][horse_name]" id="horse_name" class="req">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="horse_colour">Colour</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.horse_colour}" name="field[1][tbl_horse][{$cnt}][horse_colour]" id="horse_colour">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="horse_sex">Sex</label>
				</div>
				<div class="span9 controls">
					<select name="field[1][tbl_horse][{$cnt}][horse_sex]" id="horse_sex">
						<option value="Male" {if $fields.horse_sex eq 'Male'}selected="selected"{/if}>Male</option>
						<option value="Female" {if $fields.horse_sex eq 'Female'}selected="selected"{/if}>Female</option>
						<option value="Neuter" {if $fields.horse_sex eq 'Neuter'}selected="selected"{/if}>Neuter</option>
					</select>
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="horse_age">Age</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.horse_age}" id="horse_age" name="field[1][tbl_horse][{$cnt}][horse_age]" >
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="horse_breeding">Breeding</label>
				</div>
				<div class="span9 controls">
					<textarea name="field[1][tbl_horse][{$cnt}][horse_breeding]" id="horse_breeding">{$fields.horse_breeding}</textarea>
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="horse_breeder">Breeder</label>
				</div>
				<div class="span9 controls">
					<textarea name="field[1][tbl_horse][{$cnt}][horse_breeder]" id="horse_breeder">{$fields.horse_breeder}</textarea>
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="horse_owner">Owner</label>
				</div>
				<div class="span9 controls">
					<textarea name="field[1][tbl_horse][{$cnt}][horse_owner]" id="horse_owner">{$fields.horse_owner}</textarea>
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
								$('#Edit_Record').submit();
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
