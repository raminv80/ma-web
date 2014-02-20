{if list}
	{foreach $list as $item}
		<tr>
			<td><b>{$item.user.0.user_gname} {$item.user.0.user_surname}</b></td>
			<td><b>{$item.title|date_format:"%e %B %Y - %H:%M:%S"}</b></td>
			<td><b>{getvaluename id=$item.payment.0.order.0.order_status_id options=$options.status}</b></td>
			<td>{if $item.url} <a href='{$item.url}' class='btn btn-small btn-warning pull-right'>Edit</a> {/if}
			</td>
		</tr>
	{/foreach}
{else}
	<tr>
		<td colspan="4"><b>No orders were found.</b></td>
	</tr>
{/if}
