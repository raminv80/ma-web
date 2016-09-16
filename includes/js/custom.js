jQuery(document).ready(function($){

	if (matchMedia) {
		var mq = window.matchMedia("(max-width: 992px)");
		mq.addListener(WidthChange);
		WidthChange(mq);
	}

	function WidthChange(mq) {

	if (mq.matches) {
		$("#mobcart").append($("#cart"));
	}
	else{
		$("#cartout").append($("#cart"));
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

	$("#mobile-navbar li.dropdown").click(function(){
		if($(this).hasClass('open')){
			$(this).removeClass('open');
			$('#mobile-navbar li.dropdown ul.dropdown-menu').css('height','0');
		}
		else{
			$(this).parent().find("li.dropdown").removeClass('open');
			$('#mobile-navbar li.dropdown ul.dropdown-menu').css('height','0');
			$(this).addClass('open');
			var num=$(this).find('ul.dropdown-menu li').size();
			var hei=num*45;
			$(this).find('ul.dropdown-menu').css('height',hei+'px');
		}
	});

});