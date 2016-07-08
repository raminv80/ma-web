<!DOCTYPE html>
<html lang="en">
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
<meta name="format-detection" content="telephone=no">
</head>
<body>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tbody>
		<tr>
		<td>
		Hi {$user_gname}<br><br>
		
		{if $message}{$message}<br><br>{/if}
		
		Click the link below to change your password. This link can only be used once and will expire in 4hrs. <br>
		{$DOMAIN}/password-recovery?token={$token}<br><br><br>
		
		If you have any questions about your account, or did not submit this request, please call our friendly support team on <a href="tel:{$COMPANY.phone}" class="tel">{$COMPANY.phone}</a>.<br><br>
		Kind regards<br><br>
		</td>
		</tr>
	</tbody>
</table>
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
</body>
</html>
