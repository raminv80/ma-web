		<div id="attribute_wrapper{$attributeno}" rel="{$attributeno}" fieldno="{$attributeno*20}" class="form-attribute">
			<div class="row-fluid form-attribute-title" >
				<div class="span8">
					<fieldset>
						<legend>{if $attribute.attribute_name}{$attribute.attribute_name}{else}Attribute #{$attributeno}{/if}</legend>
					</fieldset>
				</div>
				<div class="span2">
					<a href="javascript:void(0);" class="btn btn-warning trainer-btn" onclick="toggleAttribute('{$attributeno}')">Show / Hide</a>
				</div>
				<div class="span2">
					<a href="javascript:void(0);" class="btn btn-danger del-btn" onclick="deleteAttribute('{$attributeno}')">Delete</a>
				</div>
			</div>
			<div class="row-fluid attributes" id="attribute{$attributeno}">
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="attribute_name">Name</label>
						<input type="hidden" value="product_id" name="default[attribute_product_id]" />
						<input type="hidden" value="{$attribute.attribute_id}" name="field[{$attributeno*20}][tbl_attribute][{$attributeno}][attribute_id]" id="attribute_id" />
						<input type="hidden" value="attribute_id" name="field[{$attributeno*20}][tbl_attribute][{$attributeno}][id]" id="id" /> 
						<input type="hidden" value="{$attribute.attribute_product_id}" name="field[{$attributeno*20}][tbl_attribute][{$attributeno}][attribute_product_id]" id="attribute_product_id" />  
					</div>
					<div class="span9 controls">
						<input type="text" value="{$attribute.attribute_name}" name="field[{$attributeno*20}][tbl_attribute][{$attributeno}][attribute_name]" id="attribute_name">
					</div>
				</div>
				<div class="row-fluid control-group">
					<div class="span3">
						<label class="control-label" for="attribute_order">Order</label>
					</div>
					<div class="span9 controls">
						<input type="text" value="{$attribute.attribute_order}" name="field[{$attributeno*20}][tbl_attribute][{$attributeno}][attribute_order]" id="attribute_order" class="number">
					</div>
				</div>
				<div class="row-fluid control-group offset1 span10 sub-form">
					<div class="span8 controls">
						<fieldset>
							<legend> Values </legend>
						</fieldset>
					</div>
					<div class="span4 controls">
						<a href="javascript:void(0);" class="btn del-btn" onclick="$('.attr_values').hide();newAttr_value({$attributeno});">Add New Attribute Value</a>
					</div>
				
					<div id="attr_value-wrapper{$attributeno}">
						{assign var='attrvalueno' value=0}
						{foreach $attribute.attr_value as $attr_value}
							{assign var='attrvalueno' value=$attrvalueno+1}
							{include file='form_value.tpl'}
						{/foreach}
						<input type="hidden" value="{$attrvalueno}" id="attr_valueno{$attributeno}">
					</div>
				</div>
			</div>
		</div>