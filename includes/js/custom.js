jQuery(document).ready(function($){

	if (matchMedia) {
		var mq = window.matchMedia("(max-width: 767px)");
		mq.addListener(WidthChange);
		WidthChange(mq);
	}

	function WidthChange(mq) {

	if (mq.matches) {
		if(!$("#footbuttop").hasClass('has-footbut') )
			$("#footbuttop").addClass('has-footbut').append($("#footbut").html());
	}
	else{
		if(!$("#footbut").hasClass('has-footbuttop') && !$("#footbuttop").hasClass('has-footbut') )
		$("#footbut").addClass('has-footbuttop').append($("#footbuttop").html());
	}

	}

    $(window).scroll(function() {
	wid=jQuery(window).width();
    if ($(this).scrollTop()) {
    	if (wid<767){
        $('#totop:hidden').stop(true, true).fadeIn();
        }
    } else {
        $('#totop').stop(true, true).fadeOut();
    }
	});


	$("#totop").click(function(){
		    $('body,html').animate({scrollTop: 0},600);
	});

});