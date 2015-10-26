{* This template holds the basic surrounding structure of an html page. Variables: SEO Description = {$page_metadescription} SEO Keywords = {$page_metawords} SEO Page title = {$page_title} Company Name = {$company_name} *}
<!DOCTYPE html>
<html>
<head>
<meta name="Description" content="{$page_metadescription}" />
<meta name="Keywords" content="{$page_metawords}" />
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="Distribution" content="Global" />
<meta name="Robots" content="noindex,nofollow" />
<title>Website administration</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="/images/template/favicon.ico" type="image/x-icon" rel="shortcut icon">

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->



<script src="/admin/includes/js/jquery-1.9.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="/admin/includes/css/jqui.css" />
<link rel="stylesheet" type="text/css" href="/admin/includes/css/styles.css" />
<link rel="stylesheet" type="text/css" href="/admin/includes/js/timepicker/jquery.ui.timepicker.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/fileManager/css/elfinder.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/fileManager/css/theme.css">

<link rel="stylesheet" type="text/css" href="/admin/includes/css/bootstrap.min.css" />

<link href='//fonts.googleapis.com/css?family=Raleway:400,500,600' rel='stylesheet' type='text/css'>

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

<!--[if lte IE 8]>
    <script src="/admin/includes/js/respond.min.js"></script>
      <script src="/admin/includes/js/html5shiv.js"></script>
  <![endif]-->

</head>
<body>

  <div class='container'>
  
    {block name=nav}{/block}
    <div class="row">
    <div class="col-sm-12">
    <div class="masthead">
      <div id="logo">
      </div>
      <!-- end of logo -->
    </div>
    </div>
    </div>
    <!-- end ofcontainer_16 -->
    <div class="row">
      <div class="col-sm-12">
        
        
        <!--  block body start -->
        {block name=body}{/block}
        <!--  block body end -->
      </div>
    </div>
    {block name=footer}{/block}
    
    
    
    <script type="text/javascript">
    $(document).ready(function(){
      tinyMCEinit();
    });

    function tinyMCEinit(){
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
        content_css : '/includes/css/bootstrap.min.css,/includes/css/custom.css,/admin/includes/css/tinymce.css',
        file_browser_callback : 'elFinderBrowser'
      });
			$('textarea.tinymce').removeClass('tinymce');

    }
    
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
  
  <div class="notification" id="form-error" style="display:none;">
    <img alt="success" src="/admin/images/warning.png" width="28" height="28"/>
    <div style="display:inline;" id="form-error-msg"></div>
    <button type="button" class="close pull-right" onclick="$('#form-error').slideUp();">x</button>
  </div>
  <div class="notification" id="edited" style="display:none;">
    <img alt="success" src="/admin/images/success.png" width="28" height="28" /><b>The item was successfully edited.</b>
    <button type="button" class="close pull-right" onclick="$('#edited').slideUp();">x</button>
  </div>
  <div class="notification" id="deleted" style="display:none;">
    <img alt="success" src="/admin/images/success.png" width="28" height="28" /><b>The item was successfully deleted.</b>
    <button type="button" class="close pull-right" onclick="$('#deleted').slideUp();">x</button>
  </div>
  <div class="notification" id="warning" style="display:none;">
    <img alt="error" src="/admin/images/warning.png" width="28" height="28" /><b>There is something wrong. Please check that you have filled out the fields correctly.</b>
    <button type="button" class="close pull-right" onclick="$('#warning').slideUp();">x</button>
  </div>
  <div class="notification" id="error" style="display:none;">
    <img alt="error" src="/admin/images/warning.png" width="28" height="28" /><b>An unknown error occured.</b>
    <button type="button" class="close pull-right" onclick="$('#error').slideUp();">x</button>
  </div>
  <div class="notification" id="sent" style="display:none;">
    <img alt="success" src="/admin/images/success.png" width="28" height="28" /><b>The invoice was successfully sent.</b>
    <button type="button" class="close pull-right" onclick="$('#sent').slideUp();">x</button>
  </div>
  <div class="notification" id="email-sent" style="display:none;">
    <img alt="success" src="/admin/images/success.png" width="28" height="28" /><b>The email was successfully sent.</b>
    <button type="button" class="close pull-right" onclick="$('#email-sent').slideUp();">x</button>
  </div>
  {if $notice neq ''}
    <script>
      $('#{$notice}').slideDown();
      setTimeout(function(){
        $('#{$notice}').slideUp();
        },10000);
    </script>
  {/if}
  
  <div class="modal fade bs-modal-lg" id="modal-elfinder" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
          <button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
          <h4 class="modal-title">Select a file</h4>
        </div>
            <div id="elfinder"></div>
      </div>
      </div>
  </div>
  
  <script src="/admin/includes/js/jquery-migrate-1.2.1.min.js"></script>
  <script src="/admin/includes/js/jquery-ui-1.10.2.min.js"></script>
  <script src="/admin/includes/js/admin-general.js"></script>
  <script src="/admin/includes/js/tiny_mce/jquery.tinymce.js"></script>
  <script src="/admin/includes/js/timepicker/jquery.ui.timepicker.js"></script>
  <script src="/admin/includes/fileManager/js/elfinder.full.js"></script>
  <script src="/admin/includes/js/bootstrap.min.js"></script>
{block name=tail}{/block}
</body>
</html>