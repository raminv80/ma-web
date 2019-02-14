<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'admin/includes/functions/admin-functions.php';
global $CONFIG, $SMARTY, $DBobject;

foreach($CONFIG->group as $gp){
	foreach($gp->section as $sp){
		if ($sp->url == 'orders') {
			$fromDate = date("Y-m-d", strtotime( str_replace('/', '-', $_POST['from'])));
			$toDate = date("Y-m-d", strtotime( str_replace('/', '-', $_POST['to'])));
			
			$sp->table->where = "DATE(cart_closed_date) BETWEEN '{$fromDate}' AND '{$toDate}'";
			$record = new Record($sp);
			$list = $record->getRecordList();
			$SMARTY->assign("list",$list);
			$body= $SMARTY->fetch('ec_orders.tpl');
			echo json_encode(array(
					'body' =>  str_replace(array('\r\n', '\r', '\n', '\t'), ' ', $body)
			));
		}
	}
}