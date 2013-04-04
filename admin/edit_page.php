<?php
$pageid=1;
set_include_path($_SERVER['DOCUMENT_ROOT']);
include 'admin/includes/templates/header.php';

if(isset($_REQUEST['id'])){
	$id=intval($_REQUEST['id']);
}else{
	die("<script>document.location.href='main.php'</script>");
}

$page_obj = new page();
$form_str = $page_obj->CreateEditForm($id,'Edit_Page');
echo'<div class="pages-head">';
echo"<h3 class='pages-title'>Editing  Page  - ".$page_obj->page_title." </h3>";
echo'</div>';
echo $form_str;
include 'admin/includes/templates/footer.php';
