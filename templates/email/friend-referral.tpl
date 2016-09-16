<html>
<head>
<style>
body {
	font-family: calibri, Helvetica, sans-serif;
	font-size: 16px;
	line-height: 18px;
}
table{
	font-size: 13px;
}
.container {
	padding: 25px;
}
</style>
</head>
<body>
<div class="container">
<div class="title"><h2>Benefits of membership</h2></div>
<div class="">
<p>MedicAlert Foundation offers more than medical identification jewellery. When you purchase a genuine MedicAlert product, you’re also investing in a vital MedicAlert membership. Together with your jewellery, this provides you with complete protection in a medical emergency. By becoming a member, you’ll have peace of mind knowing you’re taking control of your health.</p>
</div>
</div>
<div class="container footer">
<hr>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td width="30%"><img src="{$DOMAIN}/images/{$COMPANY.logo}" alt="logo"></td>
		<td width="10%">&nbsp;</td>
		<td width="60%"><b>{$COMPANY.name}</b>
			<br>{$COMPANY.address.street} {$COMPANY.address.suburb} {$COMPANY.address.state} {$COMPANY.address.postcode}
			{if $COMPANY.phone}<br>Ph: <a href="tel:{$COMPANY.phone}">{$COMPANY.phone}</a>{/if}
			{if $COMPANY.fax}<br>Fax: {$COMPANY.fax}{/if}
		</td>
	</tr>
</table>
</div>
</body>
</html>

