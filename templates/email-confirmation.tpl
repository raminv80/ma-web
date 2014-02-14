
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
<br>
<table>
	<tr align="left">
		<td>Dear {$user.gname}</td>
	</tr>
	<tr align="left">
		<td>Thank you for buying with us. Your order will be processed by our team and you will receive another email once your package is shipped. Should you have any queries, please call
			CALL 1800 (Mon - Fri, 9am – 5pm CST).<br>
		<br>
		</td>
	</tr>
</table>
<table cellspacing="0" cellpadding="0" border="0">
	<tr align="left">
		<th colspan="2">Invoice Details</th>
	</tr>
	<tr align="left">
		<td width="50%">Order Number:</td>
		<td>{$order.cart_id}</td>
	</tr>
	<tr align="left">
		<td>Order Date:</td>
		<td>{$order.cart_closed_date|date_format:"%e %B %Y"}</td>
	</tr>
</table>
<br />
&nbsp;
<br />
<table cellpadding="0" cellspacing="0" border="0">
	<tr align="left">
		<th colspan="2">Your Shipping Details</th>
	</tr>
	<tr align="left">
		<td width="50%">Name:</td>
		<td>{$shipping.address_name}</td>
	</tr>
	<tr align="left">
		<td>Address</td>
		<td>{$shipping.address_line1} {if $shipping.address_line2}, {$shipping.address_line2}{/if}</td>
	</tr>
	<tr align="left">
		<td>Suburb:</td>
		<td>{$shipping.address_suburb}</td>
	</tr>
	<tr align="left">
		<td>State:</td>
		<td>{$shipping.address_state}</td>
	</tr>
	<tr align="left">
		<td>Country:</td>
		<td>{$shipping.address_country}</td>
	</tr>
	<tr align="left">
		<td>Postcode:</td>
		<td>{$shipping.address_postcode}</td>
	</tr>
	<tr align="left">
		<td>Phone:</td>
		<td>{$shipping.address_telephone}</td>
	</tr>
	<tr align="left">
		<td>Mobile:</td>
		<td>{$shipping.address_mobile}</td>
	</tr>
</table>
<br />
&nbsp;
<br />
<table cellpadding="0" cellspacing="0" border="0">
	<tr align="left">
		<th colspan="2">Your Billing Details</th>
	</tr>
	<tr align="left">
		<td width="50%">Name:</td>
		<td>{$billing.address_name}</td>
	</tr>
	<tr align="left">
		<td>Address</td>
		<td>{$billing.address_line1} {if $billing.address_line2}, {$billing.address_line2}{/if}</td>
	</tr>
	<tr align="left">
		<td>Suburb:</td>
		<td>{$billing.address_suburb}</td>
	</tr>
	<tr align="left">
		<td>State:</td>
		<td>{$billing.address_state}</td>
	</tr>
	<tr align="left">
		<td>Country:</td>
		<td>{$billing.address_country}</td>
	</tr>
	<tr align="left">
		<td>Postcode:</td>
		<td>{$billing.address_postcode}</td>
	</tr>
	<tr align="left">
		<td>Phone:</td>
		<td>{$billing.address_telephone}</td>
	</tr>
	<tr align="left">
		<td>Mobile:</td>
		<td>{$billing.address_mobile}</td>
	</tr>
</table>
<br />
&nbsp;
<br />
<table cellspacing="0" cellpadding="0" border="0">

	<tr align="left">
		<th align="left">Items</th>
		<th>Qty</th>
		<th>Unit Price</th>
		<th style="text-align: right">Total Price</th>
	</tr>
	{foreach $orderItems as $item}
	<tr valign="top" aling="left">
		<td>{$item.cartitem_product_name} 	
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
	<tr valign="top" align="left">
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><strong>Sub Total</strong></td>
		<td style="text-align: right">$ {$order.cart_subtotal|number_format:2:".":","}</td>
	</tr>
	<tr valign="top" align="left">
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><strong>Discount</strong></td>
		<td style="text-align: right">$ -{$order.cart_discount|number_format:2:".":","}</td>
	</tr>
	<tr valign="top" align="left">
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><strong>Postage & Handling</strong></td>
		<td style="text-align: right">$ {$order.cart_shipping_fee|number_format:2:".":","}</td>
	</tr>
	<tr valign="top" align="left">
		<td colspan="4"><hr></td>
	</tr>
	<tr valign="top" align="left">
		<td><strong>TOTAL</strong></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td style="text-align: right"><strong>$ {$order.cart_total|number_format:2:".":","}</strong></td>
	</tr>
	<tr valign="top" align="left">
		<td colspan="4"><hr></td>
	</tr>
</table>
<br />
&nbsp;
<br />
<table>
	<tr align="left">
		<td>Best regards<br>
		<br> The Team @ <br>
		<br> COMPANY <br> Road<br> SA 5063<br> CALL: 1800 <br> FAX: 1800 <br>
		</td>
	</tr>
</table>