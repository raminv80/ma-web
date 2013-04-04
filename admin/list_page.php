<?php
$pageid=1;
set_include_path($_SERVER['DOCUMENT_ROOT']);
include 'admin/includes/templates/header.php';
$form_obj	=	new page();
echo"<h3 class='list-page-title'>CONTENT PAGES</h3>";
$misc_list_stg	= $form_obj->ListPages();
echo $misc_list_stg;
echo"</div>";
echo'
<form method="post" action="/admin/includes/processes/admin-processes-general.php" id="DeleteEntry" >
<input type="hidden" name="entry" id="entry" value="">
<input type="hidden" name="Action" value="DeletePageEntry">
'.insertToken().'
</form>
';
include 'admin/includes/templates/footer.php';