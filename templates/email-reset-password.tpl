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
<p>Dear {$user_gname}</p>
<p>You are receiving this email, because you or someone who needs access to your account has submitted a Forgotten Password request on our website.</p>
<p>Your new password is: <b>{$newPass}</b></p>
<p>Kind regards,</p>
<br>
<hr>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td width="30%"><img src="{$DOMAIN}/images/logo.png" alt="logo"></td>
		<td width="70%"><b>{$COMPANY.name}</b>
			<br>{$COMPANY.address.street} {$COMPANY.address.suburb} {$COMPANY.address.state} {$COMPANY.address.postcode}
			{if $COMPANY.phone}<br>Ph: <a href="tel:{$COMPANY.phone}">{$COMPANY.phone}</a>{/if}
			{if $COMPANY.fax}<br>Fax: {$COMPANY.fax}{/if}
		</td>
	</tr>
</table>
</body>
</html>