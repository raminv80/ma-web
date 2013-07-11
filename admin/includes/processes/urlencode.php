<?php

set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once 'database/utilities.php';

$name = $_POST['value'];

$res = urlSafeString($name);

echo json_encode(array("url"=>$res));
die();
