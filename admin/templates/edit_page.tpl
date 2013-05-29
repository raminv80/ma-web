{block name=body}
<div class="row-fluid">
	<div class="span12">
	<form id="Edit_Record" accept-charset="UTF-8" action="/admin/includes/processes/processes-record.php" method="post">
		<div class="row-fluid">
			<div class="span12">
				{if $fields.listing_id neq ""}Edit{else}New{/if} Page
				{if $cnt eq ""}{assign var=cnt value=0}{/if}
				<input type="hidden" value="listing_id" name="field[tbl_listing][{$cnt}][id]" id="id" onSubmit="var pass = validateForm(); return pass;"/>
				<input type="hidden" value="{$fields.listing_id}" name="field[tbl_listing][{$cnt}][listing_id]" id="listing_type_id">
				<input type="hidden" value="1" name="field[tbl_listing][{$cnt}][listing_type_id]" id="listing_type_id">
			</div>
		</div>
		<div class="row-fluid">
			<div class="span4">Name</div>
			<div class="span8"><input type="text" value="{$fields.listing_name}" name="field[tbl_listing][{$cnt}][listing_name]" id="id_listing_name" class="req"></div>
		</div>
		<div class="row-fluid">
			<div class="span4">Title</div>
			<div class="span8"><input type="text" value="{$fields.listing_title}" name="field[tbl_listing][{$cnt}][listing_title]" id="id_listing_title" class="req"></div>
		</div>
		<div class="row-fluid">
			<div class="span4">URL</div>
			<div class="span8"><input type="text" value="{$fields.listing_url}" name="field[tbl_listing][{$cnt}][listing_url]" id="id_listing_url" class="req"></div>
		</div>
		<div class="row-fluid">
			<div class="span4">SEO Title</div>
			<div class="span8"><input type="text" value="{$fields.listing_seo_title}" name="field[tbl_listing][{$cnt}][listing_seo_title]" id="id_listing_seo_title" class="req"></div>
		</div>
		<div class="row-fluid">
			<div class="span4">Meta Description</div>
			<div class="span8"><input type="text" value="{$fields.listing_meta_description}" name="field[tbl_listing][{$cnt}][listing_meta_description]" id="id_listing_meta_description"></div>
		</div>
		<div class="row-fluid">
			<div class="span4">Meta Words</div>
			<div class="span8"><input type="text" value="{$fields.listing_meta_words}" name="field[tbl_listing][{$cnt}][listing_meta_words]" id="id_listing_meta_words"></div>
		</div>
		<div class="row-fluid">
			<div class="span4">Short Description</div>
			<div class="span8"><textarea name="field[tbl_listing][{$cnt}][listing_short_description]" id="id_listing_short_description" class="tinymce">{$fields.listing_short_description}</textarea></div>
		</div>
		<div class="row-fluid">
			<div class="span4">Long Description</div>
			<div class="span8"><textarea name="field[tbl_listing][{$cnt}][listing_long_description]" id="id_listing_long_description" class="tinymce">{$fields.listing_long_description}</textarea></div>
		</div>
		<div class="row-fluid">
			<div class="span4">Listing Order</div>
			<div class="span8"><input type="text" value="{$fields.listing_order}" name="field[tbl_listing][{$cnt}][listing_order]" id="id_listing_order"></div>
		</div>
		<div class="row-fluid">
			<div class="span4"></div>
			<div class="span8">
				<input type="submit" value="submit"/>
				<input type="hidden" name="formToken" id="formToken" value="{$token}" />
			</div>
		</div>
	</form>
	</div>
</div>
{/block}