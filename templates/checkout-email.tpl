<html>
<head>
<style>
body {
	font-family: calibri, Helvetica, sans-serif;
	font-size: 13px;
	line-height: 18px;
}

table {
	width: 600px;
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
</head>
<body>
	<img title="Funk Coffee + Food"
		src="http://www.funkcoffeefood.com.au/images/template/funk-coffee-logo.png" />
	<br>
	<br>
	<table>
		<tr align="left">
			<td>Dear {$customer.name}</td>
		</tr>
		<tr align="left">
			<td>Thank you for your order.<br>
			<br>
			</td>
		</tr>
	</table>
	<table width="600px" cellspacing="0" cellpadding="0" border="0">
		<tr align="left">
			<th colspan="2">Invoice Details</th>
		</tr>
		<tr align="left">
			<td width="50%">Order Number:</td>
			<td>{$ordernumber}</td>
		</tr>
		<tr align="left">
			<td>Order Date:</td>
			<td>{$smarty.now|date_format:'%d-%m-%Y'}</td>
		</tr>
	</table>
	<br />&nbsp;
	<br />

	<table cellpadding="0" cellspacing="0" border="0" width="600px">
		<tr align="left">
			<th colspan="2">Your Details</th>
		</tr>
		<tr align="left">
			<td>Name:</td>
			<td>{$customer.name}</td>
		</tr>
		<tr align="left">
			<td>Email:</td>
			<td>{$customer.email}</td>
		</tr>
		<tr align="left">
			<td width="50%">Company:</td>
			<td>{$customer.company}</td>
		</tr>
		<tr align="left">
			<td width="50%">Telephone(Office):</td>
			<td>{$customer.tel_office}</td>
		</tr>
		<tr align="left">
			<td width="50%">Telephone(Mobile):</td>
			<td>{$customer.tel_mobile}</td>
		</tr>
		<tr align="left">
			<td width="50%">Notes:</td>
			<td>{$customer.notes}</td>
		</tr>
		<tr align="left">
			<td width="50%">Method:</td>
			<td>{$customer.method}</td>
		</tr>
		<tr align="left">
			<td width="50%">Address:</td>
			<td>{$customer.address}</td>
		</tr>
		<tr align="left">
			<td width="50%">Store:</td>
			<td>{$customer.store}</td>
		</tr>
		<tr align="left">
			<td width="50%">Delivery Time:</td>
			<td>{$customer.time} {$customer.date}</td>
		</tr>
	</table>
	<br />&nbsp;
	<br />
	<table width="600px" cellspacing="0" cellpadding="0" border="0"
		class="shopping-cart-table">

		<tr align="left">
			<th align="left">Items</th>
			<th>Option</th>
			<th>Qty</th>
			<th>Price</th>
		</tr>
		{foreach key=key item=cartitem from=$cart}
		<tr valign="top" align="left">
			<td>{$cartitem.name}</td>
			<td>{$cartitem.option}</td>
			<td width="15%">X{$cartitem.quantity}</td>
			<td width="15%">${$cartitem.price}</td>
		</tr>
		{/foreach}
		<tr  valign="top"  align="left">
	            	<td colspan="4"><hr ></td>
	    </tr>
		<tr valign="top" align="left">
			<td><strong>Total (Inc GST):</strong></td>
	        <td>&nbsp;</td>
	        <td>&nbsp;</td>
	        <td><strong>$ {$carttotal}</strong></td>
		</tr>
	</table>
	<br />&nbsp;
	<br />
	<table>
		<tr align="left">
			<td colspan="2">Best regards<br>
			<br> The Team @ Funk Coffee<br>
			<br></td>
		</tr>
	</table>
</body>
</html>
