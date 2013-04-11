{block name=body}
	<form id="Edit_Record" accept-charset="UTF-8" action="/admin/includes/processes/processes-record.php" method="post">
	<div class="grid_12 right">
	<p>Edit Record</p>
	{foreach key=table item=rec from=$fields}
		{foreach key=id item=item from=$rec}
			<div class="grid_4 alpha omega left">{$item.title}</div>
			<div class="grid_8 alpha omega right"><input type="text" id="id_{$id}" name="field[{$table}][{$id}]" value="{$item.value}" /></div>
		{/foreach}
	{/foreach}
	<input type="hidden" name="formToken" id="formToken" value="{$token}" />
	<input type="submit" value="submit" />
	</div>
	
	</form>
{/block}