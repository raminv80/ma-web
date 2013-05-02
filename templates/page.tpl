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
	<meta name="Description" content="{$page_metadescription}" />
	<meta name="Keywords" content="{$page_metawords}" />
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />		
	<meta name="Distribution" content="Global" />
	<meta name="Robots" content="index,follow" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">	
	<title>{$page_seo_title}</title>
	<!-- <link href="/images/template/favicon.ico" type="image/x-icon" rel="shortcut icon"> -->
	
	<!-- Bootstrap -->
    <link href="/includes/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="/includes/css/styles.css" rel="stylesheet">
    <link href="/includes/css/fluid.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Muli:400,300,300italic' rel='stylesheet' type='text/css'>
    
    <script src="http://code.jquery.com/jquery.js"></script>

	{block name=head}{/block} {* Block containing any specific information required for this page and not main site *}
</head>
<body>
<div id="top-bar">
    	<div class="container">
            <ul>
            	<li><a href="/news">News</a></li>
                <li><a href="/about-us">About us</a></li>
                <li><a href="/retailers">Retailers</a></li>
                <li><a href="/contact-us">Contact us</a></li>
            </ul>
        </div>
    </div>
<div id="top-menu">
	<div class="container">
                  <ul class="nav1">
                    <li {if $url eq ''}class="active"{/if}><a href="/" {if $url eq ''}class="selected"{/if}>Home</a></li>
                    <li {if $url eq 'products'}class="active"{/if}><a href="/products" {if $url eq 'products'}class="selected"{/if}>Products <b class="caret"></b></a>
						<ul>
							{assign var="cat" value=""}
					{assign var="count" value="0"}	
						{foreach from=$menu_products item=mp name=menu}
							{if $cat eq ""}
								{assign var="cat" value=$mp.category_title}
								<li><a href="/products/{urlencode data=$mp.category_title}" >{$mp.category_title}</a>
									<ul>
							{/if}
							{if $cat neq $mp.category_title}
								{assign var="cat" value=$mp.category_title}
								</ul></li>
								<li><a href="/products/{urlencode data=$mp.category_title}" >{$mp.category_title}</a>
									<ul>
							{/if}
								<li><a href="/products/{urlencode data=$mp.category_title}/{urlencode data=$mp.product_id}" >{$mp.product_title}</a></li>
							{if $cat neq $mp.category_title or $smarty.foreach.menu.last}
								</ul></li>
							{/if}
						{/foreach} 	
						</ul>
					</li>
                    <li {if $url eq 'support'}class="active"{/if}><a href="/support" {if $url eq 'support'}class="selected"{/if}>Support</a></li>
                    <li {if $url eq 'warranty-and-registration'}class="active"{/if}><a href="/warranty-and-registration" {if $url eq 'warranty-and-registration'}class="selected"{/if}>Warranty and registration</a></li>
                    <li class="last {if $url eq 'interactive'}active{/if}"><a href="/interactive" {if $url eq 'interactive'}class="selected"{/if}>Interactive</a></li>
                <li {if $url eq 'news'}class="active"{/if}><a href="/news" {if $url eq 'news'}class="selected"{/if}>News</a></li>
		  		<li {if $url eq 'about-us'}class="active"{/if}><a href="/about-us" {if $url eq 'about-us'}class="selected"{/if}>About us</a></li>
                <li {if $url eq 'retailers'}class="active"{/if}><a href="/retailers" {if $url eq 'retailers'}class="selected"{/if}>Retailers</a></li>
                <li {if $url eq 'contact-us'}class="active"{/if}><a href="/contact-us" {if $url eq 'contact-us'}class="selected"{/if}>Contact us</a></li>
                  </ul>
	</div>
