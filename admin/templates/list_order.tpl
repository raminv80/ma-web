{block name=body} {* Define the function *} {function name=render_list level=0} {foreach $items as $item}
<tr>
	<td>{for $var=1 to $level}- {/for}<b>{$item.title|date_format:"%e %B %Y - %H:%M:%S"}</b></td>
	<td>{if $item.url} <a href='{$item.url}' class='btn btn-small btn-warning pull-right'><b>Edit</b></a> {/if}
	</td>
</tr>
{if count($item.subs) > 0} {call name=render_list items=$item.subs level=$level+1} {/if} {/foreach} {/function}

<div class="row">
	<table class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th colspan="3">{$zone|upper}</th>
			</tr>
		</thead>
		<tbody>{call name=render_list items=$list}
		</tbody>
	</table>
</div>
{/block}
