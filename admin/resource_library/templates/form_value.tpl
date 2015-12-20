<div id="attr_value_wrapper{$attributeno*20+$attrvalueno}" class="attribute-attr_values attribute_attr_values{$attributeno}" rel="{$attributeno*20+$attrvalueno}" fieldno="{$attributeno*20+1}">
	<div class="row attribute-attr_values-title">
		<div class="col-sm-offset-1  col-sm-7">
			<fieldset>
				<legend style="font-size:17px;"><div id="attr_value_name_{$attributeno*20+$attrvalueno}_preview">{if $attr_value.attr_value_name} {$attr_value.attr_value_name}{else} Attr_value #{$attrvalueno}{/if}</div></legend>
			</fieldset>
		</div>
		<div class="col-sm-2">
			<a href="javascript:void(0);" class="btn btn-warning trainer-btn" onclick="toggleAttr_value('{$attributeno*20+$attrvalueno}')">Show / Hide</a>
		</div>																			
		<div class="col-sm-2">
			<a href="javascript:void(0);" class="btn btn-danger del-btn" onclick="deleteAttr_value('attr_value_wrapper{$attributeno*20+$attrvalueno}')">Delete</a>
		</div>
	</div>
	<div class="row attr_values" id="attr_value{$attributeno*20+$attrvalueno}">
		<input type="hidden" value="attribute_id" name="default[attr_value_attribute_id]" />
		<input type="hidden" value="{$attr_value.attr_value_id}" name="field[{$attributeno*20+1}][tbl_attr_value][{$attributeno*20+$attrvalueno}][attr_value_id]" id="attr_value_id" class="key" /> 
		<input type="hidden" value="attr_value_id" name="field[{$attributeno*20+1}][tbl_attr_value][{$attributeno*20+$attrvalueno}][id]" id="id" />
		<input type="hidden" value="{$attr_value.attr_value_attribute_id}" name="field[{$attributeno*20+1}][tbl_attr_value][{$attributeno*20+$attrvalueno}][attr_value_attribute_id]" id="attr_value_attribute_id" class="key" />  
		<div class="row form-group">
			<label class="col-sm-3 control-label" for="attr_value_name">Name</label>
			<div class="col-sm-4">
				<input class="form-control" type="text" value="{$attr_value.attr_value_name}" name="field[{$attributeno*20+1}][tbl_attr_value][{$attributeno*20+$attrvalueno}][attr_value_name]" id="attr_value_name" onchange="$('#attr_value_name_{$attributeno*20+$attrvalueno}_preview').html(this.value);">
			</div>
		</div>
		<div class="row form-group">
			<label class="col-sm-3 control-label" for="attr_value_order">Order</label>
			<div class="col-sm-4">
				<input class="form-control number" type="text" value="{$attr_value.attr_value_order}" name="field[{$attributeno*20+1}][tbl_attr_value][{$attributeno*20+$attrvalueno}][attr_value_order]" id="attr_value_order">
			</div>
		</div>
		<!-- <div class="row form-group">
			<label class="col-sm-3 control-label" for="attr_value_instock">In Stock</label>
			<div class="col-sm-7">
				<input type="hidden" value="{if $attr_value.attr_value_instock eq 1}1{else}0{/if}" name="field[{$attributeno*20+1}][tbl_attr_value][{$attributeno*20+$attrvalueno}][attr_value_instock]" class="value"> 
				<input type="checkbox" {if $attr_value.attr_value_scratch eq 1}checked="checked"{/if} onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="attr_value_instock">
			</div>
		</div> -->
		<div class="row form-group">
			<label class="col-sm-3 control-label" for="attr_value_price">Price ($)</label>
			<div class="col-sm-7">
				<input class="form-control double modifier" type="text" value="{$attr_value.attr_value_price}" name="field[{$attributeno*20+1}][tbl_attr_value][{$attributeno*20+$attrvalueno}][attr_value_price]" id="attr_value_price{$attributeno*20+$attrvalueno}" modify="price" onchange="refreshResult('attr_value_price{$attributeno*20+$attrvalueno}');">
				<span class="form-help-text">Please use +/- to change the pricing: Product price for this attribute is</span>
				<span class="form-help-value"></span>
			</div>
		</div>
		<div class="row form-group">
			<label class="col-sm-3 control-label" for="attr_value_specialprice">Special Price ($)</label>
			<div class="col-sm-7">
				<input class="form-control double modifier" type="text" value="{$attr_value.attr_value_specialprice}" name="field[{$attributeno*20+1}][tbl_attr_value][{$attributeno*20+$attrvalueno}][attr_value_specialprice]" id="attr_value_specialprice{$attributeno*20+$attrvalueno}" modify="specialprice" onchange="refreshResult('attr_value_specialprice{$attributeno*20+$attrvalueno}');">
				<span class="form-help-text">Please use +/- to change the pricing: Product special price for this attribute is</span>
				<span class="form-help-value"></span>
			</div>
		</div>
		<div class="row form-group">
			<label class="col-sm-3 control-label" for="attr_value_price">Weight (Kg)</label>
			<div class="col-sm-7">
				<input class="form-control double" type="text" value="{$attr_value.attr_value_weight}" name="field[{$attributeno*20+1}][tbl_attr_value][{$attributeno*20+$attrvalueno}][attr_value_weight]" id="attr_value_weight{$attributeno*20+$attrvalueno}" modify="weight">
				<!-- <span class="form-help-value"></span> -->
			</div>
		</div>
		<div class="row form-group">
			<label class="col-sm-3 control-label" for="attr_value_width">Width (cm)</label>
			<div class="col-sm-7">
				<input class="form-control double" type="text" value="{$attr_value.attr_value_width}" name="field[{$attributeno*20+1}][tbl_attr_value][{$attributeno*20+$attrvalueno}][attr_value_width]" id="attr_value_width{$attributeno*20+$attrvalueno}" modify="width">
				<!-- <span class="form-help-value"></span> -->
			</div>
		</div>
		<div class="row form-group">
			<label class="col-sm-3 control-label" for="attr_value_height">Height (cm)</label>
			<div class="col-sm-7">
				<input class="form-control double" type="text" value="{$attr_value.attr_value_height}" name="field[{$attributeno*20+1}][tbl_attr_value][{$attributeno*20+$attrvalueno}][attr_value_height]" id="attr_value_height{$attributeno*20+$attrvalueno}" modify="height">
				<!-- <span class="form-help-value"></span> -->
			</div>
		</div>
		<div class="row form-group">
			<label class="col-sm-3 control-label" for="attr_value_length">Length (cm)</label>
			<div class="col-sm-7">
				<input class="form-control double" type="text" value="{$attr_value.attr_value_length}" name="field[{$attributeno*20+1}][tbl_attr_value][{$attributeno*20+$attrvalueno}][attr_value_length]" id="attr_value_length{$attributeno*20+$attrvalueno}" modify="length">
				<!-- <span class="form-help-value"></span> -->
			</div>
		</div>
	</div>
</div>

