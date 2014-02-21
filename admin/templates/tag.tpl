{block name=tags}
<div id="tag_wrapper{$tagno}" rel="{$tagno}" class="sub-form">
	<div class="row" id="tag{$tagno}">
		<input type="hidden" value="product_id" name="default[tag_product_id]" />
		<input type="hidden" value="tag_id" name="field[15][tbl_tag][{$tagno}][id]" id="id" />
		<input type="hidden" value="{$tags.tag_id}" name="field[15][tbl_tag][{$tagno}][tag_id]" >
		<input type="hidden" value="tbl_product" name="field[15][tbl_tag][{$tagno}][tag_object_table]" id="tag_object_table">
		<input type="hidden" value="{$tags.tag_object_id}" name="field[15][tbl_tag][{$tagno}][tag_object_id]" id="tag_object_id" >
		<input type="hidden" value="{$tags.tag_value}" name="field[15][tbl_tag][{$tagno}][tag_value]" id="tag_value">
		<div class="col-sm-offset-2">
			<div>{$tags.tag_value} <span><a href="javascript:void(0);" class="" style="color:#F01010;" onclick="deleteImage('tag_wrapper{$tagno}')" title="Delete">x</a></span></div>
			
		</div>
	</div>
</div>
{/block}