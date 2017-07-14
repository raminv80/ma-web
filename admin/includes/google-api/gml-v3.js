var map;
var geocoder;//Nick - Them Digital
var address;//Nick - Them Digital
var cenOn;
var markersArray = [];
var infowindow = new google.maps.InfoWindow();
var marker;

function addLoadEvent(func) { 
	var oldonload = window.onload; 
	if (typeof window.onload != 'function'){ 
		window.onload = func
	} else { 
		window.onload = function() {
			oldonload();
			func();
		}
	}
}
 
addLoadEvent(loadMap);
 
function loadMap() {
	var startLatlng = new google.maps.LatLng(-34.9201702,138.5943725);
	var options = {
	    zoom: 12,
	    center: startLatlng,
	    mapTypeId: google.maps.MapTypeId.ROADMAP,
	    panControl: true,
	    zoomControl: true,
	    streetViewControl: false,
	    mapTypeControl: true,
	    scaleControl: true
	  };
	map = new google.maps.Map(document.getElementById("GmlMap"),options);

	//Nick - Them Digital
	google.maps.event.addDomListener(map, "click",function(event){
		placeAddress(event.latLng);
	});
	geocoder = new google.maps.Geocoder();

	if(cenOn!=null){
		showAddress(cenOn);
	}
}

function showAddress(location) {
	deleteOverlays();
    var latlng = location;
    geocoder.geocode({'latLng': latlng}, function(results, status) {
	if (status == google.maps.GeocoderStatus.OK) {
        if (results[1]) {
          marker = new google.maps.Marker({
              position: latlng,
              map: map
          });
          markersArray.push(marker);
          
          var address = "";
          for (i=0;i<results[0].address_components.length;i++){
        	  address += results[0].address_components[i].long_name + ', ';
        	  if(results[0].address_components[i].types[0] == 'locality'){
        		  address = address.substring(0, address.length-2) + '<br />';
        	  }
          }
          if(address != ""){
    		  address = address.substring(0, address.length-2) + '<br />';
    	  }

          var contentString = '<b>LatLng:</b>' + latlng + '<br>' +
          '<b>LatLng:</b>' + address;
          
          infowindow.setContent(contentString);//results[1].formatted_address);
          infowindow.open(map, marker);
          $('#location_latitude').val(latlng.lat());
          $('#location_longitude').val(latlng.lng());
        }
      } else {
        //alert("Geocoder failed due to: " + status);
      }
    });

}

function centerOn(lat, lng) {
	cenOn = new google.maps.LatLng(lat, lng);
}

function deleteOverlays() {
	 if (markersArray) {
	   for (i in markersArray) {
	     markersArray[i].setMap(null);
	   }
	   markersArray.length = 0;
	 }
}

function placeAddress(location) {
	map.setCenter(location);
	showAddress(location);
}

function searchAddress(address){
	geocoder.geocode({'address':address}, function(results, status)     {
		  if (status == google.maps.GeocoderStatus.OK) {
			  deleteOverlays();
		      map.setCenter(results[0].geometry.location);
		      marker = new google.maps.Marker({
		          map: map,
		          position: results[0].geometry.location
		      });
		      markersArray.push(marker);
		      
		      var address = "";
	          for (i=0;i<results[0].address_components.length;i++){
	        	  address += results[0].address_components[i].long_name + ', ';
	        	  if(results[0].address_components[i].types[0] == 'locality'){
	        		  address = address.substring(0, address.length-2) + '<br />';
	        	  }
	          }
	          if(address != ""){
	    		  address = address.substring(0, address.length-2) + '<br />';
	    	  }

	          var contentString = '<b>LatLng:</b>' + results[0].geometry.location + '<br>' +
	          '<b>LatLng:</b>' + address;
	          
	          infowindow.setContent(contentString);//results[1].formatted_address);
	          infowindow.open(map, marker);
	          $('#location_latitude').val(results[0].geometry.location.lat());
	          $('#location_longitude').val(results[0].geometry.location.lng());
		  } else {
			  $("#search-warning").html("This address could not be located.");
			  $("#search-warning").hide('slow');
		  }
		});
}
//Nick - Them Digital