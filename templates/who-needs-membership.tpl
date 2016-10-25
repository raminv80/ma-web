{block name=body}
<div id="pagehead">
	<div class="bannerout">
		<img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
	</div>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-10 col-md-offset-1 text-center">
				<h1>{$listing_title}</h1>
				{$listing_content1}
			</div>
		</div>
	</div>
</div>


<div id="who-needs">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
	  	<div id="accordion">
		  	{assign var='cnt' value=0}
		  	{foreach $additionals as $ad}
		  	{assign var='cnt' value=$cnt+1}
			<h3>
				<div class="head-img">
				<img src="{if $ad.additional_image}{$ad.additional_image}{else}/images/default-who-needs-icon.png{/if}" alt="{$ad.additional_name} icon">
				</div>
				<div class="head-text">
					<div class="head-title">{$ad.additional_name}</div>
					<div class="shortdesc">{$ad.additional_description}</div>
				</div>
			</h3>
			<div>
				{$ad.additional_content1}
			</div>
          {/foreach}
	  	</div>
	</div>
  </div>

    <div class="row">
	    <div class="col-sm-12 text-center">
		    <br /><br />
			{$listing_content2}
			<br />
			<a href="/how-to-become-a-member" class="btn btn-red">Join now</a>
	    </div>
    </div>
  </div>
</div>

<div id="testimonial-list" class="who-needs">
  <div class="container">
    <div class="row" id="testcont">
	  <div class="col-sm-12 col-md-10 col-md-offset-1 text-center">
	  <h3>The voice for people living with medical conditions</h3>
	  <p>MedicAlert membership has given thousands of Australians peace of mind knowing that they’re protected in an emergency.<br />
		  <a href="/testimonials">View our members’ stories here ></a></p>
	  </div>

	  <div id="tests" class="hidden-xs">
      {foreach $testimonials as $da}
      <div class="col-sm-6 col-md-4 testresout">
        <div class="testres text-center">
          <a href="/testimonials/{$da.listing_url}"> <img src="{if $da.listing_image}{$da.listing_image}{else}/images/testimonial-noimg.png{/if}?width=96&height=96&crop=1" alt="{$da.listing_name}" class="img-responsive fullwidth">
          </a>
          <div class="testrestext">
            <div class="h5">{$da.listing_name}</div>
            <div class="testloc">{$da.listing_content1}</div>
            <div class="testtext">{$da.listing_content2|truncate:90:"..."}</div>
            <a href="/testimonials/{$da.listing_url}" class="readart"> Read my story ></a>
          </div>
        </div>
      </div>
      {/foreach}
	  </div>
    </div>
  </div>
</div>
{/block}

{block name=tail}
<script type="text/javascript" src="/includes/js/jquery-ui.js"></script>
<script>
  $( function() {
    var icons = {
      header: "glyphicon glyphicon-plus",
      activeHeader: "glyphicon glyphicon-minus"
    };
    $( "#accordion" ).accordion({
      icons: icons,
	  heightStyle: "content",
      collapsible: true,
      activate: function( event, ui ) {
        if(!$.isEmptyObject(ui.newHeader.offset()) && !isScrolledIntoView(ui.newHeader)) {
            $('html:not(:animated), body:not(:animated)').animate({ scrollTop: ui.newHeader.offset().top }, 'slow');
        }
    }
    });
    $( "#accordion1" ).accordion({
      icons: icons,
	  heightStyle: "content",
      collapsible: true
    });
  } );
</script>
{/block}
