<!DOCTYPE html>
<html lang="en">
<head>
<style>
.tel, .tel:hover,.tel:focus { color: #fff; }
</style>
<meta name="format-detection" content="telephone=no">
</head>
<body>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tbody>
		<tr >
			<td><img src="{$DOMAIN}/images/rsgk-logo.png" />
		    </td>
		</tr>
		<tr ><td height="10"></td></tr>
		<tr >
			<td height="2" bgcolor="#b8dff3"></td>
		</tr>
		<tr ><td height="10"></td></tr>
		<tr>
		<td style="font-family: Arial, 'Helvetica Neue', 'Trebuchet MS', sans-serif; font-size:14px; color:#000; line-height:20px;">
		Hi {$user_gname}<br><br>
		
		{$message}<br><br>
		
		Click the link below to change your password. This link can only be used once and will expire in 4hrs. <br>
		{$DOMAIN}/resetpassword?token={$token}<br><br><br>
		
		If you have any questions about your account, or did not submit this request, please call our friendly support team on 1300 766 892 between 9.00 am and 4.00 pm (AEST).<br><br>
		Kind regards<br>
		The team at Ready Steady Go Kids<br><br>
		</td>
		</tr>
		<tr><td height="10"></td></tr>
		<tr><td height="10" bgcolor="#3a94d7"></td></tr>
		<tr bgcolor="#3a94d7">
		<td style="font-family: Arial, 'Helvetica Neue', 'Trebuchet MS', sans-serif; font-size:14px; color:#fff; line-height:20px; padding-left:15px; padding-right:15px;">
			{if $COMPANY.phone}P: <a href="tel:{$COMPANY.phone}" class="tel">{$COMPANY.phone}</a>{/if}
			{if $COMPANY.fax}| F: {$COMPANY.fax}{/if}<br>
		  {$COMPANY.address.street} {$COMPANY.address.suburb} {$COMPANY.address.state} {$COMPANY.address.postcode}<br>
		  <a href="{$DOMAIN}" style="color:#fff; text-decoration:none;" title="Visit our website">readysteadygokids.com.au</a></td>
		</tr>
		<tr><td height="10" bgcolor="#3a94d7"></td></tr>
	</tbody>
</table>
</body>
</html>