</div>
<div class="container">
  <div class="row-fluid">
    <div class="span4">
      <div id="logo"><a href="/" border="0"><img src="/images/template/cli-mate-logo.png" /></a></div>
    </div><!-- end of span4 -->
    <div class="span4 offset8 phone-search">
      <div id="ph-no">1300 764 325</div>
      <div class="clearfix"></div>
      <div id="search-site"><input type="text" id="search_box" placeholder="SEARCH..." /></div>
    </div><!-- end of span4 offset8 -->
  </div><!-- end of row-fluid -->
    <div class="container content-bg">
      <div class="row-fluid">
        <div class="span16">
            <div class="navbar">
              <div class="navbar-inner">
                <div class="container">
                  <ul class="nav">
                    <li {if $url eq ''}class="active"{/if}><a href="/" {if $url eq ''}class="selected"{/if}>Home</a></li>
                    <li class="dropdown {if $url eq 'products'}active{/if}"><a href="/products" class="{if $url eq 'products'}selected{/if}" >Products <b class="caret"></b></a>
				<ul class="dropdown-menu" role="menu">
					<div>
					{assign var="cat" value=""}
					{assign var="count" value="0"}	
						{foreach from=$menu_products item=mp name=menu}
							{if $cat eq ""}
								{assign var="cat" value=$mp.category_title}
								<div class="menul">
									<a href="/products/{urlencode data=$mp.category_title}" >{$mp.category_title}</a>
									<ul>
							{/if}
							{if $cat neq $mp.category_title}
								{assign var="cat" value=$mp.category_title}
								</ul>
								</div>
								<div class="menul">
									<a href="/products/{urlencode data=$mp.category_title}" >{$mp.category_title}</a>
									<ul>
							{/if}
								<li><a href="/products/{urlencode data=$mp.category_title}/{urlencode data=$mp.product_id}" >{$mp.product_title}</a></li>
							{if $cat neq $mp.category_title or $smarty.foreach.menu.last}
									</ul>
								</div>
							{/if}
						{/foreach} 			
					</div>
				</ul>
			</li>
                    <li {if $url eq 'support'}class="active"{/if}><a href="/support" {if $url eq 'support'}class="selected"{/if}>Support</a></li>
                    <li {if $url eq 'warranty-and-registration'}class="active"{/if}><a href="/warranty-and-registration" {if $url eq 'warranty-and-registration'}class="selected"{/if}>Warranty and registration</a></li>
                    <li class="last{if $url eq 'interactive'}active{/if}"><a href="/interactive" {if $url eq 'interactive'}class="selected"{/if}>Interactive</a></li>
                  </ul>
                </div>
              </div>
            </div><!-- /.navbar -->
        </div><!-- /.span16 -->
      </div><!-- /.row-fluid -->
      
	{block name=body}{/block}
	
	
	<div class="clearfix"></div>
      
    </div><!-- /.container .content-bg -->

	<div class="container featured-container">
		<div class="row-fluid">
			<div class="span16">
			<h3>Featured products</h3>
			</div>
			<div class="span16">
				{foreach $featured as $product_item}
					{include file='product-featured-item.tpl'}	
				{/foreach}	
			</div>
		</div>
	</div>




</div> <!-- /container -->
    
<div id="footer">
	<div class="container">
			<div class="row-fluid">
			<div class="span4">
				<ul>
					<li><a href="/">Home</a></li>
					<li><a href="/news">News</a></li>
					<li><a href="/about-us">About us</a></li>
					<li><a href="/retailer">Retailers</a></li>
					<li><a href="/contact-us">Contact us</a></li>
				</ul>
			</div>
			<div class="span4">
				<ul>
					<li><a href="/products">Products</a></li>
					<li><a href="/support">Support</a></li>
					<li><a href="/warranty-and-registration">Warranty and registration</a></li>
					<li><a href="/interactive">Interactive</a></li>
					<li><a href="/promotions">Promotions</a></li>
				</ul>
			</div>
			<div class="span4">
				<p>Stay connected with us</p>
				<a href="#"><img src="/images/facebook.png" alt="facebook" /></a>
				<a href="#"><img src="/images/twitter.png" alt="twitter" /></a>
				<a href="#"><img src="/images/youtube.png" alt="youtube" /></a>
			</div>
			<div class="span4">
			PO Box 81 Findon<br />
			SA 5023, Australia<br />
			P 1300 764 325<br />
			F 08 8354 0722		
			</div>
			</div>
	</div>
</div>    

<div id="footer2">
	<div class="container">
		<div class="span16">
			<div class="row-fluid">

			<div class="span4">
				<p>&copy; COPYRIGHT {'Y'|date} CLI~MATE</p>
			</div>
			<div class="span4 offset8">
				<p>MADE BY <a href="http://them.com.au">THEM ADVERTISING & DIGITAL</a></p>
			</div>

			</div>
		</div>
	</div>
</div>
    
    
<!-- Placed at the end of the document so the pages load faster -->
    <script src="/includes/js/bootstrap.min.js"></script>
    <script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery(".featured-container .span5").hover(function(){
			jQuery(this).find(".btn").css('visibility','visible');
		},function(){
			jQuery(this).find(".btn").css('visibility','hidden');
		});

		jQuery(".nav li.dropdown").hover(function(){
			jQuery(this).find('ul.dropdown-menu').css('display','block');
		},function(){
			jQuery(this).find('ul.dropdown-menu').css('display','none');
		});

	});
    </script>

<script type="text/javascript">
jQuery(document).ready(function($){

	$('#top-menu').prepend('<div id="menu-icon">Menu</div>');


	/* toggle nav */
	$("#menu-icon").on("click", function(){
		$(".nav1").slideToggle();
		$(this).toggleClass("active");
	});

	$('#search_box').keypress(function(e){
		$('#search_box').css("cursor","pointer");
		if(e.which == 13 && $('#search_box').val() != '' ){
			window.location.replace('/search?search=' + $('#search_box').val()); 
		}
	});
	$('#search_box').click(function(){
		if( $('#search_box').val() != '' && $('#search_box').val() != 'Search...' ){
			$('#value').val($('#search_box').val());
			window.location.replace('/search?search=' + $('#search_box').val());
		}
		if( $('#search_box').val() == 'Search...' ){
			$('#search_box').val('');
			}else{
		}
	});
});
</script>
    

</body>
</html>