<div id="attr_value_wrapper{$attrvalueno}" class="attribute-attr_values attribute_attr_values{$attributeno}" rel="{$attrvalueno}">
  <div class="row attribute-attr_values-title">
    <div class="col-sm-offset-1  col-sm-7">
      <fieldset>
        <legend style="font-size: 17px;">
          <div id="attr_value_name_{$attrvalueno}_preview">{if $attr_value.attr_value_name} {$attr_value.attr_value_name}{else} Attr_value #{$attrvalueno}{/if}</div>
        </legend>
      </fieldset>
    </div>
    <div class="col-sm-2">
      <a href="javascript:void(0);" class="btn btn-warning trainer-btn" onclick="ToggleAccordionElem('attr_value{$attrvalueno}', 'attr_values')">Show / Hide</a>
    </div>
    <div class="col-sm-2">
      <a href="javascript:void(0);" class="btn btn-danger del-btn" onclick="deleteAttr_value('attr_value_wrapper{$attrvalueno}')">Delete</a>
    </div>
  </div>
  <div class="row attr_values" id="attr_value{$attrvalueno}">
    <input type="hidden" value="attribute_id" name="default[attr_value_attribute_id]" />
    <input type="hidden" value="{$attr_value.attr_value_id}" name="field[10][tbl_attr_value][{$attrvalueno}][attr_value_id]" id="attr_value_id_{$attrvalueno}" class="key" />
    <input type="hidden" value="attr_value_id" name="field[10][tbl_attr_value][{$attrvalueno}][id]" id="id_{$attrvalueno}" />
    <input type="hidden" value="{$attr_value.attr_value_attribute_id}" name="field[10][tbl_attr_value][{$attrvalueno}][attr_value_attribute_id]" id="attr_value_attribute_id_{$attrvalueno}" class="key" />
    <input type="hidden" value="{if $attr_value.attr_value_created}{$attr_value.attr_value_created}{else}{'Y-m-d H:i:s'|date}{/if}" name="field[10][tbl_attr_value][{$attrvalueno}][attr_value_created]" id="attr_value_created_{$attrvalueno}">
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="attr_value_name_{$attrvalueno}">Value*</label>
      <div class="col-sm-4">
        <input class="form-control" type="text" value="{$attr_value.attr_value_name}" name="field[10][tbl_attr_value][{$attrvalueno}][attr_value_name]" id="attr_value_name_{$attrvalueno}" onchange="$('#attr_value_name_{$attrvalueno}_preview').html(this.value);" required>
      </div>
    </div>
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="attr_value_description_{$attrvalueno}">Description</label>
      <div class="col-sm-4">
        <textarea class="tinymce{if !$attr_value.attr_value_id}{$attrvalueno}{/if}" name="field[10][tbl_attr_value][{$attrvalueno}][attr_value_description]" id="attr_value_description_{$attrvalueno}" >{$attr_value.attr_value_description}</textarea>
      </div>
    </div>
    {if $attrtype eq '1'}
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="attr_value_image_{$attrvalueno}">Image</label>
      <div class="col-sm-5">
        <input type="hidden" value="{$attr_value.attr_value_image}" name="field[10][tbl_attr_value][{$attrvalueno}][attr_value_image]" id="attr_value_image_{$attrvalueno}_link" class="fileinput"> 
        <span class="file-view" id="attr_value_image_{$attrvalueno}_path">{if $attr_value.attr_value_image}<a href="{$attr_value.attr_value_image}" target="_blank" >View</a>{else}None{/if}</span> 
        <a href="javascript:void(0);" class="btn btn-info marg-5r"
          onclick="getFileType('attr_value_image_{$attrvalueno}','','');"
        >Select File</a> 
        <a href="javascript:void(0);" class="btn btn-info"
          onclick="
            $('#attr_value_image_{$attrvalueno}_link').val('');
            $('#attr_value_image_{$attrvalueno}_path').html('None');
          "
        >Remove File</a>
      </div>
    </div>
    {elseif $attrtype eq '2'}
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="attr_value_var1_{$attrvalueno}">Engraving line 1</label>
      <div class="col-sm-4">
        <input class="form-control number" type="text" maxlength="3" value="{if $attr_value.attr_value_var1}{$attr_value.attr_value_var1}{else}0{/if}" name="field[10][tbl_attr_value][{$attrvalueno}][attr_value_var1]" id="attr_value_var1_{$attrvalueno}">
      </div>
    </div>
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="attr_value_var2_{$attrvalueno}">Engraving line 2</label>
      <div class="col-sm-4">
        <input class="form-control number" type="text" maxlength="3" value="{if $attr_value.attr_value_var2}{$attr_value.attr_value_var2}{else}0{/if}" name="field[10][tbl_attr_value][{$attrvalueno}][attr_value_var2]" id="attr_value_var2_{$attrvalueno}">
      </div>
    </div>
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="attr_value_var3_{$attrvalueno}">Engraving line 3</label>
      <div class="col-sm-4">
        <input class="form-control number" type="text" maxlength="3" value="{if $attr_value.attr_value_var3}{$attr_value.attr_value_var3}{else}0{/if}" name="field[10][tbl_attr_value][{$attrvalueno}][attr_value_var3]" id="attr_value_var3_{$attrvalueno}">
      </div>
    </div>
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="attr_value_var4_{$attrvalueno}">Engraving line 4</label>
      <div class="col-sm-4">
        <input class="form-control number" type="text" maxlength="3" value="{if $attr_value.attr_value_var4}{$attr_value.attr_value_var4}{else}0{/if}" name="field[10][tbl_attr_value][{$attrvalueno}][attr_value_var4]" id="attr_value_var4_{$attrvalueno}">
      </div>
    </div>
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="attr_value_var5_{$attrvalueno}">Engraving line 5</label>
      <div class="col-sm-4">
        <input class="form-control number" type="text" maxlength="3" value="{if $attr_value.attr_value_var5}{$attr_value.attr_value_var5}{else}0{/if}" name="field[10][tbl_attr_value][{$attrvalueno}][attr_value_var5]" id="attr_value_var5_{$attrvalueno}">
      </div>
    </div>
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="attr_value_var6_{$attrvalueno}">Engraving line 6</label>
      <div class="col-sm-4">
        <input class="form-control number" type="text" maxlength="3" value="{if $attr_value.attr_value_var6}{$attr_value.attr_value_var6}{else}0{/if}" name="field[10][tbl_attr_value][{$attrvalueno}][attr_value_var6]" id="attr_value_var6_{$attrvalueno}">
      </div>
    </div>
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="attr_value_var7_{$attrvalueno}">Engraving line 7</label>
      <div class="col-sm-4">
        <input class="form-control number" type="text" maxlength="3" value="{if $attr_value.attr_value_var7}{$attr_value.attr_value_var7}{else}0{/if}" name="field[10][tbl_attr_value][{$attrvalueno}][attr_value_var7]" id="attr_value_var7_{$attrvalueno}">
      </div>
    </div>
    {/if}
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="attr_value_order_{$attrvalueno}">Order</label>
      <div class="col-sm-4">
        <input class="form-control number" type="text" value="{if $attr_value.attr_value_order}{$attr_value.attr_value_order}{else}999{/if}" name="field[10][tbl_attr_value][{$attrvalueno}][attr_value_order]" id="attr_value_order_{$attrvalueno}">
      </div>
    </div>
  </div>
</div>

