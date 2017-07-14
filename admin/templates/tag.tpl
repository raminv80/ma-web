{block name=tags}
<div id="tag_wrapper{$tagno}" rel="{$tagno}" class="sub-form">
	<div class="row" id="tag{$tagno}">
		<input type="hidden" value="{$default}" name="default[tag_object_id]" />
		<input type="hidden" value="tag_id" name="field[15][tbl_tag][{$tagno}][id]" id="id" />
		<input type="hidden" value="{$tag.tag_id}" name="field[15][tbl_tag][{$tagno}][tag_id]" >
		<input type="hidden" value="{$table_name}" name="field[15][tbl_tag][{$tagno}][tag_object_table]" id="tag_object_table">
		<input type="hidden" value="{$tag.tag_object_id}" name="field[15][tbl_tag][{$tagno}][tag_object_id]" id="tag_object_id" >
		<input type="hidden" value="{$tag.tag_value}" name="field[15][tbl_tag][{$tagno}][tag_value]" id="tag_value">
		<div class="col-sm-offset-2">
			<span><a href="javascript:void(0);" class="" style="color:#F01010;" onclick="deleteTag('tag_wrapper{$tagno}')" title="Delete">x </a></span>
			<span>{$tag.tag_value}</span>
		</div>
		
	</div>
</div>
{/block}