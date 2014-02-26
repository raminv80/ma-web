<!DOCTYPE html>
<html>
<head>
<meta name="description" content="{$listing_meta_description}" />
<meta name="keywords" content="{$listing_meta_words}" />
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

<title>{$category_name|replace:'and':'&'|ucfirst} {$product_name|ucfirst} {$listing_seo_title}</title>
<link href="/images/favicon.ico" type="image/x-icon" rel="shortcut icon">

<!-- Bootstrap -->
<link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
<link href="/includes/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="/includes/css/tipTip.css" rel="stylesheet" media="screen">
<link href="/includes/css/custom.css" rel="stylesheet" media="screen">
<link href='http://fonts.googleapis.com/css?family=Oxygen:400,700' rel='stylesheet' type='text/css'>

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script type="text/javascript" src="/includes/js/html5shiv.js"></script>
      <script type="text/javascript" src="/includes/js/respond.min.js"></script>
 	<![endif]-->
 	
<script type="text/javascript" src="/includes/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="/includes/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/includes/js/custom.js"></script>
<script type="text/javascript" src="/includes/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/includes/js/modernizr-2.6.2-respond-1.1.0.min.js"></script>
<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({ publisher: "ur-fe757e87-3393-e43b-6170-ceee5d67e29", doNotHash: false, doNotCopy: false, hashAddressBar: false });</script>

{block name=head}{/block}

{literal}
<script>
  /* GOOGLE CODE GOES HERE */
</script>
{/literal}

</head>
<body>
	<div id="fb-root"></div>
	<header>
		<div id="header1">
			<div id="header1in" class="container">
				{include file='mobilemenu.tpl'}
				<div id="logo"><a href="/"><img src="/images/logo.png" alt="logo icon"/></a></div>
				<div id="social">
					<!-- <div id="newstop"><a class="btn btnblue" id="signup" href="javascript:void(0)" onclick="$('body, html').animate({ duration: 2000,scrollTop: $('#newsletter').position().top });" title="Sign up to our Newsletter">Sign up to our Newsletter</a></div> -->
					<!-- <div id="socin">
					Follow us on
					<a target="_blank" href="https://www.twitter.com/" title="Follow us on Twitter" onclick="ga('send', 'event', 'socialmedia', 'click', 'follow us on twitter');"><img src="/images/twitter.png" alt="Twitter icon" /></a>
					<a target="_blank" href="https://www.facebook.com/" title="Like us on Facebook" onclick="ga('send', 'event', 'socialmedia', 'click', 'like us on facebook');"><img src="/images/facebook.png" alt="Facebook icon" /></a>
					<a target="_blank" href="https://www.youtube.com/" title="Our YouTube Channel" onclick="ga('send', 'event', 'socialmedia', 'click', 'our youtube channel');"><img src="/images/youtube.png" alt="YouTube icon" /></a>
					</div> -->
				</div>
				{include file='desktopmenu.tpl'}
			</div>
		</div>
		<div id="headbanner">
			<div id="headbannerin" class="container">
				<div class="row">
		
				</div>
			</div>
		</div>	 
	</header>		 

	{block name=body}{/block}
	{include file='footer.tpl'}
	
	<script type="text/javascript">
	$(document).ready(function(){
		$('#searchbox').bind('keyup', function(event) {
		    if(event.keyCode==13){
		    	window.location.href = 'http://'+(document.domain)+ '/search?q=' + $('#searchbox').val();
		    }
		});
		
		$('.dropdown.navbar-right ').hover(function() { 
		  $(this).find('.dropdown-menu:hidden').slideDown('slow');
		}, function() {
		  $(this).find('.dropdown-menu:visible').slideUp('slow')
		});
	});
	</script>
</body>
</html>