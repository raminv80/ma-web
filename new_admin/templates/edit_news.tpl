{block name=body}
<form id="Edit_Record" accept-charset="UTF-8" action="/new_admin/includes/processes/processes-record.php" method="post">
<div class="grid_12 right">
	<p>{if $fields.listing_id neq ""}Edit{else}New{/if} News</p>
	{if $cnt eq ""}{assign var=cnt value=0}{/if}
		<input type="hidden" value="listing_id" name="field[ntbl_listing][{$cnt}][id]" id="id" />
		<input type="hidden" value="{$fields.listing_id}" name="field[ntbl_listing][{$cnt}][listing_id]" id="id_article_name">
		<input type="hidden" value="2" name="field[ntbl_listing][{$cnt}][listing_type_id]" id="id_article_name">
				<div class="grid_4 alpha omega left">listing_category_id</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.listing_category_id}" name="field[ntbl_listing][{$cnt}][listing_category_id]" id="id_listing_category_id"></div>
				<div class="grid_4 alpha omega left">listing_name</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.listing_name}" name="field[ntbl_listing][{$cnt}][listing_name]" id="id_listing_name"></div>
				<div class="grid_4 alpha omega left">listing_title</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.listing_title}" name="field[ntbl_listing][{$cnt}][listing_title]" id="id_listing_title"></div>
				<div class="grid_4 alpha omega left">listing_url</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.listing_url}" name="field[ntbl_listing][{$cnt}][listing_url]" id="id_listing_url"></div>
				<div class="grid_4 alpha omega left">listing_seo_title</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.listing_seo_title}" name="field[ntbl_listing][{$cnt}][listing_seo_title]" id="id_listing_seo_title"></div>
				<div class="grid_4 alpha omega left">listing_meta_description</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.listing_meta_description}" name="field[ntbl_listing][{$cnt}][listing_meta_description]" id="id_listing_meta_description"></div>
				<div class="grid_4 alpha omega left">listing_meta_words</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.listing_meta_words}" name="field[ntbl_listing][{$cnt}][listing_meta_words]" id="id_listing_meta_words"></div>
				<div class="grid_4 alpha omega left">listing_short_description</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.listing_short_description}" name="field[ntbl_listing][{$cnt}][listing_short_description]" id="id_listing_short_description"></div>
				<div class="grid_4 alpha omega left">listing_long_description</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.listing_long_description}" name="field[ntbl_listing][{$cnt}][listing_long_description]" id="id_listing_long_description"></div>
				<div class="grid_4 alpha omega left">listing_order</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.listing_order}" name="field[ntbl_listing][{$cnt}][listing_order]" id="id_listing_order"></div>
		
		{if $fields.listing_id neq ""}
		<input type="hidden" value="news_id" name="field[ntbl_news][{$cnt}][id]" id="id"/>
		<input type="hidden" value="{$fields.news_id}" name="field[ntbl_news][{$cnt}][news_id]">
		<input type="hidden" value="{$fields.listing_id}" name="field[ntbl_news][{$cnt}][news_listing_id]">
		<div class="grid_4 alpha omega left">news_start_date</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.news_start_date}" name="field[ntbl_news][{$cnt}][news_start_date]" ></div>
		<div class="grid_4 alpha omega left">news_start_time</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.news_start_time}" name="field[ntbl_news][{$cnt}][news_start_time]" ></div>
		<div class="grid_4 alpha omega left">news_end_date</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.news_end_date}" name="field[ntbl_news][{$cnt}][news_end_date]" ></div>
		<div class="grid_4 alpha omega left">news_end_time</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.news_end_time}" name="field[ntbl_news][{$cnt}][news_end_time]" ></div>
		<div class="grid_4 alpha omega left">news_loc_address</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.news_loc_address}" name="field[ntbl_news][{$cnt}][news_loc_address]" ></div>
		<div class="grid_4 alpha omega left">news_loc_suburb</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.news_loc_suburb}" name="field[ntbl_news][{$cnt}][news_loc_suburb]" ></div>
		<div class="grid_4 alpha omega left">news_loc_postcode</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.news_loc_postcode}" name="field[ntbl_news][{$cnt}][news_loc_postcode]" ></div>
		<div class="grid_4 alpha omega left">news_loc_other</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.news_loc_other}" name="field[ntbl_news][{$cnt}][news_loc_other]" ></div>
		{/if}
		<input type="submit" value="submit"   />
		</div>
		<input type="hidden" name="formToken" id="formToken" value="{$token}" />
		</form>
{/block}