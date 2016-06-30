<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once 'admin/includes/functions/admin-functions.php';

global  $DBobject;

echo "Emails sent: ". sendBulkMail();
echo "<br/>Cron ended";
die();
