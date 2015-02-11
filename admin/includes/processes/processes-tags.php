<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'admin/includes/functions/admin-functions.php';
global $SMARTY,$DBobject;

// if(!empty($_POST['objId'])){
// 	$params = array(
// 			'objId'=> $_POST['objId'],
// 			'table'=> $_POST['table_name'],
// 			'value'=> $_POST['tag']['tag_value']
// 	);
// 	$sql = "INSERT INTO tbl_tag ( tag_object_table, tag_object_id, tag_value, tag_modified ) values( :table, :objId, :value, now() )";
// 	$res = $DBobject->wrappedSql($sql, $params);
// }


$tpl = $_POST['template'];
if(!file_exists("{$SMARTY->template_dir[0]}".$tpl)){
	die();
}
foreach($_POST as $key => $var){
	$SMARTY->assign("{$key}",$var);
}
$SMARTY->display("{$tpl}");