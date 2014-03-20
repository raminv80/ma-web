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
	        <h1>{$listing_name}</h1>
		</div>

 		<div class="col-sm-9">
			{$listing_content1}
        </div>

 		<div class="col-sm-3">
        	{if $listing_content2}<img src="{$listing_content2}" />{/if}
        </div>
      </div><!-- /.row -->
      
      <div class="row">
    	<div class="col-md-12">
            <div id="map-canvas">Loading Map...</div>
            <small>
				<a href="javascript:void(0);" onclick="mapInNewWindow();" title="View Larger Map" >View Larger Map</a>
			</small>
			
            <br><br>
            <div class="find-nearest-location">
            <p>Find your nearest location</p>
            
            	<div class="location-postcode">
                    <form accept-charset="UTF-8" action="" onsubmit="return false;" method="post" id="find-store-form" > 
                    <input type="text" value="{if $find_location.postcode}{$find_location.postcode}{else}Enter your postcode{/if}" name="postcode" onfocus="if(this.value == 'Enter your postcode') { this.value = ''; }" onblur="if(this.value == '') { this.value = 'Enter your postcode'; }" id="location-postcode" /><br>
                    <input type="checkbox" name="7day" {if $find_location.7day} checked {/if} value="7daypharmacy"> Show me the closest 7 day pharmacy<br>
                    <input type="checkbox"  name="day_night" {if $find_location.day_night} checked {/if} value="daynightpharmacy"> Show me the closest day/night pharmacy<br>
                    <a href="javascript:void(0);" onclick="findStore();" class="find-store-btn btn">Find</a>
                    </form>
            	</div><!-- /.location-postcode -->
                <div class="clear"></div>
            </div><!-- /.find-nearest-location -->
        </div><br><br>
        
     	<div id="location-wrapper" class="col-md-12">
       		{include file='location-summary.tpl'}
       	</div>
     </div>
    
  </div><!-- /.container -->
  
<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script src="/includes/js/location.js"></script>  



{/block}
