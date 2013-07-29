{block name=body}
<div class="row-fluid">
	<div class="span12">
	<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" action="/admin/includes/processes/processes-record.php" method="post">
		<div class="row-fluid">
			<div class="span12">
            	<fieldset>
                <legend>
				{if $fields.listing_id neq ""}Edit{else}New{/if} Product care category
				{if $cnt eq ""}{assign var=cnt value=0}{/if}
                </legend>
                </fieldset>
				<input type="hidden" value="listing_id" name="field[tbl_listing][{$cnt}][id]" id="id" onSubmit="var pass = validateForm(); return pass;"/>
				<input type="hidden" value="{$fields.listing_id}" name="field[tbl_listing][{$cnt}][listing_id]" id="listing_type_id">
				<input type="hidden" value="6" name="field[tbl_listing][{$cnt}][listing_type_id]" id="listing_type_id">
			</div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_name">Name</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.listing_name}" name="field[tbl_listing][{$cnt}][listing_name]" id="id_listing_name" class="req"></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="listing_image">Image</label><br/></div>
			<div class="span9 controls">
			<input type="hidden" value="{$fields.listing_image}" name="field[tbl_listing][{$cnt}][listing_image]" id="listing_image" class="req fileinput">
			<span class="file-view" id="listing_image_view" {if $fields.listing_image eq ""}style="display:none"{/if}><a href="{$fields.listing_image}" target="_blank" id="listing_image_path" >{$fields.listing_image}</a></span><span class="file-view" id="listing_image_none" {if $fields.listing_image neq ""}style="display:none"{/if}>None</span>
			<a href="javascript:void(0);"  class="btn btn-info marg-5r" type="button" onclick="getFileType('listing_image');$('#listing_image_view').css('display','block');$('#listing_image_none').css('display','none');">Select File</a><a href="javascript:void(0);" class="btn btn-info" onclick="$('#listing_image').val('');$('#listing_image_view').css('display','none');$('#listing_image_none').css('display','block');">Remove File</a>
			<br><small>Please use an image of 134px wide by 134px high.</small>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_short_description">Short Description</label></div>
			<div class="span9 controls">
			<input type="text" maxlength="150" value="{$fields.listing_short_description}" name="field[tbl_listing][{$cnt}][listing_short_description]" id="id_listing_short_description" class="tool-tip" data-original-title="maximum 150 characters" >
			</div>
		</div>
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_long_description">Long Description</label></div>
			<div class="span9 controls">
			<textarea name="field[tbl_listing][{$cnt}][listing_long_description]" id="id_listing_long_description" class="tinymce">{$fields.listing_long_description}</textarea></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_order">Order</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.listing_order}" name="field[tbl_listing][{$cnt}][listing_order]" id="id_listing_order"></div>
		</div>
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="slide_special">Active</label></div>
			<div class="span9 controls">
				<input type="checkbox" onclick="validate();"  name="listing_published_check" id="listing_published_check" class="" {if $fields.listing_published eq "1"}checked="checked"{/if}  >
				<input type="hidden" value="0"  name="field[tbl_listing][{$cnt}][listing_published]" id="listing_published" >
			</div>
		</div>
		 <div class="row-fluid control-group">
			<div class="form-actions">
                <button class="btn btn-primary" onClick="$('#Edit_Record').submit();" type="submit">Submit</button>
				<input type="hidden" name="formToken" id="formToken" value="{$token}" />
            </div>
		</div>
	</form>
	<script type="text/javascript">
		function validate(){
			if($('#listing_published_check').attr('checked') == "checked"){
				$('#listing_published').val('1');
			}else{
				$('#listing_published').val('0');
			}
		}
	</script>
	</div>
</div>

{/block}