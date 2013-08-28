
$(window).load(function(){
	
	loadMap();

	// filter items when filter link is clicked
	$('#statelist a').click(function(){
		$("#statelist a").removeClass('selected');
		$(this).addClass('selected');
		var selector = $(this).attr('data-option-value');
		selector=selector.substring(1);
		$.bbq.pushState( '#'+selector );
        return false;
	});
	

    $(window).bind( 'hashchange', function( event ){
        // get options object from hash
        var hashOptions = window.location.hash;
		hashOptions=hashOptions.substring(1);
		if (hashOptions == ''){
			hashOptions="all";
		}
		
		$("#statelist a").removeClass('selected');
		$("#"+hashOptions+"").addClass('selected');
		$(".loc").hide();
		$("."+hashOptions+"").show();
		
		deleteOverlays();
		$(".loc:visible").each(
			function(){
				// Define Marker properties
			    var pin = new google.maps.MarkerImage($(this).attr('pin'),
			        // This marker is 129 pixels wide by 42 pixels tall.
			        new google.maps.Size(77, 100),
			        // The origin for this image is 0,0.
			        new google.maps.Point(0,0),
			        // The anchor for this image is the base of the flagpole at 18,42.
			        new google.maps.Point(18, 42)
			    );
				var marker;
				var latlng = new google.maps.LatLng($(this).attr('latitude'),$(this).attr('longitude'));
			    marker = new google.maps.Marker({
			    	position: latlng,
			        map: map,
			        title: $(this).attr('title'),
			        icon: pin
			    });
			    markers.push(marker);
			});
		resetBounds();
    
        // if option link was not clicked
        // then we'll need to update selected links
	}).trigger('hashchange');

});


var map;
var markers = [];
var bound = new google.maps.LatLngBounds();
 
function loadMap() {
	var startLatlng = new google.maps.LatLng(-34.9201702,138.5943725);
	var options = {
	    zoom: 12,
	    center: startLatlng,
	    mapTypeId: google.maps.MapTypeId.ROADMAP,
	    panControl: true,
	    zoomControl: true,
	    maxZoom: 19,
	    minZoom: 0
	  };
	map = new google.maps.Map(document.getElementById("map"),options);
}

function deleteOverlays() {
	 if (markers) {
	   for (i in markers) {
		   markers[i].setMap(null);
	   }
	   markers.length = 0;
	 }
}

function resetBounds() {
	bound = new google.maps.LatLngBounds();
	for(var i in markers)
	{
		bound.extend(markers[i].getPosition());
	}
	map.fitBounds(bound);
}

//Nick - Them Digital