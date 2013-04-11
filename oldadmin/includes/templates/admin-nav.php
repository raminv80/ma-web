<?php ini_set('display_errors',1);
?>
<DIV ID="admin-nav">
	Welcome
	<?php echo $_SESSION["admin"]["name"]?>
	 /<A HREF='/admin/index.php'>Logout</A> <BR>
	 <a href='/admin/reporter.php'>Report an Issue</a> / <a href='/admin/reviewer.php'>Review Issues</a>  <BR><BR>




<?php
 	echo "<div id='bar-menu'>";
	/*$misc_id	=	'1'; ///////
	echo "<h3>ADMIN PAGES</h3>
				<div><ul id='nav-list'>";
	echo"<li><a href='list_misc.php?misc=$misc_id' class='list-header'><b>ALL</b></a></li>";
	$pages_obj = new misc($misc_id);
	$pages_arr=$pages_obj->get_misc(0,'',0,'','page_name');
	if($pages_arr){
		foreach ($pages_arr as $page) {
			echo"<li><a href='edit_misc.php?id=$page[page_id]&misc=$misc_id'>$page[page_name]</a></li>";
		}
	}
	echo "</ul></div>";

	$misc_id	=	'8'; ///////
	echo"<h3>FIELDS</h3>
					<div><ul id='nav-list'>";
	echo"<li><a href='list_misc.php?misc=$misc_id' class='list-header'><b>ALL</b></a></li>";
	$misc_obj = new misc($misc_id);
	$misc_arr=$misc_obj->get_misc(0,'',0,'','field_title');
	if($misc_arr){
	foreach ($misc_arr as $misc) {
	echo"<li><a href='edit_misc.php?id=$misc[field_id]&misc=$misc_id'>$misc[field_title]</a></li>";
	}
	}
	echo "</ul></div>";*/


	$misc	=	'2'; ///////
	echo"<h3>ADMIN</h3>
				<div><ul id='nav-list'>";
	echo"<li><a href='list_misc.php?misc=$misc' class='list-header'><b>ALL</b></a></li>";
	$pages_obj = new misc($misc);
	$pages_arr=$pages_obj->get_misc();
	if($pages_arr){
	foreach ($pages_arr as $page) {
	echo"<li><a href='edit_misc.php?id=$page[admin_id]&misc=$misc'>$page[admin_name]</a></li>";
	}
	}
	echo "</ul></div>";

	$misc_id	=	'1'; ///////
	echo"<h3>PAGES</h3>
			<div><ul id='nav-list'>";
	echo"<li><a href='list_page.php?misc=$misc_id' class='list-header'><b>ALL</b></a></li>";
	$misc_obj = new misc($misc_id);
	$misc_arr=$misc_obj->get_misc(0,'',0,'','page_name');
	if($misc_arr){
		foreach ($misc_arr as $misc) {
			echo"<li><a href='edit_page.php?id=$misc[page_id]&misc=$misc_id'>$misc[page_name]</a></li>";
		}
	}
	echo "</ul></div>";
	$misc_id	=	'69'; ///////
	echo"<h3>BULLETINS</h3>
			<div><ul id='nav-list'>";
	
	$misc_obj = new misc($misc_id);
	$misc_arr=$misc_obj->get_misc(0,'',0,'','page_name');
	echo"<li><a href='list_misc.php?misc=$misc_id' class='list-header'><b>ALL</b></a></li>";
	echo "</ul></div>";
	$misc_id	=	'71'; ///////
	echo"<h3>VIDEOS</h3>
			<div><ul id='nav-list'>";
	
	$misc_obj = new misc($misc_id);
	$misc_arr=$misc_obj->get_misc(0,'',0,'','page_name');
	echo"<li><a href='list_misc.php?misc=$misc_id' class='list-header'><b>ALL</b></a></li>";
	echo "</ul></div>";
	
		$misc_id	=	'72'; ///////
	echo"<h3>NEWS</h3>
			<div><ul id='nav-list'>";
	
	$misc_obj = new misc($misc_id);
	$misc_arr=$misc_obj->get_misc(0,'',0,'','page_name');
	echo"<li><a href='list_misc.php?misc=$misc_id' class='list-header'><b>ALL</b></a></li>";
	echo "</ul></div>";

			$misc_id	=	'73'; ///////
	echo"<h3>FAQ</h3>
			<div><ul id='nav-list'>";
	
	$misc_obj = new misc($misc_id);
	$misc_arr=$misc_obj->get_misc(0,'',0,'','page_name');
	echo"<li><a href='list_misc.php?misc=$misc_id' class='list-header'><b>ALL</b></a></li>";
	echo "</ul></div>";
	
				$misc_id	=	'74'; ///////
	echo"<h3>REPS</h3>
			<div><ul id='nav-list'>";
	
	$misc_obj = new misc($misc_id);
	$misc_arr=$misc_obj->get_misc(0,'',0,'','page_name');
	echo"<li><a href='list_misc.php?misc=$misc_id' class='list-header'><b>ALL</b></a></li>";
	echo "</ul></div>";
	
				$misc_id	=	'70'; ///////
	echo"<h3>USERS</h3>
			<div><ul id='nav-list'>";
	
	$misc_obj = new misc($misc_id);
	$misc_arr=$misc_obj->get_misc(0,'',0,'','page_name');
	echo"<li><a href='list_misc.php?misc=$misc_id' class='list-header'><b>ALL</b></a></li>";
	echo "</ul></div>";
	
	echo "</div>";
 
?>

</DIV>
<!-- admin-nave -->
<DIV ID='maincontent'>