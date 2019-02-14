{block name=body} 
{* Define the function *} 
{function name=render_list level=0} 
	{foreach $items as $item}
		{if $item.url}
		<tr>
		  <td>{$item.contact_created|date_format:"%d/%m/%Y"}</td>
		  <td>{$item.title}</td>
		  <td><a href="tel:{$item.fields.contact_phone}" title="Click to call">{$item.contact_phone}</a></td>
			<td>{if $item.url} <a href='{$item.url}' class='btn btn-small btn-warning pull-right'>View</a> {/if}
			</td>
			<td>{if $item.url_delete && $admin.level eq 1 } <a href='{$item.url_delete}' onclick="return ConfirmDelete();" class='btn btn-small btn-danger pull-right'>Delete</a> {/if}
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
				<th colspan="5">{$zone|upper} </th>
			</tr>
			<tr>
				<th>Date</th>
				<th>Name</th>
				<th>Phone</th>
				<th colspan="2"> </th>
			</tr>
		</thead>
		<tbody>{call name=render_list items=$list}
		</tbody>
	</table>
</div>
{/block}
