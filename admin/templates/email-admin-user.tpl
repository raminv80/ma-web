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
</style>
</head>
<body>

<p>Hi {$name}</p>
<p>Congratulations! Your administration access as a smartLifedirect {$position} is all set up.</p>
<p>You can now manage your parties and see all orders online.</p>
<p>To access your account please visit <a href="{$DOMAIN}/admin">{$DOMAIN}/admin</a> </p>
<p>Your password is: <b>{$password}</b></p>

<p>If you have any questions in regards to using this platform, please do not hesitate to contact me on {if $admin.admin_phone}<a href="tel:{$admin.admin_phone}">{$admin.admin_phone}</a> or {/if}<b>{$admin.admin_email}</b>.</p>
<br>
<p>Kind regards</p>
<p>
	<br><b>{$admin.admin_name} {$admin.admin_surname}</b>
		<br>{$admin.admin_email} 
		<br><a href="tel:{$admin.admin_phone}">{$admin.admin_phone}</a> 
</p>


<br />
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

