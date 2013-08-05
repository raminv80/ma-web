<!DOCTYPE html>
<html>
<head>
	<meta name="description" content="{$listing_meta_description}" />
	<meta name="keywords" content="{$listing_meta_words}" />
	<meta http-equiv="kontent-type" content="text/html;charset=UTF-8" />
	<meta name="distribution" content="Global" />
	<meta name="author" content="Them Advertising" />
	{if $staging}
	<meta name="robots" content="noindex,nofollow" />
	{else}
	<meta name="robots" content="index,follow" />
	{/if}
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3, minimum-scale=1, user-scalable=yes">
	<title>{$category_name|replace:'and':'&'|ucfirst}  {$product_name|ucfirst} {$listing_seo_title}</title>
	<!-- <link href="/images/template/favicon.ico" type="image/x-icon" rel="shortcut icon"> -->

	<!-- Bootstrap -->
    <link href="/includes/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="/includes/css/custom.css" rel="stylesheet" media="screen">
    <link href='http://fonts.googleapis.com/css?family=Oxygen:400,700' rel='stylesheet' type='text/css'>
    
    <script src="/includes/js/jquery-1.9.1.min.js"></script>
    <script src="/includes/js/bootstrap.min.js"></script>
    <script src="/includes/js/custom.js"></script>

	<script type="text/javascript">var switchTo5x=true;</script>
	<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
	<script type="text/javascript">stLight.options({ publisher: "ur-a4a1aae0-5741-1e4a-2a36-dd37824dcca", doNotHash: false, doNotCopy: false, hashAddressBar: false });</script>

</head>
<body>
	<div id="top"></div>
	{block name=body}{/block}
</body>
</html>