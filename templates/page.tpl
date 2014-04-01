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

<title>{$product_name|ucfirst} {$listing_seo_title}</title>
<link href="/images/favicon.ico" type="image/x-icon" rel="shortcut icon">

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
 	<![endif]-->
 	
<script type="text/javascript" src="/includes/js/jquery-1.9.1.min.js"></script>

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
				{include file='desktopmenu.tpl'}
			</div>
		</div>
	</header>		 

	{block name=body}{/block}
	{include file='footer.tpl'}
	
	<div class="modal fade bs-modal-lg" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
	    	<div class="modal-content">
	    		<div class="modal-header">
	    			&nbsp;
					<button class="close" aria-hidden="true" data-dismiss="modal" type="button">&times;</button>
				</div>
	      		<div class="modal-body">
	      			{include file='login.tpl'}
	      		</div>
			</div>
	  	</div>
	</div>
	<div id="help-alert" style="display:none;" >
		<button class="close" onclick="$('#help-alert').slideUp();" type="button">×</button>
		<div id="help-alert-bold">Experiencing problems?</div>	
		Give us a call on <a href="tel:+123456789">+123456789</a>
	</div>
	<script type="text/javascript" src="/includes/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/includes/js/custom.js"></script>
	<script type="text/javascript" src="/includes/js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="/includes/js/modernizr-2.6.2-respond-1.1.0.min.js"></script>
	<script type="text/javascript">var switchTo5x=true;</script>
	<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
	<script type="text/javascript">stLight.options({ publisher: "ur-fe757e87-3393-e43b-6170-ceee5d67e29", doNotHash: false, doNotCopy: false, hashAddressBar: false });</script>
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

		{if $login_referer}
			$('.redirect').val('{$login_referer}');
			$('#login-modal').modal('show');
		{/if}
	});
	</script>
</body>
</html>