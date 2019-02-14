{if $list}
	{foreach $list as $item}
		<tr class="order {$item.payment.0.order.0.order_status_id}">
			<td><b>{$item.payment.0.payment_transaction_no}</b></td>
			<td><b>{$item.cart_user_id}{* COMMENTED OUT FOR MAF * {$item.user.0.user_gname} {$item.user.0.user_surname} *}</b></td>
			<td><b>{$item.title|date_format:"%e %b %Y - %H:%M:%S"}</b></td>
			{* COMMENTED OUT FOR MAF <td><b>{getvaluename id=$item.payment.0.order.0.order_status_id options=$options.status}</b></td> *}
			<td>{if $item.url} <a href='{$item.url}' class='btn btn-small btn-warning pull-right'><span class="glyphicon glyphicon-pencil"></span> Edit</a> {/if}
			</td>
		</tr>
	{/foreach}
	<tr>
		<td colspan="4" class="no-orders" style="display:none;"><b>No orders were found.</b></td>
	</tr>
{else}
	<tr>
		<td colspan="4" class="no-orders"><b>No orders were found.</b></td>
	</tr>
{/if}
