<style>
body {
	font-family: calibri, Helvetica, sans-serif;
	font-size: 13px;
	line-height: 18px;
}

table {
	max-width: 600px;
	width: 100%;
}

table th,table td {
	padding: 3px;
	text-align: left;
}

table th {
	text-align: left;
	background-color: #f3f3f3;
	line-height: 25px;
}

table td {
	text-align: left;
}
</style>
<br>
<table>
	<tr>
		<td>Hi {$order.user_gname}</td>
	</tr>
	<tr>
		<td><br>{$message}<br>
		{if $order.payment_shipping_tracking}The shipping tracking code for this order is: {$order.payment_shipping_tracking} <br>{/if}
		<br>
		</td>
	</tr>
</table>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr bgcolor="#f3f3f3">
		<th colspan="2" align="left">Invoice Details</th>
	</tr>
	<tr>
		<td width="50%">Order Number:</td>
		<td>{$order.payment_transaction_no}</td>
	</tr>
	<tr>
		<td>Order Date:</td>
		<td>{$order.cart_closed_date|date_format:"%e %B %Y"}</td>
	</tr>
</table>
<br />
&nbsp;
<br />
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr bgcolor="#f3f3f3">
		<th colspan="2" align="left">Your shipping details</th>
	</tr>
	<tr>
		<td width="50%">Name:</td>
		<td>{$shipping.address_name}</td>
	</tr>
	<tr>
		<td>Address</td>
		<td>{$shipping.address_line1} {if $shipping.address_line2}, {$shipping.address_line2}{/if}</td>
	</tr>
	<tr>
		<td>Suburb:</td>
		<td>{$shipping.address_suburb}</td>
	</tr>
	<tr>
		<td>State:</td>
		<td>{$shipping.address_state}</td>
	</tr>
	<tr>
		<td>Postcode:</td>
		<td>{$shipping.address_postcode}</td>
	</tr>
	<tr>
		<td>Phone:</td>
		<td>{$shipping.address_telephone}</td>
	</tr>
</table>
<br />
&nbsp;
<br />
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr bgcolor="#f3f3f3">
		<th colspan="2" align="left">Your billing details</th>
	</tr>
	<tr>
		<td width="50%">Name:</td>
		<td>{$billing.address_name}</td>
	</tr>
	<tr>
		<td>Address</td>
		<td>{$billing.address_line1} {if $billing.address_line2}, {$billing.address_line2}{/if}</td>
	</tr>
	<tr>
		<td>Suburb:</td>
		<td>{$billing.address_suburb}</td>
	</tr>
	<tr>
		<td>State:</td>
		<td>{$billing.address_state}</td>
	</tr>
	<tr>
		<td>Postcode:</td>
		<td>{$billing.address_postcode}</td>
	</tr>
	<tr>
		<td>Phone:</td>
		<td>{$billing.address_telephone}</td>
	</tr>
</table>
<br />
&nbsp;
<br />
<table cellspacing="0" cellpadding="0" border="0" width="100%">

	<tr bgcolor="#f3f3f3">
		<th align="left">Items</th>
		<th align="left">Qty</th>
		<th align="left">Unit price</th>
		<th style="text-align: right">Total price</th>
	</tr>
	{foreach $orderItems as $item}
	<tr valign="top" aling="left">
		<td>{if $item.cartitem_product_gst eq '0'} {assign var=free value=1} *{/if}{$item.cartitem_product_name} 
			{if $item.attributes} 
				{foreach $item.attributes as $attr}
					<small>/ {$attr.cartitem_attr_attribute_name}: {$attr.cartitem_attr_attr_value_name}</small>
				{/foreach}
			{/if}
		</td>
		<td width="10%">{$item.cartitem_quantity}</td>
		<td width="20%">$ {$item.cartitem_product_price|number_format:2:".":","}</td>
		<td width="20%" style="text-align: right">$ {$item.cartitem_subtotal|number_format:2:".":","}</td>
	</tr>
	{/foreach}
	<tr valign="top">
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><strong>Sub total</strong></td>
		<td style="text-align: right"><strong>$ {$order.payment_subtotal|number_format:2:".":","}</strong></td>
	</tr>
	{if $order.payment_discount neq '0.00'}
	<tr valign="top">
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><strong>Discount</strong></td>
		<td style="text-align: right"><strong>$ -{$order.payment_discount|number_format:2:".":","}</strong></td>
	</tr>
	{/if}
	<tr valign="top">
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><strong>Shipping</strong></td>
		<td style="text-align: right"><strong>{if $order.payment_shipping_fee eq '0.00'}FREE{else}$ {$order.payment_shipping_fee|number_format:2:".":","}{/if}</strong></td>
	</tr>
	<tr valign="top">
		<td colspan="4"><hr></td>
	</tr>
	<tr valign="top">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Total (excl. GST)</td>
    {assign var='totalExclGST' value=$order.payment_charged_amount - $order.payment_gst}
    <td style="text-align: right">(${$totalExclGST|number_format:2:".":","})</td>
  </tr>
	<tr valign="top">
    <td>{if $free}(*)GST free item.{/if}</td>
    <td>&nbsp;</td>
    <td>GST</td>
    <td style="text-align: right">(${$order.payment_gst|number_format:2:".":","})</td>
  </tr>
	<tr valign="top">
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><strong>TOTAL</strong></td>
		<td style="text-align: right"><strong>$ {$order.payment_charged_amount|number_format:2:".":","}</strong></td>
	</tr>
	<tr valign="top">
		<td colspan="4"><hr></td>
	</tr>
</table>
<br />
&nbsp;
<br />

<hr>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td width="30%"><img src="{$DOMAIN}/images/{$COMPANY.logo}" alt="logo"></td>
		<td width="70%"><b>{$COMPANY.name}</b>
			<br>{$COMPANY.address.street} {$COMPANY.address.suburb} {$COMPANY.address.state} {$COMPANY.address.postcode}
			{if $COMPANY.phone}<br>Ph: <a href="tel:{$COMPANY.phone}">{$COMPANY.phone}</a>{/if}
			{if $COMPANY.fax}<br>Fax: {$COMPANY.fax}{/if}
		</td>
	</tr>
</table>