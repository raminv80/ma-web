{block name=body}
<div class="row">
	<div class="col-sm-12">
		<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" method="post">
			<div class="row">
				<div class="col-sm-12">
					<fieldset>
						<legend>
							{if $fields.postcoderegion_id neq ""}Edit{else}New{/if} {$zone} 
							{if $cnt eq ""}{assign var=cnt value=0}{/if} 
							<a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right" style="margin-left: 38px;"><i class="icon-ok icon-white"></i> Save</a>
							
						</legend>
					</fieldset>
					<input type="hidden" value="postcoderegion_id" name="primary_id" id="primary_id"/> 
					<input type="hidden" value="postcoderegion_id" name="field[1][tbl_postcoderegion][{$cnt}][id]" id="id"/> 
					<input type="hidden" value="{$fields.postcoderegion_id}" name="field[1][tbl_postcoderegion][{$cnt}][postcoderegion_id]" id="postcoderegion_id">
					<input type="hidden" value="{if $fields.postcoderegion_created}{$fields.postcoderegion_created}{else}{'Y-m-d H:i:s'|date}{/if}" name="field[1][tbl_postcoderegion][{$cnt}][postcoderegion_created]" id="postcoderegion_created">
					<input type="hidden" name="formToken" id="formToken" value="{$token}" /> 
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 control-label" for="id_postcoderegion_name">Name *</label>
				<div class="col-sm-5">
					<input class="form-control" type="text" value="{$fields.postcoderegion_name}" name="field[1][tbl_postcoderegion][{$cnt}][postcoderegion_name]" id="id_postcoderegion_name" required>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="row form-group">
        <label class="col-sm-3 control-label">Weight (Kg)</label>
        <label class="col-sm-3 control-label">Purchase ($)</label>
        <label class="col-sm-2 control-label">Cost ($)</label>
      </div>
			{assign var='sndcnt' value=0}
			{foreach $fields.postages as $postage}
				<div class="row form-group">
					<input type="hidden" value="postage_id" name="field[2][tbl_postage][{$sndcnt}][id]" /> 
					<input type="hidden" value="{$postage.postage_id}" name="field[2][tbl_postage][{$sndcnt}][postage_id]" /> 
					<input type="hidden" value="postcoderegion_id" name="default[postage_region_id]" /> 
					<input type="hidden" value="{$postage.postage_name}" name="field[2][tbl_postage][{$sndcnt}][postage_name]" /> 
					<label class="col-sm-3 control-label" for="id_postage_price">{$postage.postage_min_weight|number_format:2:'.':','} - {$postage.postage_max_weight|number_format:2:'.':','}</label>
					<label class="col-sm-3 control-label" for="id_postage_price">{$postage.postage_min_purchase|number_format:2:'.':','} - {$postage.postage_max_purchase|number_format:2:'.':','}</label>
					<div class="col-sm-3">
						<input class="form-control double" type="text" value="{$postage.postage_price}" name="field[2][tbl_postage][{$sndcnt}][postage_price]" id="id_postage_price" required>
					</div>
				</div>
				{assign var='sndcnt' value=$sndcnt+1}
			{/foreach}

			{if $fields.postcodes}
				<div class="row form text-center">
					<button class="btn btn-info" type="button" onclick="$('#postcode-list').toggle('slow')">Postcode list</button>
	      </div>
				<div class="row form" id="postcode-list" style="display:none;">
					{foreach $fields.postcodes as $pc}
						{if !$pc.postcode_postcode|in_array:$arrPostcode}
							{append var='arrPostcode' value=$pc.postcode_postcode}
			        <div class="col-xs-3 col-sm-1">{$pc.postcode_postcode}</div>
		        {/if}
					{/foreach}
				</div>
			{else}
				<div class="row form">
					<div class="col-sm-12 text-center">No postcode associated.</div>
				</div>
			{/if}
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

$(document).ready(function(){

	$('#Edit_Record').validate({
		onkeyup: false
	});
	
});
</script>
{/block}
