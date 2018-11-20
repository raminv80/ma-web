{* This template holds the basic surrounding structure of an html page. Variables: SEO Description = {$page_metadescription} SEO Keywords = {$page_metawords} SEO Page title = {$page_title} Company Name = {$company_name} *}
<!DOCTYPE html>
<html>
<head>
  <meta name="Description" content="{$page_metadescription}"/>
  <meta name="Keywords" content="{$page_metawords}"/>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="Distribution" content="Global"/>
  <meta name="Robots" content="noindex,nofollow"/>
  <title>Website administration</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="/images/template/favicon.ico" type="image/x-icon" rel="shortcut icon">

  <link rel="stylesheet" type="text/css" href="/node_modules/jquery-ui-dist/jquery-ui.min.css"/>
  <link rel="stylesheet" type="text/css" href="/node_modules/jquery-timepicker/jquery.timepicker.css"/>
  <link rel="stylesheet" type="text/css" href="/node_modules/bootstrap/dist/css/bootstrap.min.css"/>
  <link href='//fonts.googleapis.com/css?family=Raleway:400,500,600' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" type="text/css" href="/admin/includes/css/styles.css"/>

  <script src="/node_modules/jquery/dist/jquery.min.js"></script>
  <script type="text/javascript">
    $(function () {
      $("input[type=submit]").button();
      $('#bar-menu').accordion({
        collapsible: true,
        active: false,
        autoHeight: false,
        navigation: true,
        icons: false,
        animated: 'bounceslide'
      });
    });
  </script>

  <!--[if lte IE 8]>
  <script src="/node_modules/respond.js/dest/respond.min.js"></script>
  <script src="/node_modules/html5shiv/dist/html5shiv.min.js"></script>
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


</div>

<div class="notification" id="form-error" style="display:none;">
  <img alt="success" src="/admin/images/warning.png" width="28" height="28"/>
  <div style="display:inline;" id="form-error-msg"></div>
  <button type="button" class="close pull-right" onclick="$('#form-error').slideUp();">x</button>
</div>
<div class="notification" id="edited" style="display:none;">
  <img alt="success" src="/admin/images/success.png" width="28" height="28"/><b>The item was successfully edited.</b>
  <button type="button" class="close pull-right" onclick="$('#edited').slideUp();">x</button>
</div>
<div class="notification" id="deleted" style="display:none;">
  <img alt="success" src="/admin/images/success.png" width="28" height="28"/><b>The item was successfully deleted.</b>
  <button type="button" class="close pull-right" onclick="$('#deleted').slideUp();">x</button>
</div>
<div class="notification" id="warning" style="display:none;">
  <img alt="error" src="/admin/images/warning.png" width="28" height="28"/><b>There is something wrong. Please check
    that you have filled out the fields correctly.</b>
  <button type="button" class="close pull-right" onclick="$('#warning').slideUp();">x</button>
</div>
<div class="notification" id="error" style="display:none;">
  <img alt="error" src="/admin/images/warning.png" width="28" height="28"/><b>An unknown error occured.</b>
  <button type="button" class="close pull-right" onclick="$('#error').slideUp();">x</button>
</div>
<div class="notification" id="sent" style="display:none;">
  <img alt="success" src="/admin/images/success.png" width="28" height="28"/><b>The invoice was successfully sent.</b>
  <button type="button" class="close pull-right" onclick="$('#sent').slideUp();">x</button>
</div>
<div class="notification" id="email-sent" style="display:none;">
  <img alt="success" src="/admin/images/success.png" width="28" height="28"/><b>The email was successfully sent.</b>
  <button type="button" class="close pull-right" onclick="$('#email-sent').slideUp();">x</button>
</div>
{if $notice neq ''}
  <script>
    $('#{$notice}').slideDown();
    setTimeout(function () {
      $('#{$notice}').slideUp();
    }, 10000);
  </script>
{/if}

<script src="/node_modules/jquery-ui-dist/jquery-ui.min.js"></script>
<script src="/node_modules/jquery-timepicker/jquery.timepicker.js"></script>
<script src="/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="/admin/includes/ckeditor/ckeditor.js"></script>
<script src="/admin/includes/ckfinder/ckfinder.js"></script>
<script src="/admin/includes/js/admin-general.js"></script>
{block name=tail}{/block}
</body>
</html>
