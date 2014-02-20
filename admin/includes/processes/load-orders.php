<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'admin/includes/functions/admin-functions.php';
global $CONFIG, $SMARTY, $DBobject;

foreach($CONFIG->section as $sp){
	if ($sp->url == 'orders') {
		$sp->table->where = "cart_closed_date IS NOT NULL AND ";
		$record = new Record($sp);
		$list = $record->getRecordList();
	}
}
$popoverShopCart= $SMARTY->fetch('templates/popover-shopping-cart.tpl');