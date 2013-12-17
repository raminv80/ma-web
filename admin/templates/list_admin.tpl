{block name=body}
<div class="row-fluid ">
	<div class="span12">
		<table class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<th colspan="3">ADMINISTRATOR USERS <a href="/admin/edit/{$path}" class='btn btn-small btn-success right'><i class="icon-plus icon-white"></i> ADD NEW</a></th>
				</tr>
			</thead>
			<tbody>
				{foreach item=li from=$list}
				<tr>
					<td><b>{$li.title}</b></td>
					<td><a href='{$li.url}' class='btn btn-small btn-warning right'><b><i class="icon-pencil icon-white"></i> Edit</b></a></td>
					<td><a href='{$li.url_delete}' class='btn btn-small btn-danger right' onclick="return ConfirmDelete();"><b><i class="icon-trash icon-white"></i> Delete</b></a></td>
				</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
</div>
{/block}
