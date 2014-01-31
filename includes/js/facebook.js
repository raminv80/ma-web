window.fbAsyncInit = function() {
	FB.init({
		appId : '208591239336752',
		status : true, // check login status
		cookie : true, // enable cookies to allow the server to access the session
		xfbml : true
	// parse XFBML
	});

	FB.Event.subscribe('auth.authResponseChange', function(response) {

		if (response.status === 'connected') {

			if (fBlogged == false) {
				FBUserData();
			} 
			
		} else {
			FB.login();
		}
	});
};

// Load the SDK asynchronously
(function(d) {
	var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	if (d.getElementById(id)) {
		return;
	}
	js = d.createElement('script');
	js.id = id;
	js.async = true;
	js.src = "//connect.facebook.net/en_US/all.js";
	ref.parentNode.insertBefore(js, ref);
}(document));


function FBlogout(){
	FB.logout(function(response) {
		$.ajax({
			type: "POST",
		    url: "/process/user",
			cache: false,
			data: 'action=FBlogout',
			dataType: "html",
		    success: function(data) {
		    	try{
		    		var obj = $.parseJSON(data);
		    		console.log(obj.response);
				 	$('body').css('cursor','default'); 
				 	location.reload();
				}catch(err){
					console.log('TRY-CATCH error');
					$('body').css('cursor','default'); 
				}
		    },
			error: function(){
				console.log('AJAX error');
				$('body').css('cursor','default'); 
	      	}
		});
	});
}

function FBUserData(){
	$('body').css('cursor','wait');
	
	FB.api('/me', function(response) {
		var fbdata = JSON.stringify(response);
		$.ajax({
			type: "POST",
		    url: "/process/user",
			cache: false,
			data: 'action=FBlogin&fbdata=' + fbdata,
			dataType: "html",
		    success: function(data) {
		    	try{
		    		var obj = $.parseJSON(data);
		    		window.location.replace(obj.url);
				 	
		    		
				 	$('body').css('cursor','default'); 
				}catch(err){
					console.log('TRY-CATCH error');
					$('body').css('cursor','default'); 
				}
		    },
			error: function(){
				console.log('AJAX error');
				$('body').css('cursor','default'); 
	      	}
		});
		
	});
	
	

}