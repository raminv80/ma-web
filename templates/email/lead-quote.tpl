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
<div class="title">{if $title}{$title}{else}Lead{/if}</div>
<div><i>Notice: This email may contain confidential information and is not for public distribution.</i></div>
<div class="form">
<ul>
{foreach from=$form key=k item=field}
	<li><b>{$k}:</b>{$field}</li>
{/foreach}
</ul>
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

