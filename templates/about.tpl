{block name=body}
<div id="pagehead">
	<div class="bannerout">
		<img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
	</div>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<h1>{$listing_title}</h1>
				<div class="about-text">
				{$listing_content1}
				</div>
			</div>
		</div>
		<div class="row visible-xs">
			<div class="col-xs-12 text-center">
				<a href="#" id="readmore">Read more <img src="/images/down-arrow.png" alt="Down" /></a>
			</div>
		</div>
	</div>
</div>

<div id="our-history">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 text-center">
        <h3>Our history</h3>
      </div>
      {if $listing_image2}
      <div class="col-sm-4">
        <img src="{$listing_image2}" alt="Our history image" class="img-responsive">
      </div>
      {/if}
      <div class="col-sm-{if $listing_image2}8{else}12{/if}">
        {$listing_content2}
      </div>
    </div>
  </div>
</div>

<div id="directors-wrapper">
  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center">
        <h3>Board of Directors</h3>
        <p>Our national Board of Directors volunteer their time to ensure we can provide the best possible service for our members</p>
      </div>
    </div>
    <div class="row" id="dirout">
        {foreach $additionals as $ad}
          <div class="col-sm-4 col-md-3 directors text-center">
            <img src="{if $ad.additional_image}{$ad.additional_image}{else}/images/default-director.png{/if}" alt="{$ad.additional_name} photo" class="img-responsive">
            <div class="date">{$ad.additional_description}</div>
            <div class="h3">{$ad.additional_name}</div>
            {$ad.additional_content1}
          </div>
        {/foreach}
    </div>
  </div>
</div>
<div id="map-out">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<h3>Our Worldwide Affiliates</h3>
				<p>MedicAlert Foundation Australia is part of a worldwide network of MedicAlert affiliates located in over 40 countries. This network helps to protect and save lives across the globe through the personal emergency identification system. Our internationally recognised ID and 24/7 emergency response service provides our members with peace of mind every time they travel, knowing that the standardised naming conventions on their ID can be understood anywhere in the world. Health professionals in other countries are trained to look for and recognise MedicAlert's ID and symbol to help them quickly diagnose a member's condition and provide effective treatment. Here are a few of our affiliate locations.</p>
				<br>
			</div>
		</div>
		<div id="map_wrapper">
			<div id="map_canvas" class="mapping"></div>
		</div>
	</div>
</div>
{/block}

{block name=tail}
<script type="text/javascript">
jQuery(document).ready(function(){
	$("#readmore").click(function(){
		$(this).hide();
		$("#pagehead .about-text").css("height","auto");
	});
});

jQuery(function($) {
    // Asynchronously Load the map API
    var script = document.createElement('script');
    script.src = "//maps.googleapis.com/maps/api/js?key=AIzaSyAL4EBHS2lWtfisuJPrm5Y4qZAR5l0ltH8&sensor=false&callback=initialize";
    document.body.appendChild(script);
});

