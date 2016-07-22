<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">
<head>
<meta name="description" content="{$product_meta_description} {$listing_meta_description}" />
<meta name="keywords" content="{$product_meta_words} {$listing_meta_words}" />
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="distribution" content="Global" />
<meta name="author" content="{if $COMPANY.name}{$COMPANY.name}{else}Them Advertising{/if}" />
{if $staging || $listing_noindex eq 1}
<meta name="robots" content="noindex,nofollow" />
{else}
<meta name="robots" content="index,follow" />
{/if}
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">

<title>{$product_name|ucfirst}{$listing_seo_title}</title>
<link href="/images/favicon.png" type="image/png" rel="shortcut icon">

<!-- for Facebook -->
<meta property="og:title" content="{if $listing_share_title || $product_share_title}{$listing_share_title}{$product_share_title}{else}{$product_name|ucfirst}{$listing_seo_title}{/if}" />
<meta property="og:type" content="article" />
<meta property="og:image" content="{$DOMAIN}{if $listing_share_image || $product_share_image}{$listing_share_image}{$product_share_image}{elseif $gallery && $gallery.0.gallery_link}{$gallery.0.gallery_link}{elseif $listing_image}{$listing_image}{else}/images/logo.png{/if}"/>
<meta property="og:url" content="{$DOMAIN}{$REQUEST_URI}" />
<meta property="og:description" content="{if $listing_share_text || $product_share_text}{$listing_share_text}{$product_share_text}{else}{$product_meta_description}{$listing_meta_description}{/if}" />

<!-- for Twitter -->
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="">
<meta name="twitter:title" content="{if $listing_share_title || $product_share_title}{$listing_share_title}{$product_share_title}{else}{$product_name|ucfirst}{$listing_seo_title}{/if}" />
<meta name="twitter:description" content="{if $listing_share_text || $product_share_text}{$listing_share_text}{$product_share_text}{else}{$product_meta_description}{$listing_meta_description}{/if}" />
<meta name="twitter:creator" content="@THEMAdvertising">
<meta name="twitter:image:src" content="{$DOMAIN}{if $listing_share_image || $product_share_image}{$listing_share_image}{$product_share_image}{elseif $gallery && $gallery.0.gallery_link}{$gallery.0.gallery_link}{elseif $listing_image}{$listing_image}{else}/images/logo.png{/if}"/>
<meta name="twitter:domain" content="{$DOMAIN}">

<!-- Schema.org -->
<meta itemprop="name" content="{$product_name|ucfirst}{$listing_seo_title}">
<meta itemprop="description" content="{$product_meta_description} {$listing_meta_description}">
<meta itemprop="image" content="{$DOMAIN}/images/logo.png">

<!-- Bootstrap -->
<link href="/includes/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="/includes/css/cart.css" rel="stylesheet" media="screen">
<link href="/includes/css/bootstrap-touch-carousel.css" rel="stylesheet" media="screen">
<link href="/includes/css/custom.css" rel="stylesheet" media="screen">
<link href="/includes/css/resp.css" rel="stylesheet" media="screen">

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script type="text/javascript" src="/includes/js/html5shiv.js"></script>
      <script type="text/javascript" src="/includes/js/respond.min.js"></script>
      <script type="text/javascript" src="/includes/js/modernizr-2.6.2-respond-1.1.0.min.js"></script>
 <![endif]-->
{block name=head}{/block}

{if $ga_id}
	<script>
	(function(i,s,o,g,r,a,m){ i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	ga('create', '{$ga_id}', 'auto', { 'siteSpeedSampleRate': 50 });
	ga('require', 'displayfeatures');
	ga('require', 'linkid', 'linkid.js');
	ga('require', 'ec');
	ga('set', '&cu', 'AUD');

	{$ga_ec}
	{block name=enhanced_ecommerce}{/block}

	ga('send', 'pageview');
	</script>
{/if}

</head>
<body>
<header>
  {include file='menu.tpl'}
</header>
	{block name=body}{/block}
	{include file='footer.tpl'}
	<script type="text/javascript" src="/includes/js/jquery-2.1.4.min.js"></script>
	<script type="text/javascript" src="/includes/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/includes/js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="/includes/js/shopping-cart.js"></script>
	<script type="text/javascript" src="/includes/js/bootstrap-touch-carousel.js"></script>
	<script type="text/javascript" src="/includes/js/custom.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){

		$('#searchbox').bind('keyup', function(event) {
		    if(event.keyCode==13){
		    	$('#search-form').submit();
		    }
		});


		$('.dropdown.navbar-right ').hover(function() {
		  $(this).find('.dropdown-menu:hidden').fadeIn(200);
		}, function() {
		  $(this).find('.dropdown-menu:visible').fadeOut(200)
		});

	});




	</script>
	{block name=tail}{/block}


</body>
</html>