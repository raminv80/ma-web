<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">
<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="distribution" content="IU" />
<meta name="author" content="Them Advertising" />
<meta name="robots" content="noindex,nofollow" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">

<title>Site Style Guide</title>
<link href="/images/favicon.ico" type="image/x-icon" rel="shortcut icon">

<!-- Bootstrap -->
<link href="/includes/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="/includes/css/styles.scss" rel="stylesheet" media="screen">

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
			<script type="text/javascript" src="/includes/js/modernizr-2.6.2-respond-1.1.0.min.js"></script>
 	<![endif]-->
	<script type="text/javascript" src="/includes/js/jquery-2.1.4.min.js"></script>
	<script type="text/javascript" src="/includes/js/bootstrap.min.js"></script>
</head>
<body>
<div style="width:900px;margin:auto;margin-top:50px;">
	<div style="padding-bottom:50px">
		<select onchange="$('.template').hide();$($(this).val()).show();" style="min-width:200px; font-size:24px; margin:auto;">
		  {foreach $dir as $d}<option value=".{urlencode data=$d}">{$d}</option>{/foreach}
		</select>
	</div>
	<div class="styleguide">
		{foreach $templates as $t}
			<div class="template {urlencode data=$t.dir}" style="display:block">
			 {include file=$t.template}
			</div>
    {/foreach}
	</div>
</div>
</body>
</html>