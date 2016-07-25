<div id="variant_wrapper{$variantno}" rel="{$variantno}">
  <div class="row attribute-attr_values-title">
    <div class="col-sm-offset-1  col-sm-7">
      <fieldset>
        <legend style="font-size: 17px;">
          <div id="variant_title_{$variantno}_preview">{if $variant.variant_uid} {$variant.variant_uid}{else} Variant #{$variantno}{/if}</div>
        </legend>
      </fieldset>
    </div>
    <div class="col-sm-2">
      <a href="javascript:void(0);" class="btn btn-warning trainer-btn" onclick="ToggleAccordionElem('variant{$variantno}', 'variants')">Show / Hide</a>
    </div>
    <div class="col-sm-2">
      <a href="javascript:void(0);" class="btn btn-danger del-btn" onclick="deleteVariant('variant_wrapper{$variantno}')">Delete</a>
    </div>
  </div>
  <div class="variants" id="variant{$variantno}">
    <input type="hidden" value="product_id" name="default[variant_product_id]" />
    <input type="hidden" value="{$variant.variant_id}" name="field[{$variantno*10+1}][tbl_variant][{$variantno}][variant_id]" id="variant_id_{$variantno}" class="key" />
    <input type="hidden" value="variant_id" name="field[{$variantno*10+1}][tbl_variant][{$variantno}][id]" id="id_{$variantno}" />
    <input type="hidden" value="{$variant.variant_product_id}" name="field[{$variantno*10+1}][tbl_variant][{$variantno}][variant_product_id]" id="variant_product_id_{$variantno}" class="key" />
    <input type="hidden" value="{if $variant.variant_created}{$variant.variant_created}{else}{'Y-m-d H:i:s'|date}{/if}" name="field[{$variantno*10+1}][tbl_variant][{$variantno}][variant_created]" id="variant_created_{$variantno}">
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="variant_uid_{$variantno}">Code*</label>
      <div class="col-sm-4">
        <input class="form-control" type="text" value="{$variant.variant_uid}" name="field[{$variantno*10+1}][tbl_variant][{$variantno}][variant_uid]" id="variant_uid_{$variantno}" onchange="$('#variant_title_{$variantno}_preview').html(this.value);" required>
      </div>
    </div>
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="variant_name_{$variantno}">Name</label>
      <div class="col-sm-4">
        <input class="form-control" type="text" value="{$variant.variant_name}" name="field[{$variantno*10+1}][tbl_variant][{$variantno}][variant_name]" id="variant_name_{$variantno}">
      </div>
    </div>
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="variant_description_{$variantno}">Description</label>
      <div class="col-sm-4">
        <textarea class="form-control" maxlength="255" name="field[{$variantno*10+1}][tbl_variant][{$variantno}][variant_description]" id="variant_description_{$variantno}">{$variant.variant_description}</textarea>
      </div>
    </div>

    <input type="hidden" value="productattr_variant_id" name="default[productattr_variant_id]" />
    {if !$typeID}{assign var="typeID" value="1"}{/if}
    {foreach $attributes.$typeID as $attr}
      {foreach $variant.productattributes as $prdattr}
        {if $prdattr.productattr_attribute_id eq $attr.id}
        {assign var="existing_id" value="$prdattr.productattr_id"}
        {assign var="selected" value="$prdattr.productattr_attr_value_id"}
        {/if}
      {/foreach}
      <div class="row form-group">
        <label class="col-sm-3 control-label" for="variant_type_id_{$variantno}_{$attr.id}">{$attr.name}</label>
        <div class="col-sm-4">
          <input type="hidden" value="{$existing_id}" name="field[{$variantno*10+2}][tbl_productattr][{$attr.id}][productattr_id]" class="key" />
          <input type="hidden" value="variant_id" name="field[{$variantno*10+2}][tbl_productattr][{$attr.id}][id]" />
          <input type="hidden" value="{$variant.variant_id}" name="field[{$variantno*10+2}][tbl_productattr][{$attr.id}][productattr_variant_id]" class="key" />
          <input type="hidden" value="{if $variant.productattr_created}{$variant.productattr_created}{else}{'Y-m-d H:i:s'|date}{/if}" name="field[{$variantno*10+2}][tbl_productattr][{$attr.id}][productattr_created]">
          <input type="hidden" value="{$attr.id}" name="field[{$variantno*10+2}][tbl_productattr][{$attr.id}][productattr_attribute_id]">
  
          <select class="form-control" name="field[{$variantno*10+2}][tbl_productattr][{$attr.id}][productattr_type_id]" id="variant_type_id_{$variantno}_{$attr.id}">
            <option value="0">Select one</option>
            {foreach $attr.values as $opt}
            <option value="{$opt.attr_value_id}" {if  $selected eq $opt.attr_value_id}selected="selected"{/if}>{$opt.attr_value_name}</option>
            {/foreach}
          </select>
        </div>
      </div>
    {/foreach}

    <div class="row form-group">
      <label class="col-sm-3 control-label" for="variant_price_{$variantno}">Price ($)</label>
      <div class="col-sm-5 ">
        <input class="form-control double" type="text" value="{if $variant.variant_price}{$variant.variant_price}{else}0{/if}" name="field[{$variantno*10+1}][tbl_variant][{$variantno}][variant_price]" id="variant_price_{$variantno}">
      </div>
    </div>
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="variant_specialprice_{$variantno}">Special Price ($)</label>
      <div class="col-sm-5 ">
        <input class="form-control double" type="text" value="{if $variant.variant_specialprice}{$variant.variant_specialprice}{else}0{/if}" name="field[{$variantno*10+1}][tbl_variant][{$variantno}][variant_specialprice]" id="variant_specialprice_{$variantno}">
      </div>
    </div>
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="variant_memberprice_{$variantno}">Member Price ($)</label>
      <div class="col-sm-5 ">
        <input class="form-control double" type="text" value="{if $variant.variant_memberprice}{$variant.variant_memberprice}{else}0{/if}" name="field[{$variantno*10+1}][tbl_variant][{$variantno}][variant_memberprice]" id="variant_memberprice_{$variantno}">
      </div>
    </div>
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="variant_weight_{$variantno}">Weight (Kg)</label>
      <div class="col-sm-5 ">
        <input class="form-control double" type="text" value="{$variant.variant_weight}" name="field[{$variantno*10+1}][tbl_variant][{$variantno}][variant_weight]" id="variant_weight_{$variantno}">
      </div>
    </div>
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="variant_width_{$variantno}">Width (cm)</label>
      <div class="col-sm-5 ">
        <input class="form-control double" type="text" value="{$variant.variant_width}" name="field[{$variantno*10+1}][tbl_variant][{$variantno}][variant_width]" id="variant_width_{$variantno}">
      </div>
    </div>
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="variant_height_{$variantno}">Height (cm)</label>
      <div class="col-sm-5 ">
        <input class="form-control double" type="text" value="{$variant.variant_height}" name="field[{$variantno*10+1}][tbl_variant][{$variantno}][variant_height]" id="variant_height_{$variantno}">
      </div>
    </div>
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="variant_length_{$variantno}">Length (cm)</label>
      <div class="col-sm-5 ">
        <input class="form-control double" type="text" value="{$variant.variant_length}" name="field[{$variantno*10+1}][tbl_variant][{$variantno}][variant_length]" id="variant_length_{$variantno}">
      </div>
    </div>
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="variant_new_{$variantno}">New</label>
      <div class="col-sm-5 ">
        <input type="hidden" value="{if $variant.variant_id}{if $variant.variant_new eq 1}1{else}0{/if}{else}0{/if}" name="field[{$variantno*10+1}][tbl_variant][{$variantno}][variant_new]" class="value">
        <input class="chckbx" type="checkbox" {if $variant.variant_new eq 1}checked="checked" {/if} onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="variant_new_{$variantno}">
      </div>
    </div>
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="variant_instock_{$variantno}">In stock</label>
      <div class="col-sm-5 ">
        <input type="hidden" value="{if $variant.variant_id}{if $variant.variant_instock eq 1}1{else}0{/if}{else}1{/if}" name="field[{$variantno*10+1}][tbl_variant][{$variantno}][variant_instock]" class="value">
        <input class="chckbx" type="checkbox" {if $variant.variant_instock eq 1 || $variant.variant_id eq ""}checked="checked" {/if} onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="variant_instock_{$variantno}">
      </div>
    </div>
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="variant_limitedstock_{$variantno}">Limited stock</label>
      <div class="col-sm-5 ">
        <input type="hidden" value="{if $variant.variant_id}{if $variant.variant_limitedstock eq 1}1{else}0{/if}{else}0{/if}" name="field[{$variantno*10+1}][tbl_variant][{$variantno}][variant_limitedstock]" class="value">
        <input class="chckbx" type="checkbox" {if $variant.variant_limitedstock eq 1}checked="checked" {/if} onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="variant_limitedstock_{$variantno}">
      </div>
    </div>
    <div class="row form-group">
      <label class="col-sm-3 control-label" for="variant_published_{$variantno}">Published</label>
      <div class="col-sm-5 ">
        <input type="hidden" value="{if $variant.variant_id}{if $variant.variant_published eq 1}1{else}0{/if}{else}1{/if}" name="field[{$variantno*10+1}][tbl_variant][{$variantno}][variant_published]" class="value">
        <input class="chckbx" type="checkbox" {if $variant.variant_published eq 1 || $variant.variant_id eq ""}checked="checked" {/if} onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="variant_published_{$variantno}">
      </div>
    </div>











    

    <div class="row form-group">
      <label class="col-sm-3 control-label" for="variant_order_{$variantno}">Order</label>
      <div class="col-sm-4">
        <input class="form-control number" type="text" value="{if $variant.variant_order neq ''}{$variant.variant_order}{else}999{/if}" name="field[{$variantno*10+1}][tbl_variant][{$variantno}][variant_order]" id="variant_order_{$variantno}">
      </div>
    </div>
  </div>
</div>

