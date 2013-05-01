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
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="/images/template/favicon.ico" type="image/x-icon" rel="shortcut icon">
	
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.1.1.min.js"></script>
	<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/includes/css/jqui.css" />
	
	<link rel="stylesheet" type="text/css" href="/includes/css/styles.css" />
	
	<!-- Responsive -->
	<link rel="stylesheet" type="text/css" href="/includes/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="/includes/css/bootstrap-responsive.min.css" />
	<script src="/includes/js/bootstrap.min.js"></script>
	<!-- End Responsive -->
	
	<script type="text/javascript" src="/includes/js/validation.js"></script>		
	
	<script type="text/javascript" src="/admin/includes/js/tiny_mce/jquery.tinymce.js"></script>
	
	<script type="text/javascript" src="/includes/js/timepicker/jquery.ui.timepicker.js"></script>	
	<link rel="stylesheet" type="text/css" href="/includes/js/timepicker/jquery.ui.timepicker.css" />
	
	<script type="text/javascript" charset="utf-8" src="/includes/js/ddlevelsfiles/ddlevelsmenu.js" ></script>
	<link rel="stylesheet" type="text/css" href="/includes/js/ddlevelsfiles/ddlevelsmenu-base.css" />
	<link rel="stylesheet" type="text/css" href="/includes/js/ddlevelsfiles/ddlevelsmenu-topbar.css" />
	
	<link type="text/css" href="/includes/css/styles.css" rel="stylesheet">
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
<div class='container'>
	<div class="masthead">
	    <div id="logo">
	        <h1>CMS Administration Area</h1>
	    </div><!-- end of logo -->
	</div><!-- end ofcontainer_16 -->
	<div class="row-fluid">
		<div class="span3">
		{block name=nav}{/block}
		</div>
		<div class="span9">
		<!--  block body start -->
		{block name=body}{/block}
		<!--  block body end -->
		</div>
	</div>
	{block name=footer}{/block}
</div>

</body>
</html>