<?php
session_start();
session_destroy();
set_include_path($_SERVER['DOCUMENT_ROOT']);
include 'admin/includes/functions/admin-functions.php';
?>
<html>
<head>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />		
		<meta name="Distribution" content="Global" />
		<meta name="Robots" content="index,follow" />	
		<script type="text/javascript" src="/admin/includes/js/jq.js"></script>	
		<script type="text/javascript" src="/admin/includes/js/jqui.js"></script>	
		<link  type="text/css" href="/admin/includes/css/jqui.css" rel="stylesheet"></link>
		<link  type="text/css" href="/admin/includes/css/admin.css" rel="stylesheet"></link>
		<title>Website administration</title>		
		<script type="text/javascript">
		$.ajaxSetup ({  
		    cache: false  
		});  
		function checkEmail(email) {
			var filter = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;
			if (!filter.test(email)) {
			alert('Please provide a valid email address');
			return false;
			}else{
				return true;
				}
			}
		function checkuser(email,pass){
		$('#log').dialog({ modal: true ,resizable: false, draggable: false,title: 'login...'} );	 
		$('#log').html('<div id="result"><h3>Please Wait..</h3><img src="images/loading.gif" alt="loading..."></div>');	
		$('#result').load('/admin/includes/processes/processes-ajax.php', { email:email, password:pass ,Action:'AdminLogIn',token:$('#formToken').val()});
		}	
		function Login(){
			if( $('#email').val()  != '' && $('#password').val() != '' ){
				if(checkEmail($('#email').val()) == true){
					checkuser($('#email').val(),$('#password').val());
					}	
			}else{
			alert('Please complete all fields');
			}	
			
		}
		 
				
		</script>
</head>
<body>		
<div id='container'>
		<div id="header">
			<div id="log"></div>
		</div>
		<div class="grid_5 left" id="logo">
	        <h1><span class="dark-green">All</span> <span class="light-green">Fresh</span></h1>
	        <h2>fruit &amp; veg</h2>
	    </div><!-- end of logo -->
		<!-- header -->
		<div id="wrapper">
			<div id="admin-nav">&nbsp;</div>
			<!-- admin-nave -->
			<div id='maincontent'>
				<form>
				<table   class="login-table">
					<tr>
						<td colspan='2'>
						<h3>Login Details</h3>
						</td>
					</tr>
					<tr>
						<td class="td-user">Username:</td>
						<td><input type="text" name="username" id="email"   class="text-box-login"></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input type="password" name="password" id="password" class="text-box-login-password"	 ></td>
					</tr>
					<tr>
						<td>&nbsp;<? echo insertToken(); ?></td>
						<td align="right">
						<img src='/admin/images/login_button.png' id="logme" onclick="Login();"
							alt="Login" title="login" class="login_button"></td>
					</tr>
				</table>
				</form>
		<script>
				$("#password").keyup(function(event){
			if(event.keyCode == 13){
		    	Login();
		    }
		});
				
		</script>
			</div><!-- main content -->
		</div><!-- wrapper -->
	</div><!-- container -->
</body>
</html>

