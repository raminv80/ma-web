<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'admin/includes/functions/admin-functions.php';
global $CONFIG, $SMARTY, $DBobject;

foreach($CONFIG->section as $sp){
	if ($sp->url == 'orders') {
		$from = DateTime::createFromFormat('d/m/Y', $_POST['from']);
		$to = DateTime::createFromFormat('d/m/Y', $_POST['to']);
		$sp->table->where = "DATE(cart_closed_date) BETWEEN '{$from->format('Y-m-d')}' AND '{$to->format('Y-m-d')}'";
		$record = new Record($sp);
		$list = $record->getRecordList();
		$SMARTY->assign("list",$list);
		$body= $SMARTY->fetch('orders.tpl');
		echo json_encode(array(
				'body' =>  str_replace(array('\r\n', '\r', '\n', '\t'), ' ', $body)
		));
	}
}
