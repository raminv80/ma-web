{block name=body}
{* Define the function *} {function name=render_categories level=0} 
	{foreach $items as $item}
		<tr>
			<td>{for $var=1 to $level}- {/for}<a href='#{$item.id}'><b>{$item.value}</b></a></td>
			</td>
		</tr>
		{if count($item.subs) > 0} {call name=render_categories items=$item.subs level=$level+1} {/if} 
	{/foreach} 
{/function}

<div class="row">
	<div class="col-md-6 col-md-offset-1">
	<h2>Categories List</h2>
		<table class="table table-bordered table-striped table-hover">
			<thead>
			</thead>
			<tbody>{call name=render_categories items=$categories}
			</tbody>
		</table>
	</div>
</div>
{/block}
