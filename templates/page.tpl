{*	This template holds the basic surrounding structure of an html page.

	Variables:
		SEO Description = {$page_metadescription}
		SEO Keywords = {$page_metawords}
		SEO Page title = {$page_title}
		Company Name = {$company_name}
*}
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
    <link href="/includes/css/styles.css" rel="stylesheet">
    <link href="/includes/css/fluid.css" rel="stylesheet">
    <link href="/includes/css/main.css" rel="stylesheet">
	<script src="http://code.jquery.com/jquery.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<script src="/includes/js/bootstrap.min.js"></script>
	<script src="/includes/js/youtube.js"></script>

<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({ publisher: "ur-a4a1aae0-5741-1e4a-2a36-dd37824dcca", doNotHash: false, doNotCopy: false, hashAddressBar: false });</script>
</head>
<body>
<div id="topmenu">
                <div class="container">
						<ul class="nav1" >

		                            {foreach $menuitems as $mitem name=menuitem}
		                            	<li class="{if $mitem.selected eq 1 }active{/if}" > <a href="/{$mitem.url}"  {$mitem.rel} >{$mitem.title} </a>
		                            	  {foreach $submenuitems[$smarty.foreach.menuitem.index] as $subitem}
		                            		 	<ul>
		                            		 		<li><a href="{$subitem.url}">{$subitem.title}</a></li>
		                            		 	</ul>
										 {/foreach}
										</li>
									 {/foreach}
						</ul>
				</div>
</div>
<div class="navbar navbar-inverse navbar-static-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="logo" href="/"><img src="/images/mcleaylogo.jpg" alt="logo" /></a>

			<div class="topnav">
				<ul>
					<li><a href="/about-us"  id="about">About us</a></li>
					<li><a href="/contact-us">Contact us</a></li>
				</ul>
			</div>

			<div class="phoneno">(08) 8379 7971 <span class="phoneloc">Showroom</span><br />
			(08) 8346 4999 <span class="phoneloc">Bulk store</span></div>

                    <div class="nav-collapse collapse">
                        <ul class="nav dropdown">
                              {foreach $menuitems as $mitem name=menuitem2}
		                            	<li  class="{if $mitem.selected eq 1 }active{/if}
		                            	{if $category_name eq "curtains and blinds" and $mitem.url eq "products/curtains-and-blinds" }active{/if}
										{if $category_name eq "carpets and flooring" and $mitem.url eq "products/carpets-and-flooring" }active{/if}" id="menu-{$mitem.title}" >
		                            	<a href="/{$mitem.url}"  {$mitem.rel}  {if $submenuitems[$smarty.foreach.menuitem2.index] neq ""} class="dropdown-toggle" id="dLabel_{$smarty.foreach.menuitem2.index}" role="button" data-toggle="dropdown" data-target="{$mitem.url}"  {/if} >{$mitem.title}</a>
		                            	{if $submenuitems[$smarty.foreach.menuitem2.index] neq ""}
		                            		<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel_{$smarty.foreach.menuitem2.index}">
		                            			<li class="submenu-li"><a href="/{$mitem.url}">{$mitem.title}</a></li>
		                            		{foreach $submenuitems[$smarty.foreach.menuitem2.index] as $subitem}
			                            		<li class="submenu-li"><a href="/{$subitem.url}">{$subitem.title}</a></li>
			                            	{/foreach}
			                            	</ul>
										{/if}
										</li>
		                      {/foreach}
                        </ul>
                  </div><!--/.nav-collapse -->
            </div>
      </div>
</div>
<style>
ul.nav li.dropdown:hover > ul.dropdown-menu{
    display: block;
}
</style>

<!--  add sliders here -->

<!--  ////  -->
	{block name=body}{/block}
 <div class="container">
	<div class="row footer">
				<div class="span2">
					<ul>
						<li><a href="/">Home</a></li>
						<li><a href="/about-us">About us</a></li>
						<li><a href="/contact-us">Contact us</a></li>
						<li><a href="/products/carpets-and-flooring">Carpets &amp; flooring</a></li>
						<li><a href="/products/curtains-and-blinds">Curtains &amp; blinds</a></li>
					</ul>
				</div>
				<div class="span2">
					<ul>
						<li><a href="/specials">Specials</a></li>
						<li><a href="/product-care">Product care</a></li>
						<li><a href="/contact-us#showroom">Showroom</a></li>
						<li><a href="/contact-us#bulk-store">Bulk store</a></li>
					</ul>
				</div>
				<div class="span2 offset2 addr">
					<span style="font-weight:bold">Showroom</span><br/>
					243 Glen Osmond Road<br/>
					FREWVILLE SA 5063<br/>
					<a href="/contact-us#showroom">Map &amp; directions</a><br/>
					Phone: (08) 8379 7971<br/>
					Fax: (08) 8338 2175<br/>
					<span style="font-weight:bold">Open</span><br/>
					Monday-Friday 9am - 5pm<br/>
					Saturday 9.30am - 12pm<br/>
				</div>
				<div class="span2 addr">
					<span style="font-weight:bold">Bulk store</span><br/>
					97 Coglin Street<br/>
					BROMPTON SA 5007<br/>
					<a href="/contact-us#bulk-store">Map &amp; directions</a><br/>
					Phone: (08) 8346 4999<br/>
					Fax: (08) 8346 6963<br/>
					<span style="font-weight:bold">Open</span><br/>
					Monday-Friday 9am - 5pm<br/>
					Saturday 9am - 12pm
				</div>
				<div class="span2 addr"><br/><br/><br/><br/><br/><br/><br/><br/>
				<span class='st_sharethis_hcount right' displayText='ShareThis'></span>
				</div>

	</div>
