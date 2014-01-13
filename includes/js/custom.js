function animateContent(direction) {  
    var animationOffset = $('#newsbox').height() - $('#newscontainer').height();
    if (direction == 'up') {
        animationOffset = 0;
    }

    $('#newscontainer').animate({ "marginTop": animationOffset + "px" }, "fast");
}

function animateContent1(direction) {  
    var animationOffset = $('#menuout').height() - $('#portfolio-wrapper').height();
    var mtop=$('#portfolio-wrapper').css('marginTop');
    mtop=parseInt(mtop);

    if (direction == 'up1') {
    	m1=mtop+740;
    	if (m1 <= 0){
	    	$('#portfolio-wrapper').animate({ "marginTop": m1 + "px" }, "fast");
	    }
	    else{
	    	$('#portfolio-wrapper').animate({ "marginTop":  "0px" }, "fast");
	    }
    }
	else{
	   	m1=mtop-740;
	   	m2=-($('#portfolio-wrapper').height()-291);
	   	if (m1 > m2){
		    $('#portfolio-wrapper').animate({ "marginTop": m1 + "px" }, "fast");
	    }
	    else{
		    $('#portfolio-wrapper').animate({ "marginTop": m2 + "px" }, "fast");
	    }
	}
}

jQuery(document).ready(function($){
	
        $("#down").click(function () {
            animateContent("down");
        });

        $("#up").click(function () {
            animateContent("up");
        });
        
        	
        $("#down1").click(function () {
            animateContent1("down1");
        });

        $("#up1").click(function () {
            animateContent1("up1");
        });
        
    $("#potmbutton").click(function(){
    	if ($("#potm").is(":visible")){
			$("#potm").slideUp();
    	}
    	else{
			$("#potm").slideDown();
		}
	    
    });
        
    $(".small-images a").click(function(e){
        var href = $(this).attr("href");
        $("#big-image img").attr("src", href);
        e.preventDefault();
        return false;
    });
    
    	
    $("#menu2 ul li").hover(function(){
	    $(this).find('ul').show();
    },function(){
	    $(this).find('ul').hide();
    });
    
    $("#menu-icon").on("click", function(){
		$("#menu-top").slideToggle();
		$(this).toggleClass("active");
	});
	
	$("#locationbox .span5 small a").text("View Map");



});

