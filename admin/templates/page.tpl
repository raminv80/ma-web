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
	<title>{$page_seo_title}</title>
	<link href="/images/template/favicon.ico" type="image/x-icon" rel="shortcut icon">
	
	<script type="text/javascript" src="/includes/js/jq.js"></script>	
	<script type="text/javascript" src="/includes/js/jqui.js"></script>
	<script type="text/javascript" src="/includes/js/validation.js"></script>		
	<link rel="stylesheet" type="text/css" href="/includes/css/jqui.css" />
	
	<script type="text/javascript" src="/includes/js/json_parse.js"></script>
	<script type="text/javascript" src="/new_admin/includes/js/tiny_mce/jquery.tinymce.js"></script>
	
	<script type="text/javascript" src="/includes/js/timepicker/jquery.ui.timepicker.js"></script>	
	<link rel="stylesheet" type="text/css" href="/includes/js/timepicker/jquery.ui.timepicker.css" />
	
	<script type="text/javascript" charset="utf-8" src="/includes/js/ddlevelsfiles/ddlevelsmenu.js" ></script>
	<link rel="stylesheet" type="text/css" href="/includes/js/ddlevelsfiles/ddlevelsmenu-base.css" />
	<link rel="stylesheet" type="text/css" href="/includes/js/ddlevelsfiles/ddlevelsmenu-topbar.css" />
	
	<link type="text/css" href="/includes/css/reset.css" rel="stylesheet">
	<link type="text/css" href="/includes/css/text.css" rel="stylesheet">
	<link type="text/css" href="/includes/css/styles.css" rel="stylesheet">
	<link type="text/css" href="/includes/css/960_16_col.css" rel="stylesheet">	
	<link href='http://fonts.googleapis.com/css?family=Raleway:400,500,600' rel='stylesheet' type='text/css'>
	
	<script type="text/javascript">
		$(function() {
			 $( "input[type=submit]" ).button();
			$( '#bar-menu' ).accordion({ collapsible: true,
				active: false ,
				autoHeight: false,
				navigation: true,
				icons:false,
				animated: 'bounceslide'});
		}); 
		 
	</script>
	
	<title>Website administration</title>		
		
</head>
<body>
<div class="container_16">
    <div id="logo" class="grid_5 left">
        <h1>CMS Administration Area</h1>
    </div><!-- end of logo -->
</div><!-- end ofcontainer_16 -->
<div id='maincontent' class="container_16">
	{block name=nav}{/block}
	<!--  block body start -->
	{block name=body}{/block}
	<!--  block body end -->
</div>
{block name=footer}{/block}
</body>
</html>