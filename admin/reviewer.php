<?php
$pageid=1;
set_include_path($_SERVER['DOCUMENT_ROOT']);
include 'admin/includes/templates/header.php';
echo '<h3>Them Digital Issue Reviewer</h3>';
echo '<iframe src="http://bugs.themserver.com/login.php?return=iframe_view_all_bug_page.php&username=funk&password=funkcoffee" width="100%" height="1250px"><p>Your browser does not support iframes.</p></iframe>';
include 'admin/includes/templates/footer.php';
