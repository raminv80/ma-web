{block name=body}
<div class="row-fluid">
	<div class="span12">
	<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" action="/admin/includes/processes/processes-record.php" method="post">
		<div class="row-fluid">
			<div class="span12">
            	<fieldset>
                <legend>
                {if $fields.listing_id neq ""}Edit{else}New{/if} Special
                {if $cnt eq ""}{assign var=cnt value=0}{/if}
                </legend>
                </fieldset>
			<input type="hidden" value="listing_id" name="field[tbl_listing][{$cnt}][id]" id="id" />
			<input type="hidden" value="{$fields.listing_id}" name="field[tbl_listing][{$cnt}][listing_id]" id="id_article_name">
			<input type="hidden" value="5" name="field[tbl_listing][{$cnt}][listing_type_id]" id="id_article_name">
			<input type="hidden" value="{$fields.listing_category_id}" name="field[tbl_listing][{$cnt}][listing_category_id]" id="id_listing_category_id">


			<input type="hidden" value="1" name="field[tbl_listing][{$cnt}][listing_published]" id="id" />
			{if $fields.listing_id neq ""}

			<!--
			<input type="hidden" value="product_id" name="field[tbl_news][{$cnt}][id]" id="id"/>
			<input type="hidden" value="{$fields.product_id}" name="field[tbl_news][{$cnt}][product_id]">

			 -->
			{/if}
			</div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_name">Name</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.listing_name}" name="field[tbl_listing][{$cnt}][listing_name]" id="id_listing_name" class="req"></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_short_description">Promotion Description</label></div>
			<div class="span9 controls"><textarea name="field[tbl_listing][{$cnt}][listing_short_description]" id="id_listing_short_description" class="tinymce">{$fields.listing_short_description}</textarea></div>
		</div>
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="listing_image">Image</label></div>
			<div class="span9 controls"><input type="hidden" value="{$fields.listing_image}" name="field[tbl_listing][{$cnt}][listing_image]" id="listing_image" class="req fileinput">
			<span class="file-view" id="listing_image_view" {if $fields.listing_image eq ""}style="display:none"{/if}><a href="{$fields.listing_image}" target="_blank" id="listing_image_path" >{$fields.listing_image}</a></span><span class="file-view" id="listing_image_none" {if $fields.listing_image neq ""}style="display:none"{/if}>None</span>
			<a href="javascript:void(0);"  class="btn btn-info marg-5r" type="button" onclick="getFileType('listing_image');$('#listing_image_view').css('display','block');$('#listing_image_none').css('display','none');">Select File</a><a href="javascript:void(0);" class="btn btn-info" onclick="$('#listing_image').val('');$('#listing_image_view').css('display','none');$('#listing_image_none').css('display','block');">Remove File</a>
			<br><small>Please use an image of 148px wide by 148px high.</small>
			</div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_order">Promotion Order</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.listing_order}" name="field[tbl_listing][{$cnt}][listing_order]" id="id_listing_order"></div>
		</div>
		 <div class="row-fluid control-group">

            <div class="form-actions">
                <button class="btn btn-primary" onClick="$('#Edit_Record').submit();" type="submit">Submit</button>
				<input type="hidden" name="formToken" id="formToken" value="{$token}" />
            </div>

		</div>
	</form>
	</div>
</div>
{/block}