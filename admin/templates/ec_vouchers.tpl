{if $list}
	{foreach $list as $item}
		<tr class="voucher{if $item.discount_published neq 1} draft{/if}" id="vid-{$item.discount_id}">
			<td>{$item.discount_user_id}</td>
			<td>{$item.discount_code}</td>
			<td>{$item.discount_start_date|date_format:"%d/%m/%Y"}</td>
      <td>{$item.discount_end_date|date_format:"%d/%m/%Y"}</td>
			<td>{$item.discount_used}</td>
      <td>{if $item.discount_published eq 1}Active{else}Inactive{/if}</td>
		</tr>
	{/foreach}
	<tr>
		<td colspan="4" class="no-orders" style="display:none;"><b>No vouchers were found.</b></td>
	</tr>
{else}
	<tr>
		<td colspan="4" class="no-orders"><b>No vouchers were found.</b></td>
	</tr>
{/if}
