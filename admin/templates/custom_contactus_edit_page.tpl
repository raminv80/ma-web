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
				<a class="btn btn-small btn-success right pull-right" href="/admin/edit/page">
					<i class="icon-plus icon-white"></i>ADD NEW
				</a>
                </legend>
                </fieldset>
				<input type="hidden" value="listing_id" name="field[tbl_listing][{$cnt}][id]" id="id" onSubmit="var pass = validateForm(); return pass;"/>
				<input type="hidden" value="{$fields.listing_id}" name="field[tbl_listing][{$cnt}][listing_id]" id="listing_type_id">
				<input type="hidden" value="1" name="field[tbl_listing][{$cnt}][listing_type_id]" id="listing_type_id">
			</div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_name">Name</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.listing_name}" name="field[tbl_listing][{$cnt}][listing_name]" id="id_listing_name" class="req" onchange="seturlaAndTitle(this.value);"></div>
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
			<div class="span3"><label class="control-label" for="id_listing_parent">Parent</label></div>
			<div class="span9 controls">
				<select name="field[tbl_listing][{$cnt}][listing_category_id]" id="id_listing_parent">
				<option value="0">Select one</option>
						{foreach $fields.options.listing_category_id as $opt}
									<option value="{$opt.id}" {if $fields.listing_category_id eq $opt.id}selected="selected"{/if}>{$opt.value}  </option>
						{/foreach}
				</select>

			</div>
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
			<div class="span3"><label class="control-label" for="id_listing_content1">Content</label><br/><label class="control-label small-txt" ></label></div>
			<div class="span9 controls"><textarea name="field[tbl_listing][{$cnt}][listing_content1]" id="id_listing_content1" class="tinymce">{$fields.listing_content1}</textarea></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_content2">Address and<br>Opening times</label></div>
			<div class="span9 controls"><textarea name="field[tbl_listing][{$cnt}][listing_content2]" id="id_listing_content2" class="tinymce">{$fields.listing_content2}</textarea></div>
		</div>
		<!--  
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="listing_image">Image</label><br/><label class="control-label small-txt" >Size: 600px Wide x 600px Tall</label></div>
			<div class="span9 controls">
			<input type="hidden" value="{$fields.listing_image}" name="field[tbl_listing][{$cnt}][listing_image]" id="listing_image" class="fileinput">
			<span class="file-view" id="listing_image_view"
			{if $fields.listing_image eq ""}style="display:none"{/if} >
			<a href="{$fields.listing_image}" target="_blank" id="listing_image_path">{$fields.listing_image}</a>
			</span>
			<span class="file-view" id="listing_image_none" {if $fields.listing_image neq ""}style="display:none"{/if}>None</span>
			<a href="javascript:void(0);" class="btn btn-info marg-5r"
				onclick="
					getFileType('listing_image','','');
					$('#listing_image_view').css('display','block');
					$('#listing_image_none').css('display','none');
					">Select File</a>
			<a href="javascript:void(0);" class="btn btn-info"
				onclick="
				$('#listing_image').val('');
				$('#listing_image_view').css('display','none');
				$('#listing_image_none').css('display','block');
				">Remove File</a>
			</div>
		</div>-->
		
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="search">Location</label></div>
			<div class="span9">
				<input type="text" id="search">&nbsp;<a href="javascript:void(0);" class="btn btn-info" onclick="searchAddress($('#search').val());$('#search').val('')">Search</a>
				<div id="search-warning"></div>
				<input type="hidden" value="{$fields.listing_content4}" name="field[tbl_listing][{$cnt}][listing_content4]" id="location_latitude">
				<input type="hidden" value="{$fields.listing_content5}" name="field[tbl_listing][{$cnt}][listing_content5]" id="location_longitude">
				<div id="GmlMap" class="GmlMap">Loading Map....</div>
				<script type="text/javascript">
					jQuery(document).ready(function() {
						centerOn({$fields.listing_content4},{$fields.listing_content5});
					});
				</script>
				<script src="http://maps.googleapis.com/maps/api/js?sensor=false" type="text/javascript"></script>
				<script type="text/javascript" src="/admin/includes/google-api/gml-v3.js"></script>
				<link href='/admin/includes/google-api/gml-v3.css' rel='stylesheet' type='text/css'>
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
function seturlaAndTitle(str){
	$('#id_listing_title').val(str);
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