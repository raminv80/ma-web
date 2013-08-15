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
	<link rel="stylesheet" type="text/css" href="/admin/includes/css/jqui.css" />
	<link rel="stylesheet" type="text/css" href="/admin/includes/css/styles.css" />

	<!-- Responsive -->
	<link rel="stylesheet" type="text/css" href="/admin/includes/css/bootstrap.min.css" />
	<script src="/includes/js/bootstrap.min.js"></script>
	<!-- End Responsive -->
	<script type="text/javascript" src="/admin/includes/js/validation.js"></script>
	<script type="text/javascript" src="/admin/includes/js/tiny_mce/jquery.tinymce.js"></script>
	<script type="text/javascript" src="/admin/includes/js/timepicker/jquery.ui.timepicker.js"></script>

	<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/fileManager/css/elfinder.min.css">
	<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/fileManager/css/theme.css">
	<script type="text/javascript" src="/admin/includes/fileManager/js/elfinder.full.js"></script>
	<link rel="stylesheet" type="text/css" href="/admin/includes/js/timepicker/jquery.ui.timepicker.css" />

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
		<div id="elfinder"></div>
		<div class="row-fluid alert alert-block hidden" id="edited"><div class="span12"><img alt="success" src="/admin/images/success.png" width="28" height="28"/><b>The item was successfully edited.</b></div></div>
		<div class="row-fluid alert alert-block hidden" id="deleted"><div class="span12"><img alt="success" src="/admin/images/success.png" width="28" height="28"/><b>The item was successfully deleted.</b></div></div>
		<div class="row-fluid alert alert-block hidden" id="warning"><div class="span12"><img alt="error" src="/admin/images/warning.png" width="28" height="28"/><b>There is something wrong. Please check that you have filled out the fields correctly.</b></div></div>
		<div class="row-fluid alert alert-block hidden" id="error"><div class="span12"><img alt="error" src="/admin/images/warning.png" width="28" height="28"/><b>An unknown error occured.</b></div></div>
		{if $notice neq ''}
			<script>
			$('#{$notice}').removeClass('hidden');
			setTimeout(function(){
				//$('.alert').fadeOut('slow');
				$('#{$notice}').fadeOut('slow', function() {
					$('#{$notice}').addClass('hidden');
				});
	    	},6000);
			</script>
		{/if}
		<!--  block body start -->
		{block name=body}{/block}
		<!--  block body end -->
		</div>
	</div>
	{block name=footer}{/block}
	<script type="text/javascript">
		$(document).ready(function(){
			$('textarea.tinymce').tinymce({
				// Location of TinyMCE script
				script_url : '/admin/includes/js/tiny_mce/tiny_mce.js',

				// General options
				theme : "advanced",
				plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking",

				// Theme options
				theme_advanced_buttons1 : "forecolor,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
				theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,file,cleanup,code",
				theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media",
				theme_advanced_buttons4 : "",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : true,
				convert_urls : false,
				content_css : '/includes/css/bootstrap.min.css,/includes/css/colorbox.css,/includes/css/custom.css,/includes/css/tipTip.css',
				file_browser_callback : 'elFinderBrowser'
			});
		});

		function elFinderBrowser (field_name, url, type, win) {
            var cmsURL = '/admin/includes/fileManager/elfinder.php';    // script URL - use an absolute path!
            if (cmsURL.indexOf("?") < 0) {
                //add the type as the only query parameter
                cmsURL = cmsURL + "?type=" + type;
            }
            else {
                //add the type as an additional query parameter
                // (PHP session ID is now included if there is one at all)
                cmsURL = cmsURL + "&type=" + type;
            }

            tinyMCE.activeEditor.windowManager.open({
                file : cmsURL,
                title : 'elFinder 2.0',
                width : 900,
                height : 450,
                resizable : "yes",
                inline : "yes",  // This parameter only has an effect if you use the inlinepopups plugin!
                popup_css : false, // Disable TinyMCE's default popup CSS
                close_previous : "no"
            }, {
                window : win,
                input : field_name
            });
            return false;
        }
	</script>

</div>
	<script type="text/javascript" src="/admin/includes/js/admin-general.js"></script>
</body>
</html>