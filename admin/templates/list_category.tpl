{block name=body}

{* Define the function *}
{function name=render_list level=0}
	{foreach $items as $item}
	<tr>
        <td>{for $var=1 to $level}- {/for}<b>{$item.title}</b></td>
        <td>
        	<a href='{$item.url}' class='btn btn-small btn-warning right'><b><i class="icon-pencil icon-white"></i> Edit</b></a>
        	</td>
        	<td>
        	<a href='{$item.url_delete}'  onclick="return ConfirmDelete();" class='btn btn-small btn-danger right'><b><i class="icon-trash icon-white"></i> Delete</b></a>
        </td>
       </tr>
	{if count($item.subs) > 0}
		{call name=render_list items=$item.subs level=$level+1}
	{/if}
	{/foreach}
{/function}

<div class="row-fluid ">
	<div class="span12">
		<table class="table table-bordered table-striped table-hover">
			    <thead>
			      <tr>
			        <th colspan="3" >PAGE CATEGORIES
					<a href="/admin/edit/{$path}" class='btn btn-small btn-success right'><i class="icon-plus icon-white"></i> ADD NEW</a></th>
			      </tr>
			    </thead>
			    <tbody>
			    {call name=render_list items=$list}
			    </tbody>
			  </table>
	</div>
</div>
{/block}