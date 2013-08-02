{block name=body}<!-- fredys comment -->
<div class="row-fluid">
	<div class="span12">
	<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" action="/admin/includes/processes/processes-record.php" method="post">
		<div class="row-fluid">
			<div class="span12">
            	<fieldset>
                <legend>
				{if $fields.category_id neq ""}Edit{else}New{/if} Category
				{if $cnt eq ""}{assign var=cnt value=0}{/if}
                </legend>
                </fieldset>

				<input type="hidden" value="{$fields.category_id}" name="field[tbl_category][{$cnt}][category_id]" id="category_id" />
				<input type="hidden" value="category_id" name="field[tbl_category][{$cnt}][id]" id="id" />
				<input type="hidden" value="2" name="field[tbl_category][{$cnt}][category_type_id]" id="category_type_id" />
			</div>
		</div>
		 <div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="category_name">Name</label></div>
			<div class="span9 controls"><input type="text" value="{$fields.category_name}" name="field[tbl_category][{$cnt}][category_name]" id="category_name" ></div>
		</div>
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="category_listing_id">Primary Page</label></div>
			<div class="span9 controls">
				<select name="field[tbl_category][{$cnt}][category_listing_id]" id="category_listing_id">
				<option value="0">Select one</option>
						{foreach $fields.options.category_listing_id as $opt}
									<option value="{$opt.id}" {if $fields.category_listing_id eq $opt.id}selected="selected"{/if}>{$opt.value}  </option>
						{/foreach}
				</select>
			</div>
		</div>
		<div class="row-fluid control-group">
			<div class="span3"><label class="control-label" for="category_parent_id">Parent Category</label></div>
			<div class="span9 controls">
				<select name="field[tbl_category][{$cnt}][category_parent_id]" id="category_parent_id">
				<option value="0">Select one</option>
						{foreach $fields.options.category_parent_id as $opt}
									<option value="{$opt.id}" {if $fields.category_parent_id eq $opt.id}selected="selected"{/if}>{$opt.value}  </option>
						{/foreach}
				</select>
			</div>
		</div>
		<div class="row-fluid control-group">
			<div class="span9 offset3">
			</div>

            <div class="form-actions">
                <button class="btn btn-primary" onClick="validate()" type="submit">Submit</button>
            </div>

			<input type="hidden" name="formToken" id="formToken" value="{$token}" />
			<input type="hidden" value="{$type}" name="field[tbl_slide][{$cnt}][slide_type_id]"  />
		</div>
	</form>
	</div>
</div>
{/block}