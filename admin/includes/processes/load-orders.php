<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'admin/includes/functions/admin-functions.php';
global $CONFIG, $SMARTY, $DBobject;

foreach($CONFIG->section as $sp){
	if ($sp->url == 'orders') {
		$sp->table->where = "DATE(cart_closed_date) BETWEEN '{$_POST['from']}' AND '{$_POST['to']}'";
		$record = new Record($sp);
		$list = $record->getRecordList();
		$SMARTY->assign("list",$list);
		$body= $SMARTY->fetch('orders.tpl');
		echo json_encode(array(
				'body' =>  str_replace(array('\r\n', '\r', '\n', '\t'), ' ', $body)
		));
	}
}
