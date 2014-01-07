{block name=body} {* Define the function *} {function name=render_list level=0} {foreach $items as $item}
<tr>
	<td>{for $var=1 to $level}- {/for}<b>{$item.title}</b></td>
	<td>{if $item.url} <a href='{$item.url}' class='btn btn-small btn-warning pull-right'><b><i class="icon-pencil icon-white"></i> Edit</b></a> {/if}
	</td>
	<td>{if $item.url_delete} <a href='{$item.url_delete}' onclick="return ConfirmDelete();" class='btn btn-small btn-danger pull-right'><b><i class="icon-trash icon-white"></i> Delete</b></a> {/if}
	</td>
</tr>
{if count($item.subs) > 0} {call name=render_list items=$item.subs level=$level+1} {/if} {/foreach} {/function}

<div class="row-fluid ">
	<table class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th colspan="3">{$zone|upper} <a href="/admin/edit/{$path}" class='btn btn-small btn-success pull-right'><i class="icon-plus icon-white"></i> Add New</a></th>
			</tr>
		</thead>
		<tbody>{call name=render_list items=$list}
		</tbody>
	</table>
</div>
{/block}
