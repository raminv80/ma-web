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
                </legend>
                </fieldset>
				<input type="hidden" value="listing_id" name="field[tbl_listing][{$cnt}][id]" id="id" onSubmit="var pass = validateForm(); return pass;"/>
				<input type="hidden" value="{$fields.listing_id}" name="field[tbl_listing][{$cnt}][listing_id]" id="listing_type_id">
				<input type="hidden" value="4" name="field[tbl_listing][{$cnt}][listing_type_id]" id="listing_type_id">
				<input type="hidden" value="28" name="field[tbl_listing][{$cnt}][listing_category_id]" id="id_listing_parent">
			</div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_name">Name</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.listing_name}" name="field[tbl_listing][{$cnt}][listing_name]" id="id_listing_name" class="req" onchange="seturl(this.value);"></div>
		</div>
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_title">Title</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.listing_title}" name="field[tbl_listing][{$cnt}][listing_title]" id="id_listing_title" class="req" ></div>
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
			<div class="span3"><label class="control-label" for="id_listing_order">Order</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.listing_order}" name="field[tbl_listing][{$cnt}][listing_order]" id="id_listing_order"></div>
		</div>
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_published">Published</label></div>
			<div class="span9 controls">
			<input type="hidden" value="{if $fields.listing_published eq 1}1{else}0{/if}" name="field[tbl_listing][{$cnt}][listing_published]" class="value">
			<input type="checkbox" {if $fields.listing_published eq 1}checked="checked"{/if} onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_listing_published">
			</div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_content1">Snippet</label><br/><label class="control-label small-txt" >Recommended<br/>max-character: 180</label></div>
			<div class="span9 controls"><textarea name="field[tbl_listing][{$cnt}][listing_content1]" id="id_listing_content1" class="tinymce">{$fields.listing_content1}</textarea></div>
		</div>
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_content2">Content</label></div>
			<div class="span9 controls"><textarea name="field[tbl_listing][{$cnt}][listing_content2]" id="id_listing_content2" class="tinymce">{$fields.listing_content2}</textarea></div>
		</div>
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="listing_image">Thumbnail Image</label><br/><label class="control-label small-txt" >Size: 600px Wide x 600px Tall</label></div>
			<div class="span9 controls">
			<input type="hidden" value="{$fields.listing_image}" name="field[tbl_listing][{$cnt}][listing_image]" id="listing_image" class="fileinput">
			<a href="{$fields.listing_image}" target="_blank"  class="btn btn-info marg-5r" id="listing_image__path">{if $fields.listing_image neq ""}View{else}None{/if}</a>
			<a href="javascript:void(0);" class="btn btn-info marg-5r" onclick=" getFileType('listing_image','',''); ">Select File</a>
			<a href="javascript:void(0);" class="btn btn-info"
				onclick="
				$('#listing_image').val('');
				$('#listing_image__path').attr('href','');
				$('#listing_image__path').html('None');
				">Remove File</a>
			</div>
		</div>
		{if $fields.listing_id neq ""}
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="gallery_image_{$count}">Gallery Images</label><br/><label class="control-label small-txt" >Size: 600px Wide x 600px Tall</label><br/><label class="control-label small-txt" >Max 3 images</label></div>
			<div class="span9 controls" id="gallery">
				{counter start=1 skip=1 assign="count"}
				{foreach $fields.gallery as $item}
				<div class="row-fluid gallery_item" rel="{$count}">
					<div class="span4" id="gallery_{$count}">
						<input type="hidden" value="gallery_id" name="field[tbl_gallery][{$count}][id]" id="id" />
						<input type="hidden" value="{$item.gallery_id}" name="field[tbl_gallery][{$count}][gallery_id]" >
						<input type="hidden" value="{$item.gallery_file}" name="field[tbl_gallery][{$count}][gallery_file]" >
						<input type="hidden" value="{$item.gallery_listing_id}" name="field[tbl_gallery][{$count}][gallery_listing_id]" id="gallery_image_{$count}" class="fileinput">
						<input type="text" value="{$item.gallery_link}" name="field[tbl_gallery][{$count}][gallery_link]" class="fileinput">
						<span id="gallery_image_{$count}_file">{$item.gallery_file}</span>
					</div>
					<div class="span8">
						<a href="javascript:void(0);" class="btn btn-info marg-5r" onclick="getFileType('gallery_image_{$count}','','')">Update</a><a href="javascript:void(0);" class="btn btn-info marg-5r" onclick="deleteFileType('gallery_{$count}')">Delete</a>
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