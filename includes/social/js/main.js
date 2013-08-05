	    var latestRequest = '';
	    var type = 0;
	    var scrolling = false;
	    var groupNumber = 1 ;
	    var item_obj = false;
	    var $container = $('#container1');


	    $(document).ready(function(){
	    	Remove();
	    	$("a[rel^='prettyPhoto']").prettyPhoto();
		    loadItems();

		    setTimeout(function(){
		    	if( typeof(item_obj) == "object"){ $container.isotope( 'reLayout');}
		    	Remove();
	    	},6000);
	    	setTimeout(function(){
	    		if( typeof(item_obj) == "object"){ $container.isotope( 'reLayout');}

	    	},9000);
	    	setTimeout(function(){
	    		if( typeof(item_obj) == "object"){ $container.isotope( 'reLayout');}

	    	},12000);

	    	window.setInterval(function(){
	    		console.log('interval every 3000000 sec');
	    		resetIsotope();
	    		Remove();

	    	},300000);



		    $("a[rel^='prettyPhoto']").prettyPhoto();
		    /*** FILTERS WITHOUT REFRESH ***/
		    /*
		    $('#filters a').click(function(){
					  $('.btn_element').removeClass('selected');
					  $(this).children('div').addClass('selected');
					  var selector = $(this).attr('data-filter');
					  $('#container1').isotope({ filter: selector });
					  $('.btn-navbar').trigger('click');
					  return false;
			});
			//*/
		    /*** FILTERS WITH REFRESH ***/
		    //*
		    $('#filters a').click(function(){
					  $('.btn_element').removeClass('selected');
					  $(this).children('div').addClass('selected');

					  console.log( $(this).attr('data-type') );

					  if( $(this).attr('data-type')  == 0 || $(this).attr('data-type')  == undefined){
						  document.location.reload(true);
					  }else{
						  $container.html('');
					      $container.isotope('destroy');
					      $("#load-element").show();

						  type = $(this).attr('data-type') ;
						   groupNumber = 1 ;
						   $.post("/includes/processes/processes-general.php",{ action:"rollback",datetime:latestRequest,limit:groupNumber,type:type },function(result){
						 	 var obj = $.parseJSON(result);
						 	 var html = $(obj.html);
						 	 			$container.html(html);
								     // $container.isotope('destroy');
								     item_obj =  $('#container1').isotope({
										  itemSelector : '.item',
										  layoutMode : 'masonry'
									  });
						   	}).always(function() { $("#load-element").hide();  $container.isotope(); });
					  }
					  return false;
			});
			//*/




		});

		function loadItems(){
			 $.post("/includes/processes/processes-general.php",{ action:"load" },function(result){
				 var obj = $.parseJSON(result);
				  $container.html(obj.html);
				 item_obj =  $('#container1').isotope({
						  animationOptions: {
			              	duration: 750,
						  	easing: 'linear',
						  	queue: false
						  },
						  itemSelector : '.item',
						  layoutMode : 'masonry'
					  });
					  latestRequest = obj.datetime;
					  $container.isotope( 'reLayout');
			   }).done(function(data) {
				      $("#load-element").hide();
					  $("a[rel^='prettyPhoto']").prettyPhoto();
			   }).always(function() {  $container.isotope( 'reLayout'); });
		}

		$(window).scroll(function() {
			if($(window).scrollTop() + $(window).height() + 100 >= $(document).height()) {
				  $("#load-element").show();
		 		   $container.isotope( 'reLayout');
		 		  if(scrolling == false){
		 			  scrolling = true;
		 			  groupNumber = groupNumber + 1;
			 		  $.post("/includes/processes/processes-general.php",{ action:"rollback",datetime:latestRequest,limit:groupNumber,type:type },function(result){
			 			 var obj = $.parseJSON(result);
			 			 var html = $(obj.html);
			 			 latestRequest = obj.datetime;
						 $container.isotope( 'insert', html );
						 setTimeout(function(){ scrolling = false;},500);
				      }).done(function(data) {
				    	  $("#load-element").hide();
				    	   $container.isotope( 'reLayout');

				    }).always(function() {  $container.isotope( 'reLayout');  });
				}
			}
			 $container.isotope( 'reLayout');
			$("a[rel^='prettyPhoto']").prettyPhoto();
		  });

		function resetIsotope(){
			   	   $("#load-element").show();
			       console.log('update');
			       latestRequest = '';
			       groupNumber = 1 ;
				   $.post("/includes/processes/processes-general.php",{ action:"update",datetime:latestRequest,limit:groupNumber },function(result){
				 			 var obj = $.parseJSON(result);
				 			 var html = $(obj.html);
				 			 groupNumber =  obj.groupNumber;
							 latestRequest = obj.datetime;
						      $container.prepend(html);
						      $container.isotope('destroy');
						     item_obj =  $('#container1').isotope({
								  itemSelector : '.item',
								  layoutMode : 'masonry'
							  });
				   	}).always(function() { $("#load-element").hide();  $container.isotope(); });
		}

		function Remove(){
			 $('.trash').click(function(){
					var element = $(this).parent('div');
					var item_id = $(this).parent('div').prop('id');
						 var r=confirm("Do you want to completly remove this item from the SocialWall");
						 if(r  ==  true){
							 $.post("/includes/processes/processes-general.php",{ action:"delete",item:item_id},function(result){
								 if(result == 1){
									  $container.isotope( 'remove', element );
								 }else{
									 alert(result);
								 }
							 });
						 }else{
							 return false;
						 }
			 	});
		}
		function QRemove(id){

			var element = $('#'+id);
			var item_id = id;
				 var r=confirm("Do you want to completly remove this item from the SocialWall");
				 if(r  ==  true){
					 $.post("/includes/processes/processes-general.php",{ action:"delete",item:item_id},function(result){
						 if(result == 1){
							  $container.isotope( 'remove', element );
						 }else{
							 alert(result);
						 }
					 });
				 }else{
					 return false;
				 }

}
