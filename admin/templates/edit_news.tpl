{block name=body}
<div class="row-fluid">
	<div class="span12">
	<form id="Edit_Record" accept-charset="UTF-8" action="/admin/includes/processes/processes-record.php" method="post">
		<div class="row-fluid">
			<div class="span12">
			{if $fields.listing_id neq ""}Edit{else}New{/if} News
			{if $cnt eq ""}{assign var=cnt value=0}{/if}
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
		
		{if $fields.listing_id neq ""}
		<div class="row-fluid">
			<div class="span4">Start Date</div>
			<div class="span8"><input type="text" value="{$fields.news_start_date}" name="field[tbl_news][{$cnt}][news_start_date]" id="id_news_start_date" ></div>
		</div>
		<div class="row-fluid">
			<div class="span4">Start Time</div>
			<div class="span8"><input type="text" value="{$fields.news_start_time}" name="field[tbl_news][{$cnt}][news_start_time]" id="id_news_start_time" ></div>
		</div>
		<div class="row-fluid">
			<div class="span4">End Date</div>
			<div class="span8"><input type="text" value="{$fields.news_end_date}" name="field[tbl_news][{$cnt}][news_end_date]" id="id_news_end_date" ></div>
		</div>
		<div class="row-fluid">
			<div class="span4">End Time</div>
			<div class="span8"><input type="text" value="{$fields.news_end_time}" name="field[tbl_news][{$cnt}][news_end_time]" id="id_news_end_time" ></div>
		</div>
		<div class="row-fluid">
			<div class="span4">Location Address</div>
			<div class="span8"><input type="text" value="{$fields.news_loc_address}" name="field[tbl_news][{$cnt}][news_loc_address]" id="id_news_loc_address" ></div>
		</div>
		<div class="row-fluid">
			<div class="span4">Location Suburb</div>
			<div class="span8"><input type="text" value="{$fields.news_loc_suburb}" name="field[tbl_news][{$cnt}][news_loc_suburb]" id="id_news_loc_suburb" ></div>
		</div>
		<div class="row-fluid">
			<div class="span4">Location Postcode</div>
			<div class="span8"><input type="text" value="{$fields.news_loc_postcode}" name="field[tbl_news][{$cnt}][news_loc_postcode]" id="id_news_loc_postcode" ></div>
		</div>
		<div class="row-fluid">
			<div class="span4">Location Other</div>
			<div class="span8"><textarea name="field[tbl_news][{$cnt}][news_loc_other]" class="tinymce" id="id_news_loc_other">{$fields.news_loc_other}</textarea></div>
		</div>
		{/if}
		<div class="row-fluid">
			<div class="span4">Location Other</div>
			<div class="span8">
				<input type="submit" value="submit"/>
				<script type="text/javascript">
					$(document).ready(function(){
						$('textarea.tinymce').tinymce({
							// Location of TinyMCE script
							script_url : '/admin/includes/js/tiny_mce/tiny_mce.js',
				
							// General options
							theme : "advanced",
							plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking",
				
							// Theme options
							theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
							theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code",
							theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media",
							theme_advanced_buttons4 : "",
							theme_advanced_toolbar_location : "top",
							theme_advanced_toolbar_align : "left",
							theme_advanced_statusbar_location : "bottom",
							theme_advanced_resizing : true,
							convert_urls : false,
							external_image_list_url : "/uploads/image_list.php",
							content_css : '/includes/css/reset.css,/includes/css/text.css,/includes/css/styles.css,/includes/css/960_16_col.css'
						});
					});
				</script>
				<input type="hidden" name="formToken" id="formToken" value="{$token}" />
			</div>
		</div>
	</form>
	</div>
</div>
{/block}