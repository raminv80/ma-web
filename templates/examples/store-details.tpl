{block name=body}
<div id="banners">
	<div id="banner-top" class="carousel slide" data-ride="carousel">
  	<!-- Wrapper for slides -->
		<div class="carousel-inner">
			<div class="item active">
				<img src="{if $listing_parent.listing_image}{$listing_parent.listing_image}{else}/images/bannerin.jpg{/if}" alt="banner">
				<div class="carousel-caption">
					<div class="container">
						<div class="row">
			  			<div class="col-sm-7">
				  			<h1><span>{$listing_name}</span></h1>
			  			</div>
						</div>
					</div>
				</div>	        
			</div>        			    
		</div>
	</div>
	<div id="what">
 		<h3>What are you looking for?</h3>
 		<ul>
 			<li><a href="/wood-heaters/how-to-choose-a-wood-heater" title="Information on how to choose a wood heater">Information on how to choose a wood heater</a></li>
			<li><a href="/manufacturers-suppliers-and-services/local-suppliers-and-service-providers" title="Find retailers, suppliers and services in your area">Find retailers, suppliers and services in your area</a></li>
 		</ul>
 	</div>
</div>

<div id="maincont">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12">
				{include file='breadcrumbs.tpl'}		
			</div>
		</div>
		<div class="row">
			<div class="col-sm-3">
				<img src="{if $listing_image}{$listing_image}{else}/images/grey.png{/if}" class="img-responsive" alt="image" title="{$listing_name}" />
			</div>
			<div itemscope itemtype="http://schema.org/LocalBusiness" class="col-sm-3" id="manudet">
				<h5><span itemprop="name">{$listing_name}</span></h5>
				<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
					<span itemprop="streetAddress">{$location_street}</span>,<br />
					<span itemprop="addressLocality">{$location_suburb}</span>, <span itemprop="addressRegion">{$location_state}</span>, <span itemprop="postalCode">{$location_postcode}</span><br />
					<span itemprop="addressCountry">{$location_country}</span>  <br />
					{if $location_url}<a href="{$location_url}" target="_blank" title="{$location_url}" itemprop="url">{$location_url}</a><br />{/if}
					{if $location_email}{obfuscate email=$location_email}<br />{/if}
					{if $location_phone}P <a href="tel:{$location_phone}" title="Click here to call" itemprop="telephone">{$location_phone}</a><br />{/if}
					{if $location_fax}F {$location_fax}{/if}
				</div>
			</div>
			{if $location_latitude neq '' && $location_longitude neq ''}
			<div id="map" class="col-sm-6" data-name="{$listing_name}" data-lat="{$location_latitude}" data-lon="{$location_longitude}" itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">
         <meta itemprop="latitude" content="{$location_latitude}" />
         <meta itemprop="longitude" content="{$location_longitude}" />
          <div id="map-canvas">Loading Map...</div>
      </div>
      {/if}
     </div>
     <div class="row">
			<div class="col-sm-12">
				{$listing_content1}
			</div>
			<div class="col-sm-12">
			{if $business_manufacturer || $business_importer_distributor || $business_retailer  || $business_fuel_supplier  || $business_installer  || $business_maintenance_provider }
				<span class="biznature"><b>Category: </b></span>
				{if $business_manufacturer}<span class="biznature">Manufacturer</span>{/if}
				{if $business_importer_distributor}<span class="biznature">Importer Distributor</span>{/if}
				{if $business_retailer}<span class="biznature">Retailer</span>{/if}
				{if $business_fuel_supplier}<span class="biznature">Fuel Supplier</span>{/if}
				{if $business_installer}<span class="biznature">Installer</span>{/if}
				{if $business_maintenance_provider}<span class="biznature">Maintenance Provider</span>{/if}
			{/if}
			</div>
		</div>
	</div>
</div>

<div id="orangebox" class="visible-xs">

</div>

{if $location_latitude neq '' || $location_longitude neq ''}
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
{/if}

{/block}
