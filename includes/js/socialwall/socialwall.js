	    var latestRequest = '';
	    var type = 0;
	    var scrolling = false;
	    var groupNumber = 1 ;
	    var item_obj = false;
	    var $container = $('#container1');


	    $(document).ready(function(){
	    	 $container.isotope();
		    $('#filters a').click(function(){
				  $('.btn_element').removeClass('selected');
				  $(this).children('div').addClass('selected');
	
				  if( $(this).attr('data-type')  == 0 || $(this).attr('data-type')  == undefined){
					  $('.socialwall').find('item').show();
					  $container.isotope( {filter : '*'} );
				  }else{
					  type = $(this).attr('data-type') ;
					  var c = $(this).attr('data-filter');
					  $('.socialwall').find('item').hide();
					  $('.socialwall').find(c).show();
					  $container.isotope( {filter : c} );
				  }
				  return false;
			});

		    $(window).scroll(function() {
				if($(window).scrollTop() + $(window).height() + 100 >= $(document).height()) {
			 		  if(scrolling == false){
			 			 $("#load-element").show();
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
					    }).always(function(){
					    	 $("#load-element").hide();
					    });
					}
				}
			});
			$(document).bind('DOMSubtreeModified', function() {
				if($(window).scrollTop() + $(window).height() + 100 >= $(document).height()) {
			 		  if(scrolling == false){
			 			 $("#load-element").show();
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
					    }).always(function(){
					    	 $("#load-element").hide();
					    });
					}
				}
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
			   	}).done(function() { $("#load-element").hide();  $container.isotope(); });
	    }

	    
		function loadItems(){
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
			   }).done(function(data) {
				      $("#load-element").hide();
				      if( $(this).attr('data-type')  == 0 || $(this).attr('data-type')  == undefined){
						  $('.socialwall').find('item').show();
						  $container.isotope( {filter : '*'} );
					  }else{
						  type = $(this).attr('data-type') ;
						  var c = $(this).attr('data-filter');
						  $('.socialwall').find('item').hide();
						  $('.socialwall').find(c).show();
						  $container.isotope( {filter : c} );
					  }
			   });
		}

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

