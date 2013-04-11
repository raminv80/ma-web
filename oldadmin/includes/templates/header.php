<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include 'oldadmin/includes/functions/admin-functions.php';
if(empty($_SESSION["admin"]["id"])){
	die("<script>document.location.href='index.php'</script>");
	header('Location: /admin/index.php');
}

?>
<html>
<head>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />		
		<meta name="Distribution" content="Global" />
		<meta name="Robots" content="index,follow" />
				
		<script type="text/javascript" src="/admin/includes/js/jq.js"></script>	
		<script type="text/javascript" src="/admin/includes/js/jqui.js"></script>	
		<script type="text/javascript" src="/admin/includes/js/admin-general.js"></script>	

		<link  type="text/css" href="/admin/includes/css/jqui.css" rel="stylesheet"></link>
		<link  type="text/css" href="/admin/includes/css/admin.css" rel="stylesheet"></link>
		
		<script type="text/javascript" src="/admin/includes/js/tablesorter/jquery.tablesorter.min.js"></script>
		<link  type="text/css" href="/admin/includes/js/tablesorter/style.css" rel="stylesheet"></link>
		<title>Website administration</title>
</head>
<body>		
<div id='container'>
 <div class="grid_5 left" id="logo">
        <h1><span class="dark-green">All</span> <span class="light-green">Fresh</span></h1>
        <h2>fruit &amp; veg</h2>
    </div><!-- end of logo -->
<div id="wrapper">
<?php include 'oldadmin/includes/templates/admin-nav.php';?>
