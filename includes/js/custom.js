function isScrolledIntoView(elem)
{
    var docViewTop = $(window).scrollTop();
    var docViewBottom = docViewTop + $(window).height();

    var elemTop = $(elem).offset().top-394;
    var elemBottom = elemTop + $(elem).height();

    return ((elemBottom <= docViewBottom) && (elemTop >= (docViewTop-394)));
}

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

	if(isScrolledIntoView("#foot1")){
		$("#needhelp").css("position","absolute");
	}
	else{
		$("#needhelp").css("position","fixed");
	}

	});

	$("#helptab").click(function(){
		if($(this).hasClass("open")){
			$("#helpcont").css("height","0");
			$(this).removeClass("open");
			$("#nhicon img").css("margin-top","0");
		}
		else{
			$(this).addClass("open");
			$("#nhicon img").css("margin-top","-35px");
			$("#helpcont").css("height","140px");
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
			var num=$(this).find('ul.dropdown-menu li').length;
			var hei=num*45;
			$(this).find('ul.dropdown-menu').css('height',hei+'px');
		}
	});

	$(".sb-search").click(function(){
		if ($("#mobsearch").hasClass("sb-open")){
			$("#mobsearch").removeClass("sb-open");
			$(".sb-icon-search").show();
			$(".sb-icon-close").hide();
		}
		else{
			$("#mobsearch").addClass("sb-open");
			$(".sb-icon-search").hide();
			$(".sb-icon-close").show();
		}
	});

});
