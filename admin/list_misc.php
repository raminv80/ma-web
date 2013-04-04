<?php
$pageid=1;
set_include_path($_SERVER['DOCUMENT_ROOT']);
include 'admin/includes/templates/header.php';
if(	!empty($_REQUEST['misc'])){
	$mist_id=intval($_REQUEST['misc']);
}else{
	die("<script>document.location.href='main.php'</script>");
}
$form_obj	=	new form_class($mist_id,null,'0');
echo"<h3>Listing  - ".$form_obj->plural." </h3>";
$misc_list_stg	= $form_obj->ListMisc();
echo $misc_list_stg;
echo"</div>";

include 'admin/includes/templates/footer.php';