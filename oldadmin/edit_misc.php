<?php
$pageid=1;
set_include_path($_SERVER['DOCUMENT_ROOT']);
include 'oldadmin/includes/templates/header.php';

if(!empty($_REQUEST['misc'])){
	$id=intval($_REQUEST['id']);
	$misc_id=intval($_REQUEST['misc']);
}else{
	die("<script>document.location.href='main.php'</script>");
}

$misc_obj = new misc($misc_id);

$misc_arr = $misc_obj->get_misc($id);

foreach($misc_obj->listfields as $field_name => $field){
	$field_title = $field;
}

if(!empty($_REQUEST['id']) && $_REQUEST['id'] !=''){
	$title="- ".$misc_arr[0][$field_title];
}
echo'<hr>';
echo"<h3>Editing  - ".$misc_obj->plural." ".$title." </h3>";
echo'<hr>';

$form_obj	=	 new form_class($misc_id,$id,0);

$form_str	=	$form_obj->create_form('Edit_Misc');


echo $form_str;

include 'oldadmin/includes/templates/footer.php';
