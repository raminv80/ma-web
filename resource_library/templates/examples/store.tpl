{block name=head}
<style type="text/css">
#bannerout {
    background: url("{if $listing_parent.listing_image}{$listing_parent.listing_image}{else}/images/slider1.jpg{/if}") no-repeat;
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
      <div class="col-sm-3 sidebar menu2item2 hidden-xs">
 			<a data-target="#needhelp" data-toggle="modal" href="#needhelp"><img src="/images/needhelp.png" alt="help-icon" />Need help?</a>
			</div>
			<div class="col-sm-9">
	      {include file='store-detail.tpl'}
	    </div>
	   </div>
  </div>
</div>

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
{/block}