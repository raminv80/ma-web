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

$(window).load(function(){

		wid=$(window).width();
		if (wid>767){

        var $container = $('#portfolio-wrapper');
          // object that will keep track of options
          // defaults, used if not explicitly set in hash
           $container.isotope({
            filter: '*',
            getSortData : {
				name : function ( $elem ) {
					return $elem.find('.mitemtitle').text();
					}
			},
            sortBy: 'name',
            layoutMode: 'masonry',
            masonry:{
                columnWidth: 290,
                rowHeight: 290
            }
          });

      

	// filter items when filter link is clicked
	$('#menulist a').click(function(){
		$("#menulist a").removeClass('selected');
		$(this).addClass('selected');
		var selector = $(this).attr('data-option-value');
		selector=selector.substring(1);
		$.bbq.pushState( '#'+selector );
		$('#portfolio-wrapper').animate({ "marginTop":  "0px" }, "fast");
        return false;
	});

      $(window).bind( 'hashchange', function( event ){
        // get options object from hash
        var hashOptions = window.location.hash;
		hashOptions=hashOptions.substring(1);
		if (hashOptions == ''){
			hashOptions="favourites";
		}
		var hash1 = "."+hashOptions;
		
		$("#menulist a").removeClass('selected');
		$("#"+hashOptions+"").addClass('selected');
        // apply options from hash
        $container.isotope( {filter : hash1},
            function($changedItems, instance){
            instance.$allAtoms.removeClass('is-filtered');
            $("#count").html((instance.$filteredAtoms).length+' results');
            });
        // save options
    
        // if option link was not clicked
        // then we'll need to update selected links
		})
        // trigger hashchange to capture any hash data on init
        .trigger('hashchange');
        
        $container.isotope();
        
            $(".tags div").tipTip({defaultPosition:'top'});
		}
		else{		
			$("#menulistmobile").change(function(){
				var catg=$(this).find("option:selected").attr("value");
				$(".portfolio-item").css("display","none");
				$("."+catg).css("display","block");
			});
			
		}

});