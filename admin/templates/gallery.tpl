		<div id="image_wrapper{$imageno}" rel="{$imageno}" class="gallery-image">
			<div class="row-fluid" >
				<div class="span8">
					<fieldset>
						<legend>{if $images.gallery_file}{$images.gallery_file}{else}Image #{$imageno}{/if}</legend>
					</fieldset>
				</div>
				<div class="span2">
					<a href="javascript:void(0);" class="btn btn-warning trainer-btn" onclick="toggleImage('{$imageno}')">Show / Hide</a>
				</div>
				<div class="span2">
					<a href="javascript:void(0);" class="btn btn-danger del-btn" onclick="deleteImage('image_wrapper{$imageno}')">Delete</a>
				</div>
			</div>
			<div class="row-fluid images" id="image{$imageno}">
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="gallery_image">Image</label><br />
						<label class="control-label small-txt">Size: 480px Wide x 480px Tall</label>
					</div>
					<div class="span9 controls">
						<input type="hidden" value="product_id" name="default[gallery_product_id]" />
						<input type="hidden" value="gallery_id" name="field[10][tbl_gallery][{$imageno}][id]" id="id" />
						<input type="hidden" value="{$images.gallery_id}" name="field[10][tbl_gallery][{$imageno}][gallery_id]" >
						<input type="hidden" value="{$images.gallery_file}" name="field[10][tbl_gallery][{$imageno}][gallery_file]" id="gallery_image_{$imageno}" >
						<input type="hidden" value="0" name="field[10][tbl_gallery][{$imageno}][gallery_ishero]" id="gallery_ishero_{$imageno}" class="ishero"> 
						<input type="hidden" value="{$images.gallery_product_id}" name="field[10][tbl_gallery][{$imageno}][gallery_product_id]" id="gallery_product_id" >
						<input type="hidden" value="{$images.gallery_link}" name="field[10][tbl_gallery][{$imageno}][gallery_link]" id="gallery_image_{$imageno}_link" class="fileinput"> 
						<span class="file-view" id="gallery_image_view_{$imageno}" {if $images.gallery_link eq ""}style="display: none"{/if} > 
							<a href="{$images.gallery_link}" target="_blank" id="gallery_image_{$imageno}_path">{$images.gallery_link}</a>
						</span> 
						<span class="file-view" id="gallery_image_none_{$imageno}" {if $images.gallery_link neq ""}style="display: none"{/if}>None</span> 
						<a href="javascript:void(0);" class="btn btn-info marg-5r"
							onclick="
								getFileType('gallery_image_{$imageno}','','');
								$('#gallery_image_view_{$imageno}').css('display','block');
								$('#gallery_image_none_{$imageno}').css('display','none');
							"
						>Select File</a> 
						<a href="javascript:void(0);" class="btn btn-info"
							onclick="
								$('#gallery_image_{$imageno}').val('');
								$('#gallery_image_{$imageno}_link').val('');
								$('#gallery_image_view_{$imageno}').css('display','none');
								$('#gallery_image_non_{$imageno}e').css('display','block');
							"
						>Remove File</a>
					</div>
				</div>
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="gallery_title">Title</label>
					</div>
					<div class="span9 controls">
						<input type="text" value="{$images.gallery_title}" name="field[10][tbl_gallery][{$imageno}][gallery_title]" id="gallery_title"  style="width: 70%;">
					</div>
				</div>
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="gallery_caption">Caption</label>
					</div>
					<div class="span9 controls">
						<input type="text" value="{$images.gallery_caption}" name="field[10][tbl_gallery][{$imageno}][gallery_caption]" id="gallery_caption"  style="width: 70%;">
					</div>
				</div>				
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="gallery_alt_tag">Alt Tag</label>
					</div>
					<div class="span9 controls">
						<input type="text" value="{$images.gallery_alt_tag}" name="field[10][tbl_gallery][{$imageno}][gallery_alt_tag]" id="gallery_alt_tag" >
					</div>
				</div>
			</div>
		</div>