function initialize() {
    var map;
    var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
        mapTypeId: 'roadmap',
		icon: '../images/pin.png',
        styles: [
  {
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#323b40"
      }
    ]
  },
  {
    "elementType": "labels.icon",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  },
  {
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#616161"
      }
    ]
  },
  {
    "elementType": "labels.text.stroke",
    "stylers": [
      {
        "color": "#f5f5f5"
      }
    ]
  },
  {
    "featureType": "administrative",
    "elementType": "labels",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  },
  {
    "featureType": "administrative.land_parcel",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#bdbdbd"
      }
    ]
  },
  {
    "featureType": "landscape",
    "elementType": "labels",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  },
  {
    "featureType": "poi",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  },
  {
    "featureType": "poi",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#eeeeee"
      }
    ]
  },
  {
    "featureType": "poi",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#757575"
      }
    ]
  },
  {
    "featureType": "poi.park",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#e5e5e5"
      }
    ]
  },
  {
    "featureType": "poi.park",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#9e9e9e"
      }
    ]
  },
  {
    "featureType": "road",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#ffffff"
      },
      {
        "visibility": "off"
      }
    ]
  },
  {
    "featureType": "road",
    "elementType": "labels",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  },
  {
    "featureType": "road.arterial",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#757575"
      }
    ]
  },
  {
    "featureType": "road.highway",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#dadada"
      }
    ]
  },
  {
    "featureType": "road.highway",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#616161"
      }
    ]
  },
  {
    "featureType": "road.local",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#9e9e9e"
      }
    ]
  },
  {
    "featureType": "transit.line",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#e5e5e5"
      }
    ]
  },
  {
    "featureType": "transit.station",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#eeeeee"
      }
    ]
  },
  {
    "featureType": "water",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#c9c9c9"
      }
    ]
  },
  {
    "featureType": "water",
    "elementType": "labels",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  },
  {
    "featureType": "water",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#9e9e9e"
      }
    ]
  }
]
    };


    // Display a map on the page
    map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
    map.setTilt(45);

    // Multiple Markers
    var markers = [
        ['Australia', -34.9198864,138.6141255],
        ['USA', 37.7149345,-121.0915508],
        ['Canada', 43.7271801,-79.3419084],
        ['Cyprus', 35.1551555,33.3401727],
        ['New Zealand', -41.1246172,175.0673668],
        ['Iceland', 64.0969817,-21.8889685],
        ['South Africa', -33.9203855,18.429966],
        ['United Kingdom', 52.0378053,-0.7688055],
        ['Zimbabwe', -17.8150123,31.0308515],
        ['Malaysia', 3.1125214,101.6520863]
    ];

    // Info Window Content
    var infoWindowContent = [
        ['<div class="info_content"><strong>Australia</strong><br>11 King William St, Kent Town SA 5067, Australia<br><a target="_blank" href="https://www.medicalert.org.au/">https://www.medicalert.org.au/</a></div>'],
        ['<div class="info_content"><strong>USA</strong><br>5226 Pirrone Court, Salida, CA 95368<br><a target="_blank" href="https://www.medicalert.org/">https://www.medicalert.org/</a></div>'],
        ['<div class="info_content"><strong>Canada</strong><br>Morneau Shepell Centre II, 895 Don Mills Road, Suite 600,<br>Toronto, ON, M3C 1W3<br><a target="_blank" href="https://www.medicalert.ca/">https://www.medicalert.ca/</a></div>'],
        ['<div class="info_content"><strong>Cyprus</strong><br>18B and C. Kasos Street, Ay. Omoloyites, 1086, Nicosia<br><a target="_blank" href="http://www.medicalertcyprus.com/ ">http://www.medicalertcyprus.com/ </a></div>'],
        ['<div class="info_content"><strong>New Zealand</strong><br>CBD Towers Level 8/84-90 Main St, Upper Hutt 5018, New Zealand<br><a target="_blank" href="http://www.medicalert.co.nz/ ">http://www.medicalert.co.nz/ </a></div>'],
        ['<div class="info_content"><strong>Iceland</strong><br>Hlíðasmári 14, Kópavogur, Iceland<br><a target="_blank" href="https://www.medicalert.is/ ">http://www.medicalert.is/ </a></div>'],
        ['<div class="info_content"><strong>South Africa</strong><br>19 Louis Gradner, Foreshore, Cape Town, 8001, South Africa<br><a target="_blank" href="http://www.medicalert.co.za/ ">http://www.medicalert.co.za/</a></div>'],
        ['<div class="info_content"><strong>United Kingdom</strong><br>327 Upper Fourth Street, Milton Keynes, MK9 1EH<br><a target="_blank" href="https://www.medicalert.org.uk/ ">https://www.medicalert.org.uk/</a></div>'],
        ['<div class="info_content"><strong>Zimbabwe</strong><br>No.1 Van Praagh Avenue, Milton Park, Harare<br><a target="_blank" href="http://www.medicalert.co.zw/ ">http://www.medicalert.co.zw/</a></div>'],
        ['<div class="info_content"><strong>Malaysia</strong><br>Ground Floor, Menara Utama, Pusat Perubatan Universiti Malaya (PPUM),<br>Lembah Pantai 59100, Kuala Lumpur<br><a target="_blank" href="http://medicalert.org.my/">http://medicalert.org.my/</a></div>']
    ];

    // Display multiple markers on a map
    var infoWindow = new google.maps.InfoWindow(), marker, i;

    // Loop through our array of markers & place each one on the map
    for( i = 0; i < markers.length; i++ ) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: map,
            icon: '../images/pin.png',
            title: markers[i][0]
        });

        // Allow each marker to have an info window
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infoWindow.setContent(infoWindowContent[i][0]);
                infoWindow.open(map, marker);
            }
        })(marker, i));

        // Automatically center the map fitting all markers on the screen
        map.fitBounds(bounds);
    }

    // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
        this.setZoom(2);
        google.maps.event.removeListener(boundsListener);
    });

}

</script>
{/block}
