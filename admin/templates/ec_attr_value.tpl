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
    <input type="hidden" value="{$attr_value.attr_value_id}" name="field[10][tbl_attr_value][{$attrvalueno}][attr_value_id]" id="attr_value_id" class="key" />
    <input type="hidden" value="attr_value_id" name="field[10][tbl_attr_value][{$attrvalueno}][id]" id="id" />
    <input type="hidden" value="{$attr_value.attr_value_attribute_id}" name="field[10][tbl_attr_value][{$attrvalueno}][attr_value_attribute_id]" id="attr_value_attribute_id" class="key" />
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="attr_value_name">Value*</label>
      <div class="col-sm-4">
        <input class="form-control" type="text" value="{$attr_value.attr_value_name}" name="field[10][tbl_attr_value][{$attrvalueno}][attr_value_name]" id="attr_value_name" onchange="$('#attr_value_name_{$attrvalueno}_preview').html(this.value);" required>
      </div>
    </div>
    {if $enableImg eq '1'}
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="attr_value_description_{$attrvalueno}">Image</label>
      <div class="col-sm-5">
        <input type="hidden" value="{$attr_value.attr_value_description}" name="field[10][tbl_attr_value][{$attrvalueno}][attr_value_description]" id="attr_value_description_{$attrvalueno}_link" class="fileinput"> 
        <span class="file-view" id="attr_value_description_{$attrvalueno}_path">{if $attr_value.attr_value_description}<a href="{$attr_value.attr_value_description}" target="_blank" >View</a>{else}None{/if}</span> 
        <a href="javascript:void(0);" class="btn btn-info marg-5r"
          onclick="getFileType('attr_value_description_{$attrvalueno}','','');"
        >Select File</a> 
        <a href="javascript:void(0);" class="btn btn-info"
          onclick="
            $('#attr_value_description_{$attrvalueno}_link').val('');
            $('#attr_value_description_{$attrvalueno}_path').html('None');
          "
        >Remove File</a>
      </div>
    </div>
    {else}
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="attr_value_description_{$attrvalueno}">Description</label>
      <div class="col-sm-4">
        <textarea class="form-control" maxlength="255" name="field[10][tbl_attr_value][{$attrvalueno}][attr_value_description]" id="attr_value_description_{$attrvalueno}" >{$attr_value.attr_value_description}</textarea>
      </div>
    </div>
    {/if}
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="attr_value_order">Order</label>
      <div class="col-sm-4">
        <input class="form-control number" type="text" value="{if $attr_value.attr_value_order neq ''}{$attr_value.attr_value_order}{else}999{/if}" name="field[10][tbl_attr_value][{$attrvalueno}][attr_value_order]" id="attr_value_order">
      </div>
    </div>
  </div>
</div>

