function validateSubmit(id){
	var error = false;
	$(".req").each(function(){

  		if($(this).val()===""){
  			$(this).closest('.row-fluid').addClass("error");
  			error = true;
  		}else{
  			$(this).closest('.row-fluid').removeClass("error");
  		}
  		if($(this).is(':checkbox') == true	){
  			if($(this).is(":checked") != true ){
  				$(this).closest('.row-fluid').addClass("error");
      			error = true;
      		}else{
      			$(this).closest('.row-fluid').removeClass("error");
      		}           		 
          }
  	});
	//if(error == false){
		$(".email").each(function(){
	  		var filter =   /^[a-zA-Z0-9_\+-]+(\.[a-zA-Z0-9_\+-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.([a-zA-Z]{2,4})$/;
	  		if (!filter.test($(this).val()) && $(this).val() != "") {
	  			$(this).closest('.row-fluid').addClass("error");
	  			error = true;
	  		}else{
	  			$(this).closest('.row-fluid').removeClass("error");
	  		}
	  	});
	//}
	
	if(typeof $("[name=user_password]") == "object" && $("[name=user_password]").val() == '' ){
		$("[name=user_password]").closest('.row-fluid').addClass("error");
		error = true;
	}
	if(typeof $("[name=user_password_confirm]") == "object" && $("[name=user_password_confirm]").val() == '' ){
		$("[name=user_password_confirm]").closest('.row-fluid').addClass("error");
		error = true;
	}
	if(typeof $("[name=user_password]") == "object" && typeof $("[name=user_password_confirm]") == "object" && $("[name=user_password]").val() != $("[name=user_password_confirm]").val()){
		$("[name=user_password]").closest('.row-fluid').addClass("error");
		$("[name=user_password_confirm]").closest('.row-fluid').addClass("error");
		error = true;
		alert('Please check passwords match');
	}
	
	if(!error){
		$('#'+id).submit();
	}
}

function validateForm(){
	var error = true;
	$(" .req").each(function(){
		if($(this).val()===""){
			$(this).closest('.row-fluid').addClass("error");
			error = false;
		}else{
			$(this).closest('.row-fluid').removeClass("error");
		}
	});
	$(" .email").each(function(){
		var filter =   /^[a-zA-Z0-9_\+-]+(\.[a-zA-Z0-9_\+-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.([a-zA-Z]{2,4})$/;
		if (!filter.test($(this).val())) {
			$(this).closest('.row-fluid').addClass("error");
			error = false;
		}else{
			$(this).closest('.row-fluid').removeClass("error");
		}
	});
	return error;
}