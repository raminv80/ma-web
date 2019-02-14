{block name=body} 
{* Define the function *} 
{function name=render_list level=0} 
	{foreach $items as $item}
		<tr>
			<td><b>{$item.wishlist_user_id}</b></td>
			<td><a href="/admin/wish-list/products/{$item.wishlist_user_id}" class="btn btn-small btn-info pull-right"><span class="glyphicon glyphicon-eye-open"></span> View</a> 
			</td>
		</tr>
	{/foreach} 
{/function}
<div class="row ">
	<table class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th>{$zone|upper}</th>
                <th><a href="/admin/includes/processes/get-wish-list-csv.php" target="_blank" class="btn btn-small btn-success pull-right"><span class="glyphicon glyphicon-download"></span> Download CSV</a></th>
			</tr>
		</thead>
		<tbody>{call name=render_list items=$list}
		</tbody>
	</table>
</div>
{/block}
