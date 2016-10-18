{block name=body} 
{* Define the function *} 
{function name=render_list level=0} 
	{foreach $items as $item}
		{if $item.url}
		<tr {if $item.published eq '0'}class="draft"{/if}>
			<td>{for $var=1 to $level}- {/for}{$item.title} {if $item.published eq '0'}<small>| Inactive</small>{/if}</td>
			<td>{if $item.discount_code}{$item.discount_code}{/if}
			</td>
			<td class="text-center">{if $item.discount_used}{$item.discount_used}{/if}
			</td>
			<td>{if $item.url} <a href='{$item.url}' class='btn btn-small btn-warning pull-right'><span class="glyphicon glyphicon-pencil"></span> Edit</a> {/if}
			</td>
			<td>{if $item.url_delete} <a href='{$item.url_delete}' onclick="return ConfirmDelete();" class='btn btn-small btn-danger pull-right'><span class="glyphicon glyphicon-trash"></span> Delete</a> {/if}
			</td>
		</tr>
		{/if}
		{if count($item.subs) > 0} {call name=render_list items=$item.subs level=$level+1} {/if} 
	{/foreach} 
{/function}

<div class="row ">
	<table class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th colspan="5">{$zone|upper} <a href="/admin/edit/{$path}" class='btn btn-small btn-success pull-right'><span class="glyphicon glyphicon-plus"></span> Add New</a></th>
			</tr>
			<tr>
				<th>Name</th>
				<th>Code</th>
				<th class="text-center">Used</th>
				<th colspan="2"></th>
			</tr>
		</thead>
		<tbody>{call name=render_list items=$list}
		</tbody>
	</table>
</div>
{/block}
