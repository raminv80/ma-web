{block name=body} 
{* Define the function *} 
{function name=render_list level=0} 
	{foreach $items as $item}
		{if $item.url}
		<tr {if $item.published eq '0'}class="draft"{/if}>
			<td>{for $var=1 to $level}- {/for}<b>{$item.title} </b>{if $item.published eq '0'}<small>| draft</small>{/if}</td>
			<td>{if $item.url} <a href="{$item.url}" class="btn btn-small btn-warning pull-right"><span class="glyphicon glyphicon-pencil"></span> Edit</a> {/if}
			</td>
			<td>{if $item.url_delete} <a href="{$item.url_delete}" onclick="return ConfirmDelete();" class="btn btn-small btn-danger pull-right"><span class="glyphicon glyphicon-trash"></span> Delete</a> {/if}
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
				<th colspan="3">{$zone|upper} <a href="/admin/edit/{$path}" class="btn btn-small btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Add New</a></th>
			</tr>
		</thead>
		<tbody>{call name=render_list items=$list}
		</tbody>
	</table>
</div>
{/block}
