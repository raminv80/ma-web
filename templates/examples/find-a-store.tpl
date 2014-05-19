{block name=head}
<style type="text/css">
#bannerout {
  background: url("{if $listing_image}{$listing_image}{else}/images/slider1.jpg{/if}") no-repeat;
}
</style>
{/block}

{block name="body"}
<div id="maincont2">
    <div class="container">
    <div class="row">
      <div class="col-sm-12 breadcrumbs">
        {include file='breadcrumbs.tpl'}
      </div>
      <div class="col-sm-12">
        <h1>Steeline Store Locations</h1>
      </div>

	  <div class="col-sm-12" id="findpcout">
	     <p class="bold">Search stores by postcode</p>
	     <input type="number" id="postcodeMap" name="postcode"  onkeyup="checkPostcodeMap();"/>
	     <a href="javascript:void(0);"><img src="/images/arrow.png" alt="arrow-icon"></a>
	  </div>
		
      <div class="col-sm-12" id="map1out">
      	<div id="map1">
          	Loading Map...
	      </div>
        <!-- <div id="map1">
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3296.9786076112937!2d140.6043304!3d-34.2745977!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x7957573e9eadb9d5!2sBuildpro+-+Riverland!5e0!3m2!1sen!2sin!4v1395068135334" width="870" height="200" frameborder="0" style="border:0"></iframe>
        </div> -->
      </div>
      <div class="col-sm-10">
	      <div class="row locrow">
	      </div>
      	<div class="location-map">
      		{foreach $data as $st}
	      		<div class="map-store" data-lat="{$st.location_latitude}" data-lon="{$st.location_longitude}" data-name="{$st.listing_name}" data-url="{$st.listing_url}">
	      		</div>
	      	{/foreach}
      	</div>
      </div>
    </div>
    </div>
    </div>

<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript"> 
function checkPostcodeMap(){
	$.ajax({
     type: "POST",
     url: "/diy/process/neareststockist",
     cache: false,
     data: 'action=neareststoremap&postcode='+ $('#postcodeMap').val()+'&formToken={$token}',
     dataType: "json",
     success: function(data) {
    	 try{
    		 if(data.error == ""){
    		  $('.location-map').html(data.template);
    		  if($('.map-store').length > 0)  {
        		   mapRender();
        		   ga('send', 'event', 'find store', 'postcode', $('#postcodeMap').val());
    		  }
    		  var contenttop=$('#locationoptions').offset();
			  $('html,body').animate({ldelim}scrollTop:contenttop.top{rdelim},1000);
    		 }
    	 }catch(err){}
     },
     error: function(){
     }
   });
}


 $(window).load(function(){
	loadMap();
	mapRender();
}); 

var map;
var markers = [];
var bound = new google.maps.LatLngBounds();
var coords = null;

 function loadMap() {
	var startLatlng = new google.maps.LatLng(-34.9201702,138.5943725);
	var options = {
	    zoom: 8,
	    center: startLatlng,
	    mapTypeId: google.maps.MapTypeId.ROADMAP,
	    panControl: true,
	    maxZoom: 19,
	    minZoom: 0
	  };
	map = new google.maps.Map(document.getElementById("map1"),options);
} 

function mapRender(){

	deleteOverlays();
	$(".map-store").each(
		function(){
			// Define Marker properties
		    var pin = new google.maps.MarkerImage( '',
		        // This marker is 129 pixels wide by 42 pixels tall.
		        new google.maps.Size(77, 100),
		        // The origin for this image is 0,0.
		        new google.maps.Point(0,0),
		        // The anchor for this image is the base of the flagpole at 18,42.
		        new google.maps.Point(18, 42)
		    );

	    var contentString = '<div id="content">'+
	      '<h3>'+ $(this).attr('data-name') +'</h3>'+
	      '<a href="http://'+ document.domain + '/find-a-store/' + $(this).attr('data-url')  +'">'+
	      'View store details</a> '+
	      '</div>';
	    var infowindow = new google.maps.InfoWindow({
	        content: contentString
	    });
		  		    
			var marker;
			var latlng = new google.maps.LatLng($(this).attr('data-lat'),$(this).attr('data-lon'));
		    marker = new google.maps.Marker({
		    	position: latlng,
		        map: map,
		        title: $(this).attr('data-name'),
		        icon: 'http://' + document.domain + '/images/store-icon-blue.png'
		    });
		   
		    markers.push(marker);
		    google.maps.event.addListener(marker, 'click', function() {
		        infowindow.open(map,marker);
		      });
		    		    
		});
//	if (coords) {
//		var marker = new google.maps.Marker({
//	    	position: coords,
//	        map: map,
//	        title: 'You are here',
//	        icon: '/images/template/pin.png'
//	    });
//		markers.push(marker);
//	}
	
	resetBounds();
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

</script>
{/block}