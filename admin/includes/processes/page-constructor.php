<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include "admin/includes/functions/admin-functions.php"; //admin area specific
$DB = new DBmanager();

$sql = "INSERT INTO tbl_page (page_name) VALUES ('NEW-PAGE')";
$res = $DB->wrappedSql($sql);
$id = $DB->wrappedSqlIdentity();
$sql = "INSERT INTO tbl_link_page_field (link_page_id, link_field_id) VALUES ('{$id}','25')";
$res = $DB->wrappedSql($sql);

header("Location: /admin/list_page.php?misc=1");