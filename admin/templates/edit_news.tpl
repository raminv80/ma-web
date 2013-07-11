{block name=body}
<div class="row-fluid">
	<div class="span12">
	<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" action="/admin/includes/processes/processes-record.php" method="post">
		<div class="row-fluid">
			<div class="span12">
            	<fieldset>
                <legend>
                    {if $fields.listing_id neq ""}Edit{else}New{/if} News
                    {if $cnt eq ""}{assign var=cnt value=0}{/if}
                </legend>
                </fieldset>
			<input type="hidden" value="listing_id" name="field[tbl_listing][{$cnt}][id]" id="id" />
			<input type="hidden" value="{$fields.listing_id}" name="field[tbl_listing][{$cnt}][listing_id]" id="id_article_name">
			<input type="hidden" value="2" name="field[tbl_listing][{$cnt}][listing_type_id]" id="id_article_name">
			<input type="hidden" value="{$fields.listing_category_id}" name="field[tbl_listing][{$cnt}][listing_category_id]" id="id_listing_category_id">
			{if $fields.listing_id neq ""}
			<input type="hidden" value="news_id" name="field[tbl_news][{$cnt}][id]" id="id"/>
			<input type="hidden" value="{$fields.news_id}" name="field[tbl_news][{$cnt}][news_id]">
			<input type="hidden" value="{$fields.listing_id}" name="field[tbl_news][{$cnt}][news_listing_id]">
			{/if}
			</div>
		</div>
        <legend></legend>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_name">Name</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.listing_name}" name="field[tbl_listing][{$cnt}][listing_name]" id="id_listing_name" class="req"></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_title">Title</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.listing_title}" name="field[tbl_listing][{$cnt}][listing_title]" id="id_listing_title" class="req"></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_url">URL</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.listing_url}" name="field[tbl_listing][{$cnt}][listing_url]" id="id_listing_url" class="req"></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_seo_title">SEO Title</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.listing_seo_title}" name="field[tbl_listing][{$cnt}][listing_seo_title]" id="id_listing_seo_title" class="req"></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_meta_description">Meta Description</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.listing_meta_description}" name="field[tbl_listing][{$cnt}][listing_meta_description]" id="id_listing_meta_description"></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_meta_words">Meta Words</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.listing_meta_words}" name="field[tbl_listing][{$cnt}][listing_meta_words]" id="id_listing_meta_words"></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_short_description">Short Description</label></div>
			<div class="span9 controls"><textarea name="field[tbl_listing][{$cnt}][listing_short_description]" id="id_listing_short_description" class="tinymce">{$fields.listing_short_description}</textarea></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_long_description">Long Description</label></div>
			<div class="span9 controls"><textarea name="field[tbl_listing][{$cnt}][listing_long_description]" id="id_listing_long_description" class="tinymce">{$fields.listing_long_description}</textarea></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_order">Listing Order</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.listing_order}" name="field[tbl_listing][{$cnt}][listing_order]" id="id_listing_order"></div>
		</div>
		
		{if $fields.listing_id neq ""}
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_news_start_date">Start Date</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.news_start_date}" name="field[tbl_news][{$cnt}][news_start_date]" id="id_news_start_date" ></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_news_start_time">Start Time</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.news_start_time}" name="field[tbl_news][{$cnt}][news_start_time]" id="id_news_start_time" ></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_news_end_date">End Date</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.news_end_date}" name="field[tbl_news][{$cnt}][news_end_date]" id="id_news_end_date" ></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_news_end_time">End Time</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.news_end_time}" name="field[tbl_news][{$cnt}][news_end_time]" id="id_news_end_time" ></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_news_loc_address">Location Address</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.news_loc_address}" name="field[tbl_news][{$cnt}][news_loc_address]" id="id_news_loc_address" ></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_news_loc_suburb">Location Suburb</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.news_loc_suburb}" name="field[tbl_news][{$cnt}][news_loc_suburb]" id="id_news_loc_suburb" ></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_news_loc_postcode">Location Postcode</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.news_loc_postcode}" name="field[tbl_news][{$cnt}][news_loc_postcode]" id="id_news_loc_postcode" ></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_news_loc_other">Location Other</label></div>
			<div class="span9 controls"><textarea name="field[tbl_news][{$cnt}][news_loc_other]" class="tinymce" id="id_news_loc_other">{$fields.news_loc_other}</textarea></div>
		</div>
		{/if}
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="formToken">Location Other</label></div>
			<div class="span9 controls">
				<input type="submit" value="submit"/>
				<input type="hidden" name="formToken" id="formToken" value="{$token}" />
			</div>
		</div>
        
        
            <div class="form-actions">
                <button class="btn btn-primary" onClick="validate()" type="submit">Submit</button>
            </div>
        
	</form>
	</div>
</div>
{/block}