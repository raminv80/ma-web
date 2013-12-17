{block name=body}
<div class="row-fluid">
	<div class="span12">
		<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" action="/admin/includes/processes/processes-record.php" method="post">
			<div class="row-fluid">
				<div class="span12">
					<fieldset>
						<legend>
							{if $fields.listing_id neq ""}Edit{else}New{/if} {$zone} 
							{if $cnt eq ""}{assign var=cnt value=0}{/if} 
							{if $fields.listing_id neq ""} 
								<a class="btn btn-small btn-success right pull-right" href="./"> <i class="icon-plus icon-white"></i>ADD NEW</a> 
							{/if}
						</legend>
					</fieldset>
					<input type="hidden" value="listing_id" name="field[1][tbl_listing][{$cnt}][id]" id="id" onSubmit="var pass = validateForm(); return pass;" /> 
					<input type="hidden" value="{$fields.listing_id}" name="field[1][tbl_listing][{$cnt}][listing_id]" id="listing_type_id"> 
					<input type="hidden" value="1" name="field[1][tbl_listing][{$cnt}][listing_type_id]" id="listing_type_id">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_parent_flag">Is Parent?</label>
				</div>
				<div class="span9 controls">
					<input type="hidden" value="{if $fields.listing_parent_flag eq 1}1{else}0{/if}" name="field[1][tbl_listing][{$cnt}][listing_parent_flag]" class="value"> <input type="checkbox" {if $fields.listing_parent_flag eq 1}checked="checked"
						{/if} onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_listing_parent_flag">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_display_menu">Display in Menu?</label>
				</div>
				<div class="span9 controls">
					<input type="hidden" value="{if $fields.listing_display_menu eq 1}1{else}0{/if}" name="field[1][tbl_listing][{$cnt}][listing_display_menu]" class="value"> <input type="checkbox" {if $fields.listing_display_menu eq 1}checked="checked"
						{/if} onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_listing_display_menu">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_name">Name</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.listing_name}" name="field[1][tbl_listing][{$cnt}][listing_name]" id="id_listing_name" class="req" onchange="seturl(this.value);">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_title">Title</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.listing_title}" name="field[1][tbl_listing][{$cnt}][listing_title]" id="id_listing_title" class="req">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_url">URL</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.listing_url}" name="field[1][tbl_listing][{$cnt}][listing_url]" id="id_listing_url" class="req">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_parent">Parent</label>
				</div>
				<div class="span9 controls">
					<select name="field[1][tbl_listing][{$cnt}][listing_parent_id]" id="id_listing_parent">
						<option value="0">Select one</option> {foreach $fields.options.listing_parent_id as $opt}
						<option value="{$opt.id}" {if $fields.listing_parent_id eq $opt.id}selected="selected"{/if}>{$opt.value}</option> {/foreach}
					</select>
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_seo_title">SEO Title</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.listing_seo_title}" name="field[1][tbl_listing][{$cnt}][listing_seo_title]" id="id_listing_seo_title" class="req">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_meta_description">Meta Description</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.listing_meta_description}" name="field[1][tbl_listing][{$cnt}][listing_meta_description]" id="id_listing_meta_description">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_meta_words">Meta Words</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.listing_meta_words}" name="field[1][tbl_listing][{$cnt}][listing_meta_words]" id="id_listing_meta_words">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_order">Order</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.listing_order}" name="field[1][tbl_listing][{$cnt}][listing_order]" id="id_listing_order">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_published">Published</label>
				</div>
				<div class="span9 controls">
					<input type="hidden" value="{if $fields.listing_published eq 1}1{else}0{/if}" name="field[1][tbl_listing][{$cnt}][listing_published]" class="value"> <input type="checkbox" {if $fields.listing_published eq 1}checked="checked"
						{/if} onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_listing_published">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="listing_image">Header Image</label><br />
					<label class="control-label small-txt">Size: 1960px Wide x 345px Tall <br>("None" for default image)</label>
				</div>
				<div class="span9 controls">
					<input type="hidden" value="{$fields.listing_image}" name="field[1][tbl_listing][{$cnt}][listing_image]" id="listing_image_link" class="fileinput"> <span class="file-view" id="listing_image_view" {if $fields.listing_image eq ""}style="display: none"{/if} > <a
						href="{$fields.listing_image}" target="_blank" id="listing_image_path">{$fields.listing_image}</a>
					</span> <span class="file-view" id="listing_image_none" {if $fields.listing_image neq ""}style="display: none"{/if}>None</span> <a href="javascript:void(0);" class="btn btn-info marg-5r"
						onclick="
					getFileType('listing_image','','');
					$('#listing_image_view').css('display','block');
					$('#listing_image_none').css('display','none');
					">Select File</a> <a href="javascript:void(0);" class="btn btn-info"
						onclick="
				$('#listing_image').val('');
				$('#listing_image_view').css('display','none');
				$('#listing_image_none').css('display','block');
				">Remove File</a>
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="listing_content3">Top Ad-Banner</label><br />
					<label class="control-label small-txt">Size: 780px Wide x 90px Tall</label>
				</div>
				<div class="span9 controls">
					<input type="hidden" value="{$fields.listing_content3}" name="field[1][tbl_listing][{$cnt}][listing_content3]" id="listing_content3_link" class="fileinput"> <span class="file-view" id="listing_content3_view" {if $fields.listing_content3 eq ""}style="display: none"{/if} > <a
						href="{$fields.listing_content3}" target="_blank" id="listing_content3_path">{$fields.listing_content3}</a>
					</span> <span class="file-view" id="listing_content3_none" {if $fields.listing_content3 neq ""}style="display: none"{/if}>None</span> <a href="javascript:void(0);" class="btn btn-info marg-5r"
						onclick="
					getFileType('listing_content3','','');
					$('#listing_content3_view').css('display','block');
					$('#listing_content3_none').css('display','none');
					">Select File</a> <a href="javascript:void(0);" class="btn btn-info"
						onclick="
				$('#listing_content3').val('');
				$('#listing_content3_view').css('display','none');
				$('#listing_content3_none').css('display','block');
				">Remove File</a>
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_content5">Ad-Banner Link</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.listing_content5}" name="field[1][tbl_listing][{$cnt}][listing_content5]" id="id_listing_content5" style="width: 80%;">
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_content1">Content</label><br />
				</div>
				<div class="span9 controls">
					<textarea name="field[1][tbl_listing][{$cnt}][listing_content1]" id="id_listing_content1" class="tinymce">{$fields.listing_content1}</textarea>
				</div>
			</div>
			<div class="row-fluid control-group">
				<div class="span3">
					<label class="control-label" for="id_listing_content2">YouTube Video Link</label>
				</div>
				<div class="span9 controls">
					<input type="text" value="{$fields.listing_content2}" name="field[1][tbl_listing][{$cnt}][listing_content2]" id="id_listing_content2" style="width: 80%;">
				</div>
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
<script>
function seturl(str){
	$.ajax({
		type: "POST",
	    url: "/admin/includes/processes/urlencode.php",
		cache: false,
		data: "value="+encodeURIComponent(str),
		dataType: "json",
	    success: function(res, textStatus) {
	    	try{
	    		$('#id_listing_url').val(res.url);
	    	}catch(err){ }
	    }
	});
}
</script>
{/block}
