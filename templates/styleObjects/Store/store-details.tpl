<div class="row">
	<div itemscope itemtype="http://schema.org/LocalBusiness" class="col-sm-3" id="manudet">
		<h5><span itemprop="name">Local Office</span></h5>
		<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
			<span itemprop="streetAddress">King William Rd</span>,<br />
			<span itemprop="addressLocality">Adelaide</span>, <span itemprop="addressRegion">SA</span>, <span itemprop="postalCode">5000</span><br />
			<span itemprop="addressCountry">Australia</span>  <br />
			<a href="#" target="_blank" title="" itemprop="url">example.com</a><br />
			online@them.com.au<br />
			P <a href="tel:08 8333 3333" title="Click here to call" itemprop="telephone">08 8333 3333</a><br />
			F 08 8333 3333
		</div>
	</div>
	<div id="map" class="col-sm-6" data-name="Local Office" data-lat="-34.929" data-lon="138.6010" itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">
     <meta itemprop="latitude" content="-34.929" />
     <meta itemprop="longitude" content="138.6010" />
     <div id="map-canvas">Loading Map...</div>
  </div>
</div>

<style>
#map-canvas {
	height: 430px; 
	text-align: center; 
	background-color:#eee;
}

#map-canvas img {
	max-width: none !important;
}

#map-canvas label {
	width: auto; 
	display:inline; 
}

</style>
<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
function LoadMap(){
	var myLatlng = new google.maps.LatLng($('#map').attr('data-lat'), $('#map').attr('data-lon'));
	var mapOptions = {
   		center: myLatlng,
     	zoom: 15,
     	mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
  var marker = new google.maps.Marker({
	    position: myLatlng,
      map: map,
      title: $('#map').attr('data-name')
	});
}
window.onload=LoadMap;
</script>

