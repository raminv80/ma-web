{block name=body}
<form id="Edit_Record" accept-charset="UTF-8" action="/new_admin/includes/processes/processes-record.php" method="post">
<div class="grid_12 right">
	<p>{if $fields.listing_id neq ""}Edit{else}New{/if} News</p>
	{if $cnt eq ""}{assign var=cnt value=0}{/if}
		<input type="hidden" value="listing_id" name="field[ntbl_listing][{$cnt}][id]" id="id" />
		<input type="hidden" value="{$fields.listing_id}" name="field[ntbl_listing][{$cnt}][listing_id]" id="id_article_name">
		<input type="hidden" value="2" name="field[ntbl_listing][{$cnt}][listing_type_id]" id="id_article_name">
		<input type="hidden" value="{$fields.listing_category_id}" name="field[ntbl_listing][{$cnt}][listing_category_id]" id="id_listing_category_id">
				<div class="grid_4 alpha omega left">category_id</div>
		<div class="grid_8 alpha omega right"></div>
				<div class="grid_4 alpha omega left">name</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.listing_name}" name="field[ntbl_listing][{$cnt}][listing_name]" id="id_listing_name"></div>
				<div class="grid_4 alpha omega left">title</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.listing_title}" name="field[ntbl_listing][{$cnt}][listing_title]" id="id_listing_title"></div>
				<div class="grid_4 alpha omega left">url</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.listing_url}" name="field[ntbl_listing][{$cnt}][listing_url]" id="id_listing_url"></div>
				<div class="grid_4 alpha omega left">seo_title</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.listing_seo_title}" name="field[ntbl_listing][{$cnt}][listing_seo_title]" id="id_listing_seo_title"></div>
				<div class="grid_4 alpha omega left">meta description</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.listing_meta_description}" name="field[ntbl_listing][{$cnt}][listing_meta_description]" id="id_listing_meta_description"></div>
				<div class="grid_4 alpha omega left">meta_words</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.listing_meta_words}" name="field[ntbl_listing][{$cnt}][listing_meta_words]" id="id_listing_meta_words"></div>
				<div class="grid_4 alpha omega left">short_description</div>
		<div class="grid_8 alpha omega right"><textarea name="field[ntbl_listing][{$cnt}][listing_short_description]" id="id_listing_short_description" class="tinymce">{$fields.listing_short_description}</textarea></div>
				<div class="grid_4 alpha omega left">long_description</div>
		<div class="grid_8 alpha omega right"><textarea name="field[ntbl_listing][{$cnt}][listing_long_description]" id="id_listing_long_description" class="tinymce">{$fields.listing_long_description}</textarea></div>
				<div class="grid_4 alpha omega left">order</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.listing_order}" name="field[ntbl_listing][{$cnt}][listing_order]" id="id_listing_order"></div>
		
		{if $fields.listing_id neq ""}
		<input type="hidden" value="news_id" name="field[ntbl_news][{$cnt}][id]" id="id"/>
		<input type="hidden" value="{$fields.news_id}" name="field[ntbl_news][{$cnt}][news_id]">
		<input type="hidden" value="{$fields.listing_id}" name="field[ntbl_news][{$cnt}][news_listing_id]">
		<div class="grid_4 alpha omega left">start date</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.news_start_date}" name="field[ntbl_news][{$cnt}][news_start_date]" ></div>
		<div class="grid_4 alpha omega left">start time</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.news_start_time}" name="field[ntbl_news][{$cnt}][news_start_time]" ></div>
		<div class="grid_4 alpha omega left">end date</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.news_end_date}" name="field[ntbl_news][{$cnt}][news_end_date]" ></div>
		<div class="grid_4 alpha omega left">end time</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.news_end_time}" name="field[ntbl_news][{$cnt}][news_end_time]" ></div>
		<div class="grid_4 alpha omega left">location address</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.news_loc_address}" name="field[ntbl_news][{$cnt}][news_loc_address]" ></div>
		<div class="grid_4 alpha omega left">location suburb</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.news_loc_suburb}" name="field[ntbl_news][{$cnt}][news_loc_suburb]" ></div>
		<div class="grid_4 alpha omega left">location postcode</div>
		<div class="grid_8 alpha omega right"><input type="text" value="{$fields.news_loc_postcode}" name="field[ntbl_news][{$cnt}][news_loc_postcode]" ></div>
		<div class="grid_4 alpha omega left">location other</div>
		<div class="grid_8 alpha omega right"><textarea name="field[ntbl_news][{$cnt}][news_loc_other]" class="tinymce">{$fields.news_loc_other}</textarea></div>
		{/if}
		<input type="submit" value="submit"   />
		</div>
		
		<script type="text/javascript">
			$(document).ready(function(){
				$('textarea.tinymce').tinymce({
					// Location of TinyMCE script
					script_url : '/new_admin/includes/js/tiny_mce/tiny_mce.js',
		
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
		</form>
{/block}