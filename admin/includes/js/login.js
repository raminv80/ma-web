/**
 * 
 */
$(document).ready( function(){
	$("#password").keyup(function(event){
		if(event.keyCode == 13){
	    	Login();
	    }
	});
		
	$.ajaxSetup ({  
	    cache: false  
	});  
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
	$('#log').dialog({modal: true ,resizable: false, draggable: false,title: 'login...'} );	 
	$('#log').html('<div id="result"><h3>Please Wait..</h3><img src="images/loading.gif" alt="loading..."></div>');	
	$('#result').load('/admin/includes/processes/processes-login.php', {email:email, password:pass ,token:$('#formToken').val()});
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