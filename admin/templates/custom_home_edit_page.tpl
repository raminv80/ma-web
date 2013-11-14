{block name=body}
<div class="row-fluid">
	<div class="span12">
	<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" action="/admin/includes/processes/processes-record.php" method="post">
		<div class="row-fluid">
			<div class="span12">
            	<fieldset>
                <legend>
				{if $fields.listing_id neq ""}Edit{else}New{/if} Page
				{if $cnt eq ""}{assign var=cnt value=0}{/if}
                </legend>
                </fieldset>
				<input type="hidden" value="listing_id" name="field[1][tbl_listing][{$cnt}][id]" id="id" onSubmit="var pass = validateForm(); return pass;"/>
				<input type="hidden" value="{$fields.listing_id}" name="field[1][tbl_listing][{$cnt}][listing_id]" id="listing_type_id">
				<input type="hidden" value="1" name="field[1][tbl_listing][{$cnt}][listing_type_id]" id="listing_type_id">
			</div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_name">Name</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.listing_name}" name="field[1][tbl_listing][{$cnt}][listing_name]" id="id_listing_name" class="req" onchange="seturl(this.value);"></div>
		</div>
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_title">Title</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.listing_title}" name="field[1][tbl_listing][{$cnt}][listing_title]" id="id_listing_title" class="req" ></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_url">URL</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.listing_url}" name="field[1][tbl_listing][{$cnt}][listing_url]" id="id_listing_url" class="req"></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_parent">Parent</label></div>
			<div class="span9 controls">
				<select name="field[1][tbl_listing][{$cnt}][listing_category_id]" id="id_listing_parent">
				<option value="0">Select one</option>
						{foreach $fields.options.listing_category_id as $opt}
									<option value="{$opt.id}" {if $fields.listing_category_id eq $opt.id}selected="selected"{/if}>{$opt.value}  </option>
						{/foreach}
				</select>

			</div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_seo_title">SEO Title</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.listing_seo_title}" name="field[1][tbl_listing][{$cnt}][listing_seo_title]" id="id_listing_seo_title" class="req"></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_meta_description">Meta Description</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.listing_meta_description}" name="field[1][tbl_listing][{$cnt}][listing_meta_description]" id="id_listing_meta_description"></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_meta_words">Meta Words</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.listing_meta_words}" name="field[1][tbl_listing][{$cnt}][listing_meta_words]" id="id_listing_meta_words"></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_content1">View Our Menu</label><br/><label class="control-label small-txt" >Recommended<br/>character: 280</label></div>
			<div class="span9 controls"><textarea name="field[1][tbl_listing][{$cnt}][listing_content1]" id="id_listing_content1" class="tinymce">{$fields.listing_content1}</textarea></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_content2">Locations</label><br/><label class="control-label small-txt" >Recommended<br/>max-character: 280</label></div>
			<div class="span9 controls"><textarea name="field[1][tbl_listing][{$cnt}][listing_content2]" id="id_listing_content2" class="tinymce">{$fields.listing_content2}</textarea></div>
		</div>
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_content3">Gift Ideas</label><br/><label class="control-label small-txt" >Recommended<br/>max-character: 280</label></div>
			<div class="span9 controls"><textarea name="field[1][tbl_listing][{$cnt}][listing_content3]" id="id_listing_content3" class="tinymce">{$fields.listing_content3}</textarea></div>
		</div>
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_content4">Bottom content</label><br/><label class="control-label small-txt" >Recommended<br/>max-character: 320</label></div>
			<div class="span9 controls"><textarea name="field[1][tbl_listing][{$cnt}][listing_content4]" id="id_listing_content4" class="tinymce">{$fields.listing_content4}</textarea></div>
		</div>
		{if $fields.listing_id neq ""}
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="gallery_image_{$count}">Promotion Images</label><br/><label class="control-label small-txt" >Size: 1170px Wide x 210px Tall</label></div>
			<div class="span9 controls" id="gallery">
				{counter start=1 skip=1 assign="count"}
				{foreach $fields.gallery as $item}
				<div class="row-fluid gallery_item" rel="{$count}">
					<div class="span4" id="gallery_{$count}">
						<input type="hidden" value="gallery_id" name="field[1][tbl_gallery][{$count}][id]" id="id" />
						<input type="hidden" value="{$item.gallery_id}" name="field[1][tbl_gallery][{$count}][gallery_id]" >
						<input type="hidden" value="{$item.gallery_file}" name="field[1][tbl_gallery][{$count}][gallery_file]" >
						<input type="hidden" value="{$item.gallery_listing_id}" name="field[1][tbl_gallery][{$count}][gallery_listing_id]" id="gallery_image_{$count}" class="fileinput">
						<input type="text" value="{$item.gallery_link}" name="field[1][tbl_gallery][{$count}][gallery_link]" class="fileinput">
						<span id="gallery_image_{$count}_file">{$item.gallery_file}</span>
					</div>
					<div class="span8">
						<a href="javascript:void(0);" class="btn btn-info marg-5r" onclick="getFileType('gallery_image_{$count}','','')">Update</a><a href="{$item.gallery_link}" target="_blank"  class="btn btn-info marg-5r" id="gallery_image_{$count}_path">View</a><a href="javascript:void(0);" class="btn btn-info marg-5r" onclick="deleteFileType('gallery_{$count}')">Delete</a>
					</div>
				</div>
				{counter}
				{/foreach}
			</div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"></div>
			<div class="span9 controls">
				<div class="row-fluid">
					<div class="span12">
						<a href="javascript:void(0);" class="btn btn-info" onclick="getFileType('','gallery','{$fields.listing_id}')">Add New File</a>
					</div>
				</div>
			</div>
		</div>
		{/if}
		
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