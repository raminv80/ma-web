{block name=full-product-cat}
{* Define the function *} {function name=render_fullList level=0 parentUrl=''} 
	{foreach from=$items key=k item=item}
		{assign var='type' value=$k|truncate:1:''}
		<tr>
			<td>{for $var=1 to $level}- {/for}<a href='{$parentUrl}{$item.url}'>{if $type eq 'p'}<b>{/if}{$type}+{$item.title}{if $type eq 'p'}</b>{/if}</a></td>
			</td>
		</tr>
		{if count($item.subs) > 0} {call name=render_fullList items=$item.subs level=$level+1 parentUrl=$parentUrl|cat:$item.url} {/if} 
	{/foreach} 
{/function}



	<div class="row">
		<div class="col-md-6 col-md-offset-1">
			<h3>Full List</h3>
			<table class="table table-bordered table-striped table-hover">
				<thead>
				</thead>
				<tbody>{call name=render_fullList items=$productsList}
				</tbody>
			</table>
		</div>
	</div>


{/block}
