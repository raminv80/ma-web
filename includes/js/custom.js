(function(e){e.fn.visible=function(t,n,r){var i=e(this).eq(0),s=i.get(0),o=e(window),u=o.scrollTop(),a=u+o.height(),f=o.scrollLeft(),l=f+o.width(),c=i.offset().top,h=c+i.height(),p=i.offset().left,d=p+i.width(),v=t===true?h:c,m=t===true?c:h,g=t===true?d:p,y=t===true?p:d,b=n===true?s.offsetWidth*s.offsetHeight:true,r=r?r:"both";if(r==="both")return!!b&&m<=a&&v>=u&&y<=l&&g>=f;else if(r==="vertical")return!!b&&m<=a&&v>=u;else if(r==="horizontal")return!!b&&y<=l&&g>=f}})(jQuery)

jQuery(document).ready(function($){
	$(window).scroll(function(){
	
	
	
	if($(window).width() > 767){
    if  ($(window).scrollTop() >= 400){
		 	$("#topfix").addClass('contract');
//		 	$('#menu2out').css({position:'fixed',top:'155px','z-index': '1000'});
    } else {
		 $("#topfix").removeClass('contract');
//		 $("#menu2out").removeClass('contract');
//         $('#menu2out').css({position:'relative', top:'auto','z-index': 'auto'});
        }
    }
	});
	
	if($("#desc").length > 0){
	if($(window).width() < 768){
	$('#desc').readmore({
		speed: 75,
		maxHeight: 150
	});	
	}
	}
	
	if($(".introtext").length > 0){
	if($(window).width() < 768){
	$('.introtext').readmore({
		speed: 75,
		maxHeight: 140
	});	
	}
	}
	
	if($(window).width() < 992){
		if (!$("#topfix ul.nav").hasClass("resized")){
		var menitems=$("#mainmenu").html();
		$("#topfix ul.nav").prepend(menitems);
		$("#topfix ul.nav").addClass("resized");
		}
	}

	if($("#maincont2").length > 0){
	if($(window).width() > 767){
	if($("#maincont2 p").length > 0 && !$("#maincont2 p").visible()){
		var contenttop=$("#menu2out").offset();
		$('html,body').animate({scrollTop:contenttop.top-100 },1000);
	}
	}
	}
	
	if($("#introcont").length > 0){
	if($(window).width() > 767){
	if($("#introcont").length > 0 && !$("#introcont p").visible()){
		var contenttop=$("#introcont").offset();			
		$('html,body').animate({scrollTop:contenttop.top-100 },1000);
	}
	}
	}
	
	if (matchMedia) {
		var mq = window.matchMedia("(max-width: 767px)");
		mq.addListener(WidthChange);
		WidthChange(mq);
	}

	function WidthChange(mq) {

	if (mq.matches) {
    var agent = navigator.userAgent;      
    var isWebkit = (agent.indexOf("AppleWebKit") > 0);      
    var isIPad = (agent.indexOf("iPad") > 0);      
    var isIOS = (agent.indexOf("iPhone") > 0 || agent.indexOf("iPod") > 0);     
    var isAndroid = (agent.indexOf("Android")  > 0);     
    var isNewBlackBerry = (agent.indexOf("AppleWebKit") > 0 && agent.indexOf("BlackBerry") > 0);     
    var isWebOS = (agent.indexOf("webOS") > 0);      
    var isWindowsMobile = (agent.indexOf("IEMobile") > 0);     
    var isSmallScreen = (screen.width < 767 || (isAndroid && screen.width < 1000));     
    var isUnknownMobile = (isWebkit && isSmallScreen);     
    var isMobile = (isIOS || isAndroid || isNewBlackBerry || isWebOS || isWindowsMobile || isUnknownMobile);     
    
    
    		if($(".account").length > 0){
		$(".mobaccount").append("<div class='row' id='namedesc1'></div>");
		$("#namedesc1").append($("#namedesc"));
		$("#namedesc1").append($("#special"));
		$("#namedesc1").append($("#video"));
		}

		if(isMobile){
		if($(".detail").length > 0){
		$(".mobdetail").append($("#prodname1"));
		$(".mobdetail").append($("#prodimg1"));
		$(".mobdetail").append("<div class='row' id='mobdet1'></div>");
		$("#mobdet1").append($("#movblk"));		
		$("#mobdet1").append($("#desc"));
		$(".mobdetail").append($("#prodspecs"));
		$(".mobdetail").append($("#colorbond"));
		}
		

		
		}

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
		    $('body').animate({scrollTop: 0},600);
	});
	
	
	$("#contmenu").click(function(){
		if($("#topfix").hasClass("contract")){
		 $("#topfix").removeClass('contract');
		 $("#menu2out").removeClass('contract');
		 $("#menu2out").css("top","245px");
		}else{
		 $("#topfix").addClass('contract');
		 $("#menu2out").addClass('contract');
		 $("#menu2out").css("top","155px");
		}
	});
	
	$(window).resize(function(){
	if($(window).width() < 992){
		if (!$("#topfix ul.nav").hasClass("resized")){
		var menitems=$("#mainmenu").html();
		$("#topfix ul.nav").prepend(menitems);
		$("#topfix ul.nav").addClass("resized");
		}
	}else{
		$('#topfix ul.nav li:not(.topitems)').remove();
		$("#topfix ul.nav").removeClass("resized");
	}
	
	});
	
	if($("#prodtabs").length > 0){		
		$('#prodtabs a').click(function (e) {
			e.preventDefault()
			$(this).tab('show')
		});
		
		fakewaffle.responsiveTabs(['xs']);
		
	}
	
	$("#navop").click(function(){
		if($("#prodnav").is(":visible")){
			$("#prodnav").hide();
			$("#navop").html("click to view product categories");
		}
		else{
			$("#prodnav").show();
			$("#navop").html("CLOSE");
		}
	});
	
	$(".media-read").click(function(){
		if($(this).parent().find(".media-text").is(":visible")){
			$(this).parent().find(".media-text").hide();
			$(this).html("read more >");
		}
		else{
			$(this).parent().find(".media-text").show();
			$(this).html("< read less");
		}
	});
	
	if($("#prodnav").length > 0){
	$("#prodnav").navgoco({accordion:true});
	}
	
	var slideshows = $('.cycle-slideshow').on('cycle-next cycle-prev', function(e, opts) {
    // advance the other slideshow
    slideshows.not(this).cycle('goto', opts.currSlide);
	});

	$('#cycle-2 .cycle-slide').click(function(){
    var index = $('#cycle-2').data('cycle.API').getSlideIndex(this);
    slideshows.cycle('goto', index);
	});
	

	

	
});