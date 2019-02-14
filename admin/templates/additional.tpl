{block name=files}
<div id="additional_wrapper{$additionalno}" rel="{$additionalno}" class="sub-form">
  <div class="row">
    <div class="col-sm-8">
      <fieldset>
        <legend style="font-size: 17px;">
          <div id="additional_title_{$additionalno}">{if $additional.additional_name}{$additional.additional_name}{else}{if $addName}{$addName}{else}Name{/if} #{$additionalno}{/if}</div>
        </legend>
      </fieldset>
    </div>
    <div class="col-sm-2">
      <a href="javascript:void(0);" class="btn btn-warning trainer-btn" onclick="toggleAdditional('{$additionalno}')">Show / Hide</a>
    </div>
    <div class="col-sm-2">
      <a href="javascript:void(0);" class="btn btn-danger del-btn" onclick="deleteAdditional('additional_wrapper{$additionalno}')">Delete</a>
    </div>
  </div>
  <div class="row additionals" id="additional{$additionalno}">
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="additional_name_{$additionalno}">{if $addName}{$addName}{else}Name{/if}</label>
      <div class="col-sm-5">
        <input type="hidden" value="listing_id" name="default[additional_listing_id]" />
        <input type="hidden" value="additional_id" name="field[10][tbl_additional][{$additionalno}][id]" id="id_{$additionalno}" />
        <input type="hidden" value="{$additional.additional_id}" name="field[10][tbl_additional][{$additionalno}][additional_id]" class="key">
        <input type="hidden" value="{$additional.additional_listing_id}" name="field[10][tbl_additional][{$additionalno}][additional_listing_id]" id="additional_listing_id_{$additionalno}" class="key">
        <input type="hidden" value="{if $additional.additional_category}{$additional.additional_category}{else}{$addcat}{/if}" name="field[10][tbl_additional][{$additionalno}][additional_category]" id="additional_category_{$additionalno}">
        <input type="hidden" value="{if $additional.additional_created}{$additional.additional_created}{else}{'Y-m-d H:i:s'|date}{/if}" name="field[10][tbl_additional][{$additionalno}][additional_created]" id="additional_created_{$additionalno}">
        <input class="form-control" type="text" maxlength="500" value="{$additional.additional_name}" name="field[10][tbl_additional][{$additionalno}][additional_name]" id="additional_name_{$additionalno}" onchange="$('#additional_title_{$additionalno}').html(this.value);">
      </div>
    </div>
    {if $addDescriptionName}
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="additional_description_{$additionalno}">{$addDescriptionName}</label>
      <div class="col-sm-5">
        <textarea class="form-control" maxlength="1500" name="field[10][tbl_additional][{$additionalno}][additional_description]" id="additional_description_{$additionalno}">{$additional.additional_description}</textarea>
      </div>
    </div>
    {/if}
    {if $addContent1Name}
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="additional_content1_{$additionalno}">{$addContent1Name}</label>
      <div class="col-sm-5">
        <textarea class="tinymce{if !$additional.additional_id}_additional{$additionalno}{/if}" name="field[10][tbl_additional][{$additionalno}][additional_content1]" id="additional_content1_{$additionalno}">{$additional.additional_content1}</textarea>
      </div>
    </div>
    {/if}
    {if $addFileName}
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="additional_file_{$additionalno}">{$addFileName}</label>
      <div class="col-sm-5">

        <input type="hidden" value="{$additional.additional_file}" name="field[10][tbl_additional][{$additionalno}][additional_file]" id="additional_file_{$additionalno}_link" class="fileinput">
        <span class="file-view" id="additional_file_{$additionalno}_path">{if $additional.additional_file}<a href="{$additional.additional_file}" target="_blank">View</a>{else}None{/if}
        </span> <a href="javascript:void(0);" class="btn btn-info marg-5r" onclick="getFileType('additional_file_{$additionalno}','','');">Select File</a> <a href="javascript:void(0);" class="btn btn-info" onclick="
						$('#additional_file_{$additionalno}_link').val('');
						$('#additional_file_{$additionalno}_path').html('None');
					">Remove File</a>
      </div>
    </div>
    {/if}
    {if $addImageName}
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="additional_image">
        {$addImageName}
      </label>
      <div class="col-sm-9">
        <input type="hidden" value="{$additional.additional_image}" name="field[10][tbl_additional][{$additionalno}][additional_image]" id="additional_image_{$additionalno}_link" class="fileinput">
        <span class="file-view" id="additional_image_{$additionalno}_path">{if $additional.additional_image}<a href="{$additional.additional_image}" target="_blank">View</a>{else}None{/if}
        </span> <a href="javascript:void(0);" class="btn btn-info marg-5r" onclick="getFileType('additional_image_{$additionalno}','','');">Select File</a> <a href="javascript:void(0);" class="btn btn-info" onclick="
						$('#additional_image_{$additionalno}_link').val('');
						$('#additional_image_{$additionalno}_path').html('None');
					">Remove File</a>
      </div>
    </div>
    {/if}
    {if $addFlag1Name}
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="additional_flag1_{$additionalno}">{$addFlag1Name}</label>
      <div class="col-sm-5">
        <input type="hidden" value="{if $additional.additional_flag1 eq 1}1{else}0{/if}" name="field[10][tbl_additional][{$additionalno}][additional_flag1]" class="value">
        <input class="chckbx" type="checkbox" {if $additional.additional_flag1 eq 1} checked="checked" {/if}
						 onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="additional_flag1_{$additionalno}">
      </div>
    </div>
    {/if}
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="additional_order_{$additionalno}">Order</label>
      <div class="col-sm-4">
        <input class="form-control number" type="text" value="{if $additional.additional_order}{$additional.additional_order}{else}999{/if}" name="field[10][tbl_additional][{$additionalno}][additional_order]" id="additional_order_{$additionalno}">
      </div>
    </div>
  </div>
</div>
{/block}
