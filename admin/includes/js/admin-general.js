
jQuery.ajaxSetup ({
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
	jQuery('#log').dialog({ modal: true ,resizable: false, draggable: false,title: 'login...'} );
	jQuery('#log').html('<div id=\"result\"><h3>Please Wait..</h3><img src=\"images/loading.gif\" alt=\"loading...\"></div>');
	jQuery('#result').load('/admin/includes/processes/processes-ajax.php', { email:email, password:pass ,Action:'AdminLogIn',form:jQuery('#formToken').val()});
}
function Login(){
	if( jQuery('#username').val()  != '' && jQuery('#password').val() != '' ){
		checkuser(jQuery('#username').val(),jQuery('#password').val());
	}else{
		alert('Please complete all fields');
	}
}

function CreateCSV(misc,simple){
	if(simple	!=	'1'){
		simple	=	'0';
		
	}
	
	if(misc){
		jQuery("#maincontent").append("<div id='csv'></div>");
		var idd = jQuery("#formToken").val();
		var url = '/admin/includes/processes/csv_generator.php?id='+idd+'&misc='+misc+'&s='+simple;		
		var windowName = 'Page';
		window.open(url, windowName, 'width=200,height=200');
	}
	
}

function DE(entry_id){
	var con = confirm('Do you want to delete this entry ? ');
	if(con ==  true){
	jQuery('#entry').val(entry_id);
	jQuery('#DeleteEntry').submit();
	}
}	

jQuery("#password").keyup(function(event){
	if(event.keyCode == 13){
	    jQuery("#logme").click();
	}
});

jQuery(function() {
	jQuery( '#bar-menu' ).accordion({   collapsible: true,
									  active: false	,
									  autoHeight: false,
									  navigation: true,
									  icons:false,
									animated: 'bounceslide'});
	jQuery( '#user-bar-menu' ).accordion({   collapsible: true,
									  active: false	,
									  autoHeight: false,
									  navigation: true,
									  icons:false,
									animated: 'bounceslide'});
	jQuery( '#cart-bar-menu' ).accordion({   collapsible: true,
									  active: false	,
									  autoHeight: false,
									  navigation: true,
									  icons:false,
									animated: 'bounceslide'});
});
var  $dialog;
function AlertD(text){
	 $dialog = $('<div  style="margin-left:50px;">'+text+'</div>').dialog({
			autoOpen: false,
			height:125,
			width: 500,
			modal: false,
			hide: "explode",
			position: "top",
			dialogClass: "alert"
		});
	 $dialog.dialog('open');
	 CloseD();
}
function CloseD(){
	setTimeout(function(){$dialog.dialog("close")},3000);
}
	