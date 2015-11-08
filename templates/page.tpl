<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">
<head>
<meta name="description" content="{$product_meta_description} {$listing_meta_description}" />
<meta name="keywords" content="{$product_meta_words} {$listing_meta_words}" />
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="distribution" content="Global" />
<meta name="author" content="Them Advertising" />
{if $staging}
<meta name="robots" content="noindex,nofollow" />
{else}
<meta name="robots" content="index,follow" />
{/if}
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">

<title>{$product_name|ucfirst} {$listing_seo_title}</title>
<link href="/images/favicon.ico" type="image/x-icon" rel="shortcut icon">

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
<meta itemprop="name" content="{$product_name|ucfirst} {$listing_seo_title}">
<meta itemprop="description" content="{$product_meta_description} {$listing_meta_description}">
<meta itemprop="image" content="{$DOMAIN}/images/logo.png">

<!-- Bootstrap -->
<link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
<link href="/includes/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="/includes/css/cart.css" rel="stylesheet" media="screen">
<link href="/includes/css/tipTip.css" rel="stylesheet" media="screen">
<link href="/includes/css/custom.css" rel="stylesheet" media="screen">
<link href='http://fonts.googleapis.com/css?family=Oxygen:400,700' rel='stylesheet' type='text/css'>

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
	<div id="fb-root"></div>
	<header>
		<div id="header1">
			<div id="header1in" class="container">
				{include file='menu.tpl'}
			</div>
		</div>
		<div class="info-alert-wrapper" {if !$notice}style="display:none;"{/if}>
      <div class="alert alert-info fade in info-alert">
				<button class="close" aria-hidden="true" type="button"  onclick="$(this).closest('.info-alert-wrapper').fadeOut('slow');">&times;</button>
				<strong>{$notice}</strong>
			</div>
    </div>
	</header>		 

	{block name=body}{/block}
	{include file='footer.tpl'}
	
	<div id="help-alert" style="display:none;" >
		<button class="close" onclick="$('#help-alert').slideUp();" type="button">×</button>
		<div id="help-alert-bold">Experiencing problems?</div>	
		Give us a call on <a href="tel:+123456789">+123456789</a>
	</div>
	<script type="text/javascript" src="/includes/js/jquery-1.11.3.min.js"></script>
	<script type="text/javascript" src="/includes/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/includes/js/custom.js"></script>
	<script type="text/javascript" src="/includes/js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="/includes/js/shopping-cart.js"></script>	
	<script type="text/javascript">
	$(document).ready(function(){

		setTimeout(function(){
			$('#help-alert').slideDown();
    	},300000);
    	
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

	function nonAplha(ID){
		var str = $(ID).val();
	   t = str.replace(/[^a-zA-Z0-9-_+]/i,'');
	   $(ID).val(t); 
	}

	if (typeof jQuery != 'undefined') {
	    jQuery(document).ready(function($) {
	        var filetypes = /\.(zip|exe|pdf|doc*|xls*|ppt*|mp3)$/i;
	        var baseHref = '';
	        if (jQuery('base').attr('href') != undefined)
	            baseHref = jQuery('base').attr('href');
	        jQuery('a').each(function() {
	            var href = jQuery(this).attr('href');
	            if (href && (href.match(/^http?\:/i) || href.match(/^https?\:/i))  && (!href.match(document.domain))) {
	                jQuery(this).click(function() {
	                    var extLink = href.replace(/^https?\:\/\//i, ''); 
	                    extLink = extLink.replace(/^http?\:\/\//i, '');
	                    //_gaq.push(['_trackEvent', 'External', 'Click', extLink]);
	                    ga('send', 'event', 'External', 'Click', extLink);
	                    if (jQuery(this).attr('target') != undefined && jQuery(this).attr('target').toLowerCase() != '_blank') {
	                        setTimeout(function() { location.href = href; }, 200);
	                        return false;
	                    }
	                });
	            }
	            else if (href && href.match(/^mailto\:/i)) {
	                jQuery(this).click(function() {
	                    var mailLink = href.replace(/^mailto\:/i, '');
	                    //_gaq.push(['_trackEvent', 'Email', 'Click', mailLink]);
	                    ga('send', 'event', 'Email', 'Click', mailLink);
	                });
	            }
	            else if (href && href.match(/^tel\:/i)) {
	                jQuery(this).click(function() {
	                    var telLink = href.replace(/^tel\:/i, '');
	                    //_gaq.push(['_trackEvent', 'Phone', 'Click', telLink]);
	                    ga('send', 'event', 'Phone', 'Click', telLink);
	                });
	            }
	            else if (href && href.match(filetypes)) {
	                jQuery(this).click(function() {
	                    var extension = (/[.]/.exec(href)) ? /[^.]+$/.exec(href) : undefined;
	                    var filePath = href;
	                   // _gaq.push(['_trackEvent', 'Download', 'Click-' + extension, filePath]);
	                    ga('send', 'event', 'Download', 'Click-'+ extension, filePath);
	                    
	                    if (jQuery(this).attr('target') != undefined && jQuery(this).attr('target').toLowerCase() != '_blank') {
	                        ;
	                        setTimeout(function() { location.href = baseHref + href; }, 200);
	                        return false;
	                    }
	                });
	            }
	        });
	    });
	}
	</script>
	{block name=tail}{/block}
</body>
</html>