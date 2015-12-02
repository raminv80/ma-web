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
}</style>
</head>
<body>
<p>Hi {$user_gname}</p>
<p>Thank you for registering with <a href="{$DOMAIN}">{$DOMAIN}</a>.</p>
<p>Your new password is: <b>{$password}</b></p>

<p>If you have any questions about your account or did not submit this request please contact Customer Service on <a href="tel:{$COMPANY.phone}">{$COMPANY.phone}</a>.</p>
<p>Kind regards</p>
<br>
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
</body>
</html>