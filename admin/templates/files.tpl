{block name=files}
<div id="file_wrapper{$filesno}" rel="{$filesno}" class="files-file sub-form">
	<div class="row" >
		<div class="col-sm-8">
			<fieldset>
				<legend style="font-size:17px;">
					<div id="files_title_{$filesno}">{if $files.files_friendly_name}{$files.files_friendly_name}{else}File #{$filesno}{/if}</div>
				</legend>
			</fieldset>
		</div>
		<div class="col-sm-2">
			<a href="javascript:void(0);" class="btn btn-warning trainer-btn" onclick="toggleFile('{$filesno}')">Show / Hide</a>
		</div>
		<div class="col-sm-2">
			<a href="javascript:void(0);" class="btn btn-danger del-btn" onclick="deleteFile('file_wrapper{$filesno}')">Delete</a>
		</div>
	</div>
	<div class="row files" id="file{$filesno}">
		<div class="row form-group">
			<label class="col-sm-3 control-label" for="files_title">Title</label>
			<div class="col-sm-5">
				<input type="hidden" value="{$gTableName}_id" name="default[files_{$gTableName}_id]" />
				<input type="hidden" value="files_id" name="field[10][tbl_files][{$filesno}][id]" id="id"/>
				<input type="hidden" value="{$files.files_id}" name="field[10][tbl_files][{$filesno}][files_id]" class="key" >
				<input type="hidden" value="{$files.files_listing_id}" name="field[10][tbl_files][{$filesno}][files_listing_id]" id="files_listing_id" class="key" >
				
				<input class="form-control" type="text" value="{$files.files_friendly_name}" name="field[10][tbl_files][{$filesno}][files_friendly_name]" id="files_title_{$filesno}" onchange="$('#files_title_{$filesno}').html(this.value);">
			</div>
		</div>
		<div class="row form-group">
			<label class="col-sm-3 control-label" for="files_filename_{$filesno}">Short Description</label>
			<div class="col-sm-5">
				<textarea class="tinymce" name="field[10][tbl_files][{$filesno}][files_filename]" id="files_filename_{$filesno}" >{$files.files_filename}</textarea>
			</div>
		</div>
		<div class="row form-group">
			<label class="col-sm-3 control-label" for="files_path">File</label>
			<div class="col-sm-5">
				
				<input type="hidden" value="{$files.files_path}" name="field[10][tbl_files][{$filesno}][files_path]" id="files_path_{$filesno}_link" class="fileinput"> 
				<span class="file-view" id="files_path_{$filesno}_path">{if $files.files_path}<a href="{$files.files_path}" target="_blank" >View</a>{else}None{/if}</span> 
				<a href="javascript:void(0);" class="btn btn-info marg-5r"
					onclick="getFileType('files_path_{$filesno}','','');"
				>Select File</a> 
				<a href="javascript:void(0);" class="btn btn-info"
					onclick="
						$('#files_path_{$filesno}_link').val('');
						$('#files_path_{$filesno}_path').html('None');
					"
				>Remove File</a>
			</div>
		</div>
		<div class="row form-group">
			<label class="col-sm-3 control-label" for="files_image">Image <small>(optional)</small></label>
			<div class="col-sm-9">
				<input type="hidden" value="{$files.files_image}" name="field[10][tbl_files][{$filesno}][files_image]" id="files_image_{$filesno}_link" class="fileinput"> 
				<span class="file-view" id="files_image_{$filesno}_path">{if $files.files_image}<a href="{$files.files_image}" target="_blank" >View</a>{else}None{/if}</span> 
				<a href="javascript:void(0);" class="btn btn-info marg-5r"
					onclick="getFileType('files_image_{$filesno}','','');"
				>Select File</a> 
				<a href="javascript:void(0);" class="btn btn-info"
					onclick="
						$('#files_image_{$filesno}_link').val('');
						$('#files_image_{$filesno}_path').html('None');
					"
				>Remove File</a>
			</div>
		</div>	
	</div>
</div>
{/block}