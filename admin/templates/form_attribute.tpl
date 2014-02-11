<div id="attribute_wrapper{$attributeno}" rel="{$attributeno}" fieldno="{$attributeno*20}" class="form-attribute sub-form">
	<div class="row form-attribute-title" >
		<div class="col-sm-8">
			<fieldset>
				<legend>{if $attribute.attribute_name}{$attribute.attribute_name}{else}Attribute #{$attributeno}{/if}</legend>
			</fieldset>
		</div>
		<div class="col-sm-2">
			<a href="javascript:void(0);" class="btn btn-warning trainer-btn" onclick="toggleAttribute('{$attributeno}')">Show / Hide</a>
		</div>
		<div class="col-sm-2">
			<a href="javascript:void(0);" class="btn btn-danger del-btn" onclick="deleteAttribute('{$attributeno}')">Delete</a>
		</div>
	</div>
	<div class="row attributes" id="attribute{$attributeno}">
		<div class="row form-group">
				<label class="col-sm-2 control-label" for="attribute_name">Name</label>
				<input type="hidden" value="product_id" name="default[attribute_product_id]" />
				<input type="hidden" value="{$attribute.attribute_id}" name="field[{$attributeno*20}][tbl_attribute][{$attributeno}][attribute_id]" id="attribute_id" />
				<input type="hidden" value="attribute_id" name="field[{$attributeno*20}][tbl_attribute][{$attributeno}][id]" id="id" /> 
				<input type="hidden" value="{$attribute.attribute_product_id}" name="field[{$attributeno*20}][tbl_attribute][{$attributeno}][attribute_product_id]" id="attribute_product_id" />  
			<div class="col-sm-5">
				<input class="form-control" type="text" value="{$attribute.attribute_name}" name="field[{$attributeno*20}][tbl_attribute][{$attributeno}][attribute_name]" id="attribute_name">
			</div>
		</div>
		<div class="row form-group">
				<label class="col-sm-2 control-label" for="attribute_order">Order</label>
			<div class="col-sm-5">
				<input class="form-control" type="text" value="{$attribute.attribute_order}" name="field[{$attributeno*20}][tbl_attribute][{$attributeno}][attribute_order]" id="attribute_order" class="number">
			</div>
		</div>
		<div class="col-sm-12 sub-form">
			<div class="col-sm-8">
				<fieldset>
					<legend style="font-size:19px;margin-left: 18px;"> Values </legend>
				</fieldset>
			</div>
		
			<div id="attr_value-wrapper{$attributeno}">
				{assign var='attrvalueno' value=0}
				{foreach $attribute.attr_value as $attr_value}
					{assign var='attrvalueno' value=$attrvalueno+1}
					{include file='form_value.tpl'}
				{/foreach}
				<input type="hidden" value="{$attrvalueno}" id="attr_valueno{$attributeno}">
			</div>
			<div class="col-sm-4">
				<a href="javascript:void(0);" class="btn btn-success btn-add-new" onclick="$('.attr_values').hide();newAttr_value({$attributeno});">Add New Value</a>
			</div>
		</div>
	</div>
</div>