</div>
		<div class="footout">
			<div class="container">
				<div class="row">
					<div class="span12">
						<div class="copy">&copy; Copyright McLeay and Sons  {'Y'|date}</div>
						<div class="made">Made by <a href="http://www.them.com.au" target="_blank">THEM Advertising &amp; Digital</a></div>
					</div>
				</div>
			</div>
		</div>



<!-- Placed at the end of the document so the pages load faster -->

	<script type="text/javascript">
jQuery(window).load(function() {

	$('#topmenu').prepend('<a id="menu-icon"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></a>');
	$('.carousel').carousel();

	$('#vid1').click(function(){
		if($(this).hasClass('redtext')){
		$('.videos').css('display','none');
		$(this).find('.homebox').removeClass('redborder');
		$(this).removeClass('redtext');
		}else{
		$('.videos').css('display','block');
		$(this).find('.homebox').addClass('redborder');
		$(this).addClass('redtext');
		}
	});

	$('.boxes a').hover(function(){
		$(this).find('.homebox').css('border','5px solid #E23F2E');
		$(this).css('color','#E23F2E');
	},function(){
		$(this).find('.homebox').css('border','5px solid #000');
		$(this).css('color','#000');
	});

	$('li.active').prev().css('border-right','0');


	/* toggle nav */
	$("#menu-icon").on("click", function(){
		$(".nav1").slideToggle();
		$(this).toggleClass("active");
	});

	$('.readmore').click(function(){
		$(this).parent().find('.more').toggleClass('hide');
		if(!$(this).parent().find('.more').hasClass('hide')){
			$(this).html('Read less');
		}else{
			$(this).html('Read more');
		}
	});

	$('.prodbox').click(function(){
		var tag=$(this).parent().attr('href');
		if(!$(tag).hasClass('hide')){
			$(this).parent().parent().parent().find('.prodboxt a').html('Read more');
			$(this).find('.hov').removeClass('show');
			$(this).removeClass('redborder');
			$(tag).addClass('hide');
		}else{
			$('.productslist').addClass('hide');
			$('.prodbox').removeClass('redborder');
			$('.prodbox').find('.hov').removeClass('show');
			$('.prodboxt a').html('Read more');
			$(this).parent().parent().parent().find('.prodboxt a').html('Read less');
			$(this).find('.hov').addClass('show');
			$(this).addClass('redborder');
			$(tag).removeClass('hide');
		}
	});

	$('.prodboxt a').click(function(){
		var tag=$(this).attr('href');
		if(!$(tag).hasClass('hide')){
			$(this).html('Read more');
			$(this).parent().parent().parent().find('.hov').removeClass('show');
			$(this).parent().parent().parent().find('.prodbox').removeClass('redborder');
			$(tag).addClass('hide');
		}else{
			$('.productslist').addClass('hide');
			$('.prodbox').removeClass('redborder');
			$('.prodbox').find('.hov').removeClass('show');
			$('.prodboxt a').html('Read more');
			$(this).html('Read less');
			$(this).parent().parent().parent().find('.prodbox').addClass('redborder');
			$(this).parent().parent().parent().find('.hov').addClass('show');
			$(tag).removeClass('hide');
		}
	});

    var deviceAgent = navigator.userAgent.toLowerCase();
    var agentID = deviceAgent.match(/(iPad|iPhone|iPod)/i);
	if (!agentID){

	$('.boxes a').hover(function(){
		$(this).find('.homebox').css('border','5px solid #E23F2E');
		$(this).css('color','#E23F2E');
	},function(){
		$(this).find('.homebox').css('border','5px solid #000');
		$(this).css('color','#000');
	});

	$('.prodboxes .span4').hover(function(){
		$(this).find('.prodbox').css('border','5px solid #E23F2E');
		$(this).find('.hov').show();
	},function(){
		$(this).find('.prodbox').css('border','5px solid #FFFFFF');
		$(this).find('.hov').hide();
	});
	}


});


	</script>
</body>
</html>