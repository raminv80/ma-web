{block name=body}
<div class="row-fluid">
	<div class="span12">
	<form id="Edit_Record" accept-charset="UTF-8" action="/admin/includes/processes/processes-record.php" method="post">
		<div class="row-fluid">
			<div class="span12">
				{if $fields.category_id neq ""}Edit{else}New{/if} Category
				{if $cnt eq ""}{assign var=cnt value=0}{/if}
				<input type="hidden" value="{$fields.category_id}" name="field[tbl_category][{$cnt}][category_id]" id="category_id" />
				<input type="hidden" value="category_id" name="field[tbl_category][{$cnt}][id]" id="id" />
			</div>
		</div>
		<div class="row-fluid">
			<div class="span4">Name</div>
			<div class="span8"><input type="text" value="{$fields.category_name}" name="field[tbl_category][{$cnt}][category_name]" id="category_name" class="req"></div>
		</div>
		<div class="row-fluid">
			<div class="span4">Associate Type</div>
			<div class="span8"><select name="field[tbl_category][{$cnt}][category_type_id]" id="category_type_id">
									{foreach $fields.options.category_type_id as $opt}
									<option value="{$opt.id}" {if $fields.category_level eq $opt.id}selected="selected"{/if}>{$opt.value}</option>
									{/foreach}
								</select>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span4">Associated Listing</div>
			<div class="span8"><select name="field[tbl_category][{$cnt}][category_listing_id]" id="category_listing_id">
								<option value="0" {if $fields.category_level eq "0"}selected="selected"{/if}>NONE</option>
									{foreach $fields.options.category_listing_id as $opt}
									<option value="{$opt.id}" {if $fields.category_level eq $opt.id}selected="selected"{/if}>{$opt.value}</option>
									{/foreach}
								</select>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span4">Parent Category</div>
			<div class="span8"><select name="field[tbl_category][{$cnt}][category_parent_id]" id="category_parent_id">
			<option value="0" {if $fields.category_level eq "0"}selected="selected"{/if}>NONE</option>
									{foreach $fields.options.category_parent_id as $opt}
									<option value="{$opt.id}" {if $fields.category_level eq $opt.id}selected="selected"{/if}>{$opt.value}</option>
									{/foreach}
								</select>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span8 offset4">
				<script type="text/javascript">
				function validate(){
					$('#Edit_Record').submit();
				}
			</script>
			<a href="javascript:void(0);" onClick="validate()">Submit</a></div>
			<input type="hidden" name="formToken" id="formToken" value="{$token}" />
		</div>
	</form>
	</div>
</div>
{/block}