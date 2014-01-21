	    var latestRequest = '';
	    var type = 0;
	    var scrolling = false;
	    var groupNumber = 1 ;
	    var item_obj = false;
	    var $container = $('#container1');


	    $(document).ready(function(){
		    loadItems();
		    //StartWithType("1");

		    setTimeout(function(){
		    	if( typeof(item_obj) == "object"){ $container.isotope( 'reLayout');}
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

	    	},300000);



		    $('#filters a').click(function(){
			  $('.btn_element').removeClass('selected');
			  $(this).children('div').addClass('selected');

			  if( $(this).attr('data-type')  == 0 || $(this).attr('data-type')  == undefined){
				  loadItems();
			  }else{
				  $container.html('');
			      $container.isotope('destroy');
			      $("#load-element").show();

				  type = $(this).attr('data-type') ;
				   groupNumber = 1 ;
				   $.post("/includes/social/processes-general.php",{ action:"rollback",datetime:latestRequest,limit:groupNumber,type:type },function(result){
				 	 var obj = $.parseJSON(result);
				 	 var html = $(obj.html);
				 	 			$container.html(html);
						     item_obj =  $('#container1').isotope({
								  itemSelector : '.item',
								  layoutMode : 'masonry'
							  });
				   	}).always(function() { $("#load-element").hide();  $container.isotope(); });
			  }
			  return false;
			});




		});
	    function StartWithType(type){
	    	  groupNumber = 1 ;
			   $.post("/includes/social/processes-general.php",{ action:"rollback",datetime:latestRequest,limit:groupNumber,type:type },function(result){
			 	 var obj = $.parseJSON(result);
			 	 var html = $(obj.html);
			 	 			$container.html(html);
					     item_obj =  $('#container1').isotope({
							  itemSelector : '.item',
							  layoutMode : 'masonry'
						  });
			   	}).always(function() { $("#load-element").hide();  $container.isotope(); });
	    }

		function loadItems(){
/*			 $container.html('');
		     $container.isotope('destroy');
		     $("#load-element").show();
*/
			 $.post("/includes/social/processes-general.php",{ action:"load" },function(result){
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
			   }).always(function() {  $container.isotope( 'reLayout'); });
		}

		$(window).scroll(function() {
			if($(window).scrollTop() + $(window).height() + 100 >= $(document).height()) {
				  $("#load-element").show();
		 		   $container.isotope( 'reLayout');
		 		  if(scrolling == false){
		 			  scrolling = true;
		 			  groupNumber = groupNumber + 1;
			 		  $.post("/includes/social/processes-general.php",{ action:"rollback",datetime:latestRequest,limit:groupNumber,type:type },function(result){
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
		  });

		function resetIsotope(){
			   	   $("#load-element").show();
			       console.log('update');
			       latestRequest = '';
			       groupNumber = 1 ;
				   $.post("/includes/social/processes-general.php",{ action:"update",datetime:latestRequest,limit:groupNumber },function(result){
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

		function QRemove(id){
			var element = $('#'+id);
			var item_id = id;
				 var r=confirm("Do you want to completly remove this item from the SocialWall");
				 if(r  ==  true){
					 $.post("/includes/social/processes-general.php",{ action:"delete",item:item_id},function(result){
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

