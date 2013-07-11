{block name=page-list-item}
<tr>
<td>{for $var=1 to $count}- {/for}<b>{$li.title}</b></td>
<td>
	<a href='{$li.url}' class='btn btn-small btn-warning right'><b><i class="icon-pencil icon-white"></i> Edit</b></a>
	</td>
	<td>
	<a href='{$li.url_delete}' class='btn btn-small btn-danger right'  onclick="return ConfirmDelete();"><b><i class="icon-trash icon-white"></i> Delete</b></a>
 </td>
</tr>
{foreach item=li2 from=$list}
{if	$li2.category_id eq $li.id}
{counter}
<tr>
	<td>{for $var=1 to $count}- {/for}<b>{$li2.title}</b></td>
    <td>
        <a href='{$li2.url}' class='btn btn-small btn-warning right'><b><i class="icon-pencil icon-white"></i> Edit</b></a>
	</td>
    <td>
     	<a href='{$li2.url_delete}' class='btn btn-small btn-danger right' onclick="return ConfirmDelete();"><b><i class="icon-trash icon-white"></i> Delete</b></a>
	</td>
</tr>
{counter start=$count+1 skip=1 assign="count"}
{/if}
{/foreach}
{/block}