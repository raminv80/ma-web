{block name=body}<!-- fredys comment -->
<div class="row-fluid">
	<div class="span12">
	<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" action="/admin/includes/processes/processes-record.php" method="post">
		<div class="row-fluid">
			<div class="span12">
            	<fieldset>
                <legend>
				{if $fields.category_id neq ""}Edit{else}New{/if} Category
				{if $cnt eq ""}{assign var=cnt value=0}{/if}
                </legend>
                </fieldset>
				<input type="hidden" value="{$fields.category_id}" name="id" id="category_id" />
				<input type="hidden" value="{$fields.category_id}" name="field[tbl_category][{$cnt}][category_id]" id="category_id" />
				<input type="hidden" value="category_id" name="field[tbl_category][{$cnt}][id]"   />
				<input type="hidden"  value="4" name="field[tbl_category][{$cnt}][category_type_id]"  />


			</div>
		</div>
        <legend></legend>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="category_name">Name</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.category_name}" name="field[tbl_category][{$cnt}][category_name]" id="category_name" class="req" ></div>
		</div>
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_category_short_description">Short Description</label></div>
			<div class="span9 controls"><input type="text" name="field[tbl_category][{$cnt}][category_short_description]" id="id_category_short_description" value="{$fields.category_short_description}" maxlength="150" class="tool-tip" data-original-title="maximum 150 characters"></div>
		</div>

		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="id_category_long_description">Long Description</label></div>
			<div class="span9 controls"><textarea name="field[tbl_category][{$cnt}][category_long_description]" id="id_category_long_description" class="tinymce">{$fields.category_long_description}</textarea></div>
		</div>

		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="category_image">Thumbnail Image</label></div>
			<div class="span9 controls">
			<input type="hidden" value="{$fields.category_image}" name="field[tbl_category][{$cnt}][category_image]" id="category_image" class="req  fileinput">
			<span class="file-view" id="category_image_view" {if $fields.category_image eq ""}style="display:none"{/if}><a href="{$fields.category_image}" target="_blank" id="category_image_path">{$fields.category_image}</a></span><span class="file-view" id="category_image_none" {if $fields.category_image neq ""}style="display:none"{/if}>None</span>
			<a href="javascript:void(0);" class="btn btn-info marg-5r" onclick="getFileType('category_image','','');$('#category_image_view').css('display','block');$('#category_image_none').css('display','none');">Select File</a><a href="javascript:void(0);" class="btn btn-info" onclick="$('#category_image').val('');$('#category_image_view').css('display','none');$('#category_image_none').css('display','block');">Remove File</a>
			<br><small>Please use an image of 134px wide by 121px high.</small>
			</div>
		</div>
	<!--	 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="category_type_id">Category Type</label></div>
			<div class="span9 controls"><select name="field[tbl_category][{$cnt}][category_type_id]" id="category_type_id">
									{foreach $fields.options.category_type_id as $opt}
									<option value="{$opt.id}" {if $fields.category_type_id eq $opt.id}selected="selected"{/if}>{$opt.value}</option>
									{/foreach}
								</select>
			</div>
		</div>

		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="category_category_id">Associated Listing</label></div>
			<div class="span9 controls"><select name="field[tbl_category][{$cnt}][category_listing_id]" id="category_listing_id">
								<option value="0" {if $fields.category_level eq "0"}selected="selected"{/if}>NONE</option>
									{foreach $fields.options.category_listing_id as $opt}
									<option value="{$opt.id}" {if $fields.category_level eq $opt.id}selected="selected"{/if}>{$opt.value}</option>
									{/foreach}
								</select>
			</div>
		</div>
		 -->
		  <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="category_video_1">Category Video 1</label></div>
			<div class="span9 controls"><input type="text" name="field[tbl_category][{$cnt}][category_video_1]" class="tool-tip" value="{$fields.category_video_1}"  data-toggle="tooltip" data-original-title="Please enter YouTube video ID
eg. From the video url http://www.youtube.com/watch?v=[USE THIS PART]" id="category_video_1"  />
			</div>
		</div>
				  <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="category_video_2">Category Video 2</label></div>
			<div class="span9 controls"><input type="text" name="field[tbl_category][{$cnt}][category_video_2]"  class="tool-tip" value="{$fields.category_video_2}" data-toggle="tooltip" data-original-title="Please enter YouTube video ID
eg. From the video url http://www.youtube.com/watch?v=[USE THIS PART]" id="category_video_2" />
			</div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="category_parent_id">Parent Category</label></div>
			<div class="span9 controls"><select name="field[tbl_category][{$cnt}][category_parent_id]" id="category_parent_id">
			<option value="0" {if $fields.category_level eq "0"}selected="selected"{/if}>NONE</option>
									{foreach $fields.options.category_parent_id as $opt}
									<option value="{$opt.id}" {if $fields.category_parent_id eq $opt.id}selected="selected"{/if}>{$opt.value}  </option>
									{/foreach}
								</select>
			</div>
		</div>
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="slide_special">Active</label></div>
			<div class="span9 controls">
				<input type="checkbox" onclick="validate();"  name="category_published_check" id="category_published_check" class="" {if $fields.category_published eq "1"}checked="checked"{/if}  >
				<input type="hidden" value="0"  name="field[tbl_category][{$cnt}][category_published]" id="category_published" >
			</div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span9 offset3">
				<script type="text/javascript">
				function validate(){
					if($('#category_published_check').attr('checked') == "checked"){
						$('#category_published').val('1');
					}else{
						$('#category_published').val('0');
					}

					$('#Edit_Record').submit();
				}

			</script>
			</div>

            <div class="form-actions">
                <button class="btn btn-primary" onClick="validate()" type="submit">Submit</button>
            </div>

			<input type="hidden" name="formToken" id="formToken" value="{$token}" />
		</div>
	</form>
	</div>
</div>
{/block}