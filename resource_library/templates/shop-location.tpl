{block name=body}

<div class="container content">
  	<div class="row">
        <div class="col-md-push-9 col-md-3">
            	<div class="search">
            		<form accept-charset="UTF-8" action="/search" method="get" id="search-form" > 
            			<input type="text" name="q" value="Site search" onfocus="if(this.value == 'Site search') { this.value = ''; }" onblur="if(this.value == '') { this.value = 'Site search'; }" id="searchbox" />
            		</form>
            	</div><!-- /.search -->
        </div>
  	
		<div class="col-md-pull-3 col-md-8">    
	        {include file='breadcrumbs.tpl'}
	        <h1>{$listing_name} [shop-location.tpl]</h1>
		</div>


    	
    </div><!-- /.row -->
    <div class="row">
    	<div class="col-md-12">
	            <div id="map-canvas">Loading Map...</div>
	            <small>
					<a target="_blank" href="https://maps.google.com/maps?z=16&q={$location_latitude},{$location_longitude}" title="View Larger Map" >View Larger Map</a>
				</small>
			
        </div>
        <br><br>
        <div class="col-md-3 col-sm-6 location-summary location-details">
            <div>
            	<span class="location-suburb">{$listing_name|upper}</span> <span class="green">{if $listing_flag1}7 DAYS{/if} {if $listing_flag2}Day/Night{/if}</span><br>
                {if $listing_flag3}<span class="compound">(Compounding Pharmacy)</span><br>{/if}
                {$location_street}<br>
                {$location_state} {$location_postcode}<br>
                <span class="location-phone">P <a href="tel:{$d.location_phone}">{$location_phone}</a></span><br>
                E {obfuscate email=$location_email}<br>
                {if $location_url}<img src="/images/template/locations-fb.png" /> <a href="{$d.location_url}" target="_blank">Find us on Facebok</a><br>{/if}
            </div>
        </div><!-- /.location-summary -->
        <div class="col-md-3 col-sm-6">
            <p><strong>Opening hours</strong><br>
            {$listing_content3}
        </div><!-- /.opening hours -->
        <div class="col-md-6 col-sm-12">
        	{if $listing_content2}<img src="{$listing_content2}" alt="{$listing_name} image" title="{$listing_name} image"/>{/if}
        </div>
    </div>
  </div><!-- /.container -->

<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
            
<script type="text/javascript">
function LoadMap(){
	var myLatlng = new google.maps.LatLng({$location_latitude}, {$location_longitude});
	var mapOptions = {
   		center: myLatlng,
       	zoom: 15,
       	mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        title:"{$listing_title}"
    });
}
window.onload=LoadMap;
</script>
{/block}
