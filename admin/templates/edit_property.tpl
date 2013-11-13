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
				<a class="btn btn-small btn-success right pull-right" href="./">
					<i class="icon-plus icon-white"></i>ADD NEW
				</a>
				{/if}
                </legend>
                </fieldset>
				<input type="hidden" value="listing_id" name="field[tbl_listing][{$cnt}][id]" id="id" onSubmit="var pass = validateForm(); return pass;"/>
				<input type="hidden" value="{$fields.listing_id}" name="field[tbl_listing][{$cnt}][listing_id]" id="listing_type_id">
				<input type="hidden" value="2" name="field[tbl_listing][{$cnt}][listing_type_id]" id="listing_type_id">
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
			<div class="span3"><label class="control-label" for="id_listing_parent">Product Category</label></div>
			<div class="span9 controls">
				<select name="field[tbl_listing][{$cnt}][listing_category_id]" id="id_listing_parent">
				<!-- <option value="0">Select one</option> -->
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
			<div class="span3"><label class="control-label" for="id_listing_published">Published</label></div>
			<div class="span9 controls">
			<input type="hidden" value="{if $fields.listing_published eq 1}1{else}0{/if}" name="field[tbl_listing][{$cnt}][listing_published]" class="value">
			<input type="checkbox" {if $fields.listing_published eq 1}checked="checked"{/if} onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_listing_published">
			</div>
		</div>
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_content1">Short Description</label><br><label class="control-label small-txt" > Max: 500 characters</label></div>
			<div class="span9 controls"><textarea name="field[tbl_listing][{$cnt}][listing_content1]" id="id_listing_content1" class="tinymce">{$fields.listing_content1}</textarea></div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_listing_content2">Full Description</label></div>
			<div class="span9 controls"><textarea name="field[tbl_listing][{$cnt}][listing_content2]" id="id_listing_content2" class="tinymce">{$fields.listing_content2}</textarea></div>
		</div>
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="listing_image">Main Image</label><br/><label class="control-label small-txt" >Size: 640px Wide x 480px Tall</label></div>
			<div class="span9 controls">
			<input type="hidden" value="{$fields.listing_image}" name="field[tbl_listing][{$cnt}][listing_image]" id="listing_image_link" class="fileinput">
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
		</div>
		<!--  gallery -->
		
		{if $fields.listing_id neq ""}
		<div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="gallery_image_{$count}">Gallery Images</label><br/><label class="control-label small-txt" >Size: 640px Wide x 480px Tall</label></div>
			<div class="span9 controls" id="gallery">
				{counter start=1 skip=1 assign="count"}
				{foreach $fields.gallery as $item}
				<div class="row-fluid gallery_item" rel="{$count}">
					<div class="span4" id="gallery_{$count}">
						<input type="hidden" value="gallery_id" name="field[tbl_gallery][{$count}][id]" id="id" />
						<input type="hidden" value="{$item.gallery_id}" name="field[tbl_gallery][{$count}][gallery_id]" >
						<input type="hidden" value="{$item.gallery_file}" name="field[tbl_gallery][{$count}][gallery_file]" id="gallery_image_{$count}"  >
						<input type="hidden" value="{$item.gallery_listing_id}" name="field[tbl_gallery][{$count}][gallery_listing_id]" id="gallery_image_{$count}" class="fileinput">
						<input type="text" value="{$item.gallery_link}" name="field[tbl_gallery][{$count}][gallery_link]" class="fileinput"  id="gallery_image_{$count}_link" >
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
		 <div class="row-fluid control-group btn-separator">
			<div class="span3"></div>
			<div class="span9 controls">
				<div class="row-fluid">
					<div class="span12">
						<a href="javascript:void(0);" class="btn btn-info" onclick="getFileType('','gallery','{$fields.listing_id}')">Add New File</a>
					</div>
				</div>
			</div>
		</div>
		</div>
		{/if}
		<!--  gallery -->
		
		<!--  Property Details -->
		{if $fields.listing_id neq ""}
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="property_street_address">Street Address</label></div>
			<div class="span9 controls">
				<input type="text" value="{$fields.property_street_address}" name="field[tbl_property][{$cnt}][property_street_address]" id="property_street_address" class="req" >
			</div>
		</div>
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="property_suburb">Suburb</label></div>
			<div class="span9 controls">
				<input type="hidden" value="property_id" name="field[tbl_property][{$cnt}][id]" />
				<input type="hidden" value="listing_id" name="default[property_listing_id]" />
				<input type="hidden" value="{$fields.property_id}" name="field[tbl_property][{$cnt}][property_id]" >
				<input type="hidden" value="{$fields.listing_id}" name="field[tbl_property][{$cnt}][property_listing_id]" />
				<!-- <input type="text" value="{$fields.property_suburb}" name="field[tbl_property][{$cnt}][property_suburb]" id="property_suburb" class="req" > -->
				<select name="field[tbl_property][{$cnt}][property_suburb]" id="property_suburb">
					{foreach $fields.options.property_suburb as $opt}
						<option value="{$opt.value}" {if $fields.property_suburb eq $opt.value}selected="selected"{/if}>{$opt.value}</option>
					{/foreach}
				</select>
			</div>
		</div>
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="search">Map Location</label></div>
			<div class="span9">
				<a href="javascript:void(0);" class="btn btn-info" onclick="searchAddress($('#property_street_address').val() + ', ' + $('#property_suburb').val());">Search current address</a>
				<div id="search-warning"></div>
				<input type="hidden" value="{$fields.property_latitude}" name="field[tbl_property][{$cnt}][property_latitude]" id="location_latitude">
				<input type="hidden" value="{$fields.property_longitude}" name="field[tbl_property][{$cnt}][property_longitude]" id="location_longitude">
				<div id="GmlMap" class="GmlMap">Loading Map....</div>
				<script type="text/javascript">
					jQuery(document).ready(function() {
						centerOn({$fields.property_latitude},{$fields.property_longitude});
					});
				</script>
				<script src="http://maps.googleapis.com/maps/api/js?sensor=false" type="text/javascript"></script>
				<script type="text/javascript" src="/admin/includes/google-api/gml-v3.js"></script>
				<link href='/admin/includes/google-api/gml-v3.css' rel='stylesheet' type='text/css'>
			</div>
		</div>
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="property_rent">Rent (per week)</label></div>
			<div class="span9 controls">
				<input type="text" value="{$fields.property_rent}" name="field[tbl_property][{$cnt}][property_rent]" id="property_rent" class="req" >
			</div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="property_type_id">Property Type</label></div>
			<div class="span9 controls">
				<select name="field[tbl_property][{$cnt}][property_type_id]" id="property_type_id">
					{foreach $fields.options.property_type_id as $opt}
						<option value="{$opt.value}" {if $fields.property_type_id eq $opt.value}selected="selected"{/if}>{$opt.value}</option>
					{/foreach}
				</select>
			</div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="property_bedrooms">No. Bedrooms</label></div>
			<div class="span9 controls">
				<select name="field[tbl_property][{$cnt}][property_bedrooms]" id="property_bedrooms">
					{for $val=0 to 6}
						<option value="{$val}" {if $fields.property_bedrooms eq $val}selected="selected" {/if}>{$val}</option>
					{/for}
				</select>
			</div>
		</div>
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="property_bathrooms">No. Bathrooms</label></div>
			<div class="span9 controls">
				<select name="field[tbl_property][{$cnt}][property_bathrooms]" id="property_bathrooms">
					{for $val=0 to 6}
						<option value="{$val}" {if $fields.property_bathrooms eq $val}selected="selected" {/if}>{$val}</option>
					{/for}
				</select>
			</div>
		</div>
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="property_carparks">No. Car Parks</label></div>
			<div class="span9 controls">
				<select name="field[tbl_property][{$cnt}][property_carparks]" id="property_carparks">
					{for $val=0 to 6}
						<option value="{$val}" {if $fields.property_carparks eq $val}selected="selected" {/if}>{$val}</option>
					{/for}
				</select>
			</div>
		</div>
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="property_pet">Pet Friendly</label></div>
			<div class="span9 controls">
				<select name="field[tbl_property][{$cnt}][property_pet]" id="property_pet">
					<option value="0" {if $fields.property_pet eq 0}selected="selected" {/if}>No</option>
					<option value="1" {if $fields.property_pet eq 1}selected="selected" {/if}>Yes</option>
					<option value="2" {if $fields.property_pet eq 2}selected="selected" {/if}>N/A</option>
				</select>
			</div>
		</div>
		
		{/if}					
		<!--  Property Details  -->
			
	<!-- Property Manager -->
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="property_manager_id">Property Manager</label></div>
			<div class="span9 controls">
				<select name="field[tbl_property][{$cnt}][property_manager_id]" id="property_manager_id">
					{foreach $fields.options.listing_id as $opt}
						<option value="{$opt.id}" {if $fields.property_manager_id eq $opt.id}selected="selected"{/if}>{$opt.value}  </option>
					{/foreach}
				</select>
			</div>
		</div>
	<!-- Property Manager -->
	
		<!--  inspections -->
		{if $fields.listing_id neq ""}
		 <div class="row-fluid control-group">
			<div class="span3">
				<label class="control-label" for="inspections-content">Inspections</label>
			</div>
			<div class="span9 controls" id="inspections-content">
				{counter start=1 skip=1 assign="count"}
				{foreach $fields.inspection as $item}
				<div class="row-fluid inspection_item" rel="{$count}" id="inspection_container{$count}">
					<input type="hidden" value="inspection_id" name="field[tbl_inspection][{$count}][id]" id="id" />
					<input type="hidden" value="{$item.inspection_id}" name="field[tbl_inspection][{$count}][inspection_id]" id="inspection_id{$count}">
					<input type="hidden" value="{$fields.listing_id}" name="field[tbl_inspection][{$count}][inspection_listing_id]" id="inspection_listing_{$count}" >
					<div class="row-fluid">
						<div class="span3"><label for="inspection_start_date{$count}">Date</label></div>
						<div class="span9">
							<input type="text" value="{$item.inspection_start_date}" name="field[tbl_inspection][{$count}][inspection_start_date]" class="value" id="inspection_start_date{$count}">
							<script type="text/javascript">
								$(function(){
									$('#inspection_start_date{$count}').datepicker({
										dateFormat: "yy-mm-dd"
									});
								});
							</script>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span3"><label for="inspection_start_time{$count}">Start Time</label></div>
						<div class="span9">
							<input type="text" value="{$item.inspection_start_time}" name="field[tbl_inspection][{$count}][inspection_start_time]" class="value" id="inspection_start_time{$count}">
							<script type="text/javascript">
								$(function(){
									$('#inspection_start_time{$count}').timepicker();
								});
							</script>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span3"><label for="inspection_end_time{$count}">End Time</label></div>
						<div class="span9">
							<input type="text" value="{$item.inspection_end_time}" name="field[tbl_inspection][{$count}][inspection_end_time]" class="value" id="inspection_end_time{$count}">
							<script type="text/javascript">
								$(function(){
									$('#inspection_end_time{$count}').timepicker();
								});
							</script>
						</div>
					</div>
					<div class="row-fluid btn-separator">
						<div class="span12">
							<a href="javascript:void(0);" class="btn btn-danger del-btn" onclick="deleteInspection('inspection_container{$count}')">Delete</a>
						</div>
					</div>
				</div>
				{counter}
				{/foreach}
				<input type="hidden" value="{$count}" name="inspection" id="inspection_no" />
			</div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"></div>
			<div class="span9 controls">
				<div class="row-fluid">
					<div class="span12">
						<a href="javascript:void(0);" class="btn btn-info" onclick="AddInspection();">Add New Inspection</a>
					</div>
				</div>
			</div>
		</div>
		{/if}
	<!-- inspections -->

	
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
function AddInspection(){
	var d = new Date();
	var today = d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDate();
	
	var no = $('#inspection_no').val();
	
	var buf = '<div class="row-fluid inspection_item" rel="'+no+'" id="inspections_container'+no+'">';
	buf += '<input type="hidden" value="inspection_id" name="field[tbl_inspection]['+no+'][id]" id="id" />';
	buf += '<input type="hidden" value="" name="field[tbl_inspection]['+no+'][inspection_id]" id="inspection_id'+no+'">';
	buf += '<input type="hidden" value="{$fields.listing_id}" name="field[tbl_inspection]['+no+'][inspection_listing_id]" id="inspection_listing_'+no+'" >';
	buf += '<div class="row-fluid">';
	buf += '	<div class="span3"><label for="inspection_start_date'+no+'">Date</label></div>';
	buf += '	<div class="span9">';
	buf += '		<input type="text" value="'+today+'" name="field[tbl_inspection]['+no+'][inspection_start_date]" id="inspection_start_date'+no+'">';
	buf += '	</div>';
	buf += '</div>';
	buf += '<div class="row-fluid">';
	buf += '	<div class="span3"><label for="inspection_start_time'+no+'">Start Time</label></div>';
	buf += '	<div class="span9">';
	buf += '		<input type="text" value="9:00" name="field[tbl_inspection]['+no+'][inspection_start_time]" id="inspection_start_time'+no+'"/>';
	buf += '	</div>';
	buf += '</div>';
	buf += '<div class="row-fluid">';
	buf += '	<div class="span3"><label for="inspection_end_time'+no+'">End Time</label></div>';
	buf += '	<div class="span9">';
	buf += '		<input type="text" value="9:00" name="field[tbl_inspection]['+no+'][inspection_end_time]" id="inspection_end_time'+no+'"/>';
	buf += '	</div>';
	buf += '</div>';
	buf += '<div class="row-fluid btn-separator">';
	buf += '	<div class="span12">';
	buf += '	<a href="javascript:void(0);" class="btn btn-danger del-btn" onclick="deleteInspection(\'inspections_container'+no+'\')">Delete</a>';
	buf += '	</div>';
	buf += '</div>';
	buf += '</div>';

	var lastno = no;
	$('#inspections-content').append(buf);
	no++;
	$('#inspection_no').val(no);

	$('#inspection_start_date'+lastno).datepicker({
		dateFormat: "yy-mm-dd"
	});
	$('#inspection_start_time'+lastno).timepicker();
	$('#inspection_end_time'+lastno).timepicker();
	

}
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