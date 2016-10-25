{block name=gallery}
<div id="image_wrapper{$imageno}" rel="{$imageno}" class="gallery-image sub-form">
  <div class="row">
    <div class="col-sm-8">
      <fieldset>
        <legend style="font-size: 17px;">
          <div id="gallery_image_{$imageno}_preview">
            {if $images.gallery_link}<img src="{$images.gallery_link}" alt="{$images.gallery_alt}" height="50px" width="50px">{/if}
          </div>
          <div id="gallery_title_{$imageno}_preview">{if $images.gallery_title}{$images.gallery_title}{else}Image #{$imageno}{/if}</div>
        </legend>
      </fieldset>
    </div>
    <div class="col-sm-2">
      <a href="javascript:void(0);" class="btn btn-warning trainer-btn" onclick="toggleImage('{$imageno}')">Show / Hide</a>
    </div>
    <div class="col-sm-2">
      <a href="javascript:void(0);" class="btn btn-danger del-btn" onclick="deleteImage('image_wrapper{$imageno}')">Delete</a>
    </div>
  </div>
  <div class="row images" id="image{$imageno}">
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="gallery_image_{$imageno}">
        Image<br>
        <small>{if $image_size}{$image_size}{/if}</small>
      </label>
      <div class="col-sm-9">
        <input type="hidden" value="{$gTableName}_id" name="default[gallery_{$gTableName}_id]" />
        <input type="hidden" value="gallery_id" name="field[10][tbl_gallery][{$imageno}][id]" id="id" />
        <input type="hidden" value="{$images.gallery_id}" name="field[10][tbl_gallery][{$imageno}][gallery_id]" class="key">
        <input type="hidden" value="{$images.gallery_file}" name="field[10][tbl_gallery][{$imageno}][gallery_file]" id="gallery_image_{$imageno}">
        <input type="hidden" value="0" name="field[10][tbl_gallery][{$imageno}][gallery_ishero]" id="gallery_ishero_{$imageno}" class="ishero">
        <input type="hidden" value="{$images.gallery_listing_id}" name="field[10][tbl_gallery][{$imageno}][gallery_listing_id]" id="gallery_listing_id" class="key">
        <input type="hidden" value="{$images.gallery_product_id}" name="field[10][tbl_gallery][{$imageno}][gallery_product_id]" id="gallery_product_id" class="key">
        <input type="hidden" value="{$images.gallery_link}" name="field[10][tbl_gallery][{$imageno}][gallery_link]" id="gallery_image_{$imageno}_link" class="fileinput" required>
        <span class="file-view" id="gallery_image_{$imageno}_path">{if $images.gallery_link}<a href="{$images.gallery_link}" target="_blank">View</a>{else}None{/if}
        </span> <a href="javascript:void(0);" class="btn btn-info marg-5r" onclick="getFileType('gallery_image_{$imageno}','','');">Select File</a> <a href="javascript:void(0);" class="btn btn-info" onclick="
						$('#gallery_image_{$imageno}').val('');
						$('#gallery_image_{$imageno}_link').val('');
						$('#gallery_image_{$imageno}_path').html('None');
						$('#gallery_image_{$imageno}_preview').html('');
					">Remove File</a>
      </div>
      <span class="help-block"></span>
    </div>
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="gallery_title_{$imageno}">Title</label>
      <div class="col-sm-5">
        <input class="form-control" type="text" value="{$images.gallery_title}" name="field[10][tbl_gallery][{$imageno}][gallery_title]" id="gallery_title_{$imageno}" onchange="$('#gallery_title_{$imageno}_preview').html(this.value);">
      </div>
    </div>
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="gallery_caption_{$imageno}">Caption</label>
      <div class="col-sm-5">
        <input class="form-control" type="text" value="{$images.gallery_caption}" name="field[10][tbl_gallery][{$imageno}][gallery_caption]" id="gallery_caption_{$imageno}">
      </div>
    </div>
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="gallery_alt_tag_{$imageno}">Alt Tag</label>
      <div class="col-sm-5">
        <input class="form-control" type="text" value="{$images.gallery_alt_tag}" name="field[10][tbl_gallery][{$imageno}][gallery_alt_tag]" id="gallery_alt_tag_{$imageno}">
      </div>
    </div>
    {if $gTableName eq 'product'}
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="gallery_variant_id_{$imageno}">Variant</label>
      <div class="col-sm-5">
        <select class="form-control product-imgs" name="field[10][tbl_gallery][{$imageno}][gallery_variant_id]" id="gallery_variant_id_{$imageno}" data-value="{$images.gallery_variant_id}">
        </select>
      </div>
    </div>
    {/if}
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="gallery_order_{$imageno}">Order</label>
      <div class="col-sm-5">
        <input class="form-control number" type="number" value="{if $images.gallery_order}{$images.gallery_order}{else}999{/if}" name="field[10][tbl_gallery][{$imageno}][gallery_order]" id="gallery_order_{$imageno}">
      </div>
    </div>
  </div>
</div>
{/block